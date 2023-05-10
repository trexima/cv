<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVEducation;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Entity\Enum\EducationTypeEnum;
use Trexima\EuropeanCvBundle\Form\Type\YearRangeType;

use function Symfony\Component\Translation\t;

class EuropeanCVEducationType extends AbstractType implements EventSubscriberInterface
{

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator
    ) {}

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $builder
            ->add('type', EnumType::class, array_merge([
                'data' => $options['education_type'],
                'class' => EducationTypeEnum::class,
                'mapped' => true,
                'hidden' => true
            ], ($options['field_options']['type'] ?? [])))
            ->add('title', TextType::class, array_merge([
                'label' => t('trexima_european_cv.form_label.education_title_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.education_title_placeholder', [], 'trexima_european_cv')
                ]
            ], ($options['field_options']['title'] ?? [])))
            ->add('yearRange', YearRangeType::class, array_merge([
                'required' => false,
                'label' => t('trexima_european_cv.form_label.education_year_range_label', [], 'trexima_european_cv'),
                'constraints' => [
                    new Valid(),
                ],
                'field_options' => [
                    'endYear' => [
                        'choices' => [t('trexima_european_cv.form_label.education_not_finished', [], 'trexima_european_cv')->trans($this->translator) => -1] + array_reverse(array_combine(range(date('Y')-100, date('Y')), range(date('Y')-100, date('Y'))), true),
                    ]
                ]
            ], ($options['field_options']['yearRange'] ?? [])));

            if ($options['education_type']->value !== EducationTypeEnum::EDUCATION_ELEMENTARY_SCHOOL->value) {
                $builder->add('kov', KovType::class, array_merge([
                    'label' => t('trexima_european_cv.form_label.education_kov', [], 'trexima_european_cv'),
                    'data' => $entity,
                    'multiple' => false,
                    'required' => false,
                    'by_reference' => false,
                    'form_floating' => true,
                    'mapped' => false,
                    'class' => EuropeanCVEducation::class,
                    'row_attr' => [
                        'class' => 'mt-3.5'
                    ],
                ], ($options['field_options']['kov'] ?? [])));

                $builder->add('description', TextareaType::class, array_merge([
                    'label' => t('trexima_european_cv.form_label.education_description_label', [], 'trexima_european_cv'),
                    'required' => false,
                    'attr' => [
                        'placeholder' => t('trexima_european_cv.form_label.education_description_placeholder', [], 'trexima_european_cv')
                    ]
                ], ($options['field_options']['description'] ?? [])));
            }
            $builder->addEventSubscriber($this);
            
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCVEducation::class,
            'education_type' => EducationTypeEnum::EDUCATION_ELEMENTARY_SCHOOL,
            'attr' => [
                'id' => 'EuropeanCVEducationType'
            ],
            'field_options' => []
        ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onSubmit(FormEvent $formEvent): void
    {
        /** @var EuropeanCVEducation|null $data */
        $data = $formEvent->getData();

        if (null === $data) {
            return;
        }

        if ($data->getType()->value !== EducationTypeEnum::EDUCATION_ELEMENTARY_SCHOOL->value) {
            $kov = $formEvent->getForm()->get('kov')->getData();
            if (!empty($kov)) {
                $code = $kov->getKovCode();
                if ($code === $kov->getKovTitle()) {
                    $code = null;
                }
                $data->setKovCode($code);
                $data->setKovTitle($kov->getKovTitle());
            }
        }
    }

    public function onPreSetData(FormEvent $formEvent): void
    {
        /** @var EuropeanCVEducation|null $data */
        $data = $formEvent->getData();

        if (null === $data) {
            return;
        }

        if ($data->getKovCode() === null && $data->getKovTitle() !== null) {
            $data->setKovCode($data->getKovTitle());
        }
    }
}