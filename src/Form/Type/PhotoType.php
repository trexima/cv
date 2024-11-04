<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints as Assert;
use Trexima\EuropeanCvBundle\Form\DataTransformer\ArrayToJsonTransformer;
use Trexima\EuropeanCvBundle\Form\Model\Photo;

class PhotoType extends AbstractType
{
    private const MAX_WIDTH = 4096; // Some sane amount
    private const MAX_HEIGHT = 4096; // Some sane amount
    private const MAX_SIZE = 16 << 20; // 16MB
    private const MIME_TYPES = ['image/jpg', 'image/jpeg', 'image/png'];

    private const DEFAULT_IMAGE = '//default.jpg';

    public function __construct(private readonly ArrayToJsonTransformer $arrayToJsonTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['accept' => implode(',', self::MIME_TYPES).',.jpg,.jpeg,.png'],
                'error_bubbling' => true,
                'constraints' => [
                    new Assert\Image(
                        maxSize: $options['max_size'],
                        mimeTypes: self::MIME_TYPES,
                        maxWidth: $options['max_width'],
                        maxHeight: $options['max_height'],
                        detectCorrupted: true,
                    ),
                ],
            ])
            ->add('options', HiddenType::class, [
                'required' => false,
                'error_bubbling' => true,
                'constraints' => [
                    new Assert\Type('array'),
                    new Assert\Collection([
                        'x' => new Assert\GreaterThanOrEqual(0, message: 'trexima_european_cv.photo.constraint.options.x'),
                        'y' => new Assert\GreaterThanOrEqual(0, message: 'trexima_european_cv.photo.constraint.options.y'),
                        'width' => new Assert\GreaterThan(0, message: 'trexima_european_cv.photo.constraint.options.width'),
                        'height' => new Assert\GreaterThan(0, message: 'trexima_european_cv.photo.constraint.options.height'),
                        'rotate' => new Assert\Range(min: -360, max: 360, notInRangeMessage: 'trexima_european_cv.photo.constraint.options.rotate'),
                    ], missingFieldsMessage: 'trexima_european_cv.photo.constraint.options'),
                ],
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, $this->postSetData(...));

        $builder->get('options')->addModelTransformer($this->arrayToJsonTransformer);
    }

    public function postSetData(FormEvent $formEvent): void
    {
        $form = $formEvent->getForm();
        /** @var Photo|null $viewData */
        $viewData = $form->getViewData();

        $this->addExistingFileSubform($form, $viewData);
    }

    private function addExistingFileSubform(FormInterface $form, ?Photo $viewData): void
    {
        $constraints = [];
        if (null !== $viewData) {
            $constraints[] = new Assert\EqualTo(
                value: $viewData->getExistingFileId(),
                message: 'This value is not valid.',
            );
        }

        $form->add('existingFileId', HiddenType::class, [
            'data' => $viewData?->getExistingFileId(),
            'required' => false,
            'error_bubbling' => true,
            'constraints' => $constraints,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var Photo|null $data */
        $data = $form->getViewData();

        $view->vars['existing_image'] = $data?->getExistingFileUrl();
        $view->vars['default_image'] = $options['default_image'];
        $view->vars['aspect_ratio'] = $options['aspect_ratio'];
        $view->vars['max_size'] = $options['max_size'];
        $view->vars['max_width'] = $options['max_width'];
        $view->vars['max_height'] = $options['max_height'];
        $view->vars['max_size_message'] = $options['max_size_message'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Photo::class,
                'error_bubbling' => false,
                'aspect_ratio' => null, // Used as option for cropper
                'max_width' => self::MAX_WIDTH, // Used in validation and view
                'max_height' => self::MAX_HEIGHT, // Used in validation and view
                'max_size' => self::MAX_SIZE, // Used in validation and view
                'max_size_message' => null, // Used in view
                'default_image' => self::DEFAULT_IMAGE,
            ])
            ->setAllowedTypes('aspect_ratio', ['float', 'int', 'null'])
            ->setAllowedTypes('max_width', 'integer')
            ->setAllowedTypes('max_height', 'integer')
            ->setAllowedTypes('max_size', 'integer')
            ->setAllowedTypes('max_size_message', ['null', 'string', TranslatableMessage::class])
            ->setAllowedTypes('default_image', 'string');
    }
}
