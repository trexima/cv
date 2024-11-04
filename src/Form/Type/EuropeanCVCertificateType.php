<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVCertificate;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVEducation;

use function Symfony\Component\Translation\t;

class EuropeanCVCertificateType extends AbstractType implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $builder->getData();
        $builder
            ->add('title', TextType::class, array_merge([
                'label' => t('trexima_european_cv.form_label.certificate_title_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.certificate_title_label', [], 'trexima_european_cv'),
                ],
            ], $options['field_options']['title'] ?? []))
            ->add('institution', TextType::class, array_merge([
                'label' => t('trexima_european_cv.form_label.certificate_institution_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.certificate_institution_label', [], 'trexima_european_cv'),
                ],
            ], $options['field_options']['title'] ?? []))
            ->add('yearRange', YearRangeType::class, array_merge([
                'required' => false,
                'label' => t('trexima_european_cv.form_label.education_year_range_label', [], 'trexima_european_cv'),
                'constraints' => [
                    new Valid(),
                ],
                'field_options' => [
                    'beginYear' => [
                        'label' => t('trexima_european_cv.form_label.certificate_start_label', [], 'trexima_european_cv'),
                    ],
                    'endYear' => [
                        'label' => t('trexima_european_cv.form_label.certificate_end_label', [], 'trexima_european_cv'),
                        'choices' => [t('trexima_european_cv.form_label.certificate_no_end_date', [], 'trexima_european_cv')->trans($this->translator) => -1] + array_reverse(array_combine(range(date('Y') - 100, date('Y') + 20), range(date('Y') - 100, date('Y') + 20)), true),
                        'attr' => [
                            'data-trexima-european-cv-dynamic-collection-sort-by' => 1,
                            'data-controller' => 'ui--select2',
                            'data-ui--select2-placeholder-value' => 'Vyberte rok',
                            'data-ui--select2-theme-value' => 'worki-floating',
                        ],
                    ],
                ],
            ], $options['field_options']['yearRange'] ?? []))
            ->add('description', TextareaType::class, array_merge([
                'label' => t('trexima_european_cv.form_label.education_description_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.education_description_placeholder', [], 'trexima_european_cv'),
                ],
            ], $options['field_options']['description'] ?? []));
        $builder->addEventSubscriber($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCVCertificate::class,
            'attr' => [
                'id' => 'EuropeanCVEducationType',
            ],
            'field_options' => [],
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
    }

    public function onPreSetData(FormEvent $formEvent): void
    {
        /** @var EuropeanCVEducation|null $data */
        $data = $formEvent->getData();

        if (null === $data) {
            return;
        }
    }
}
