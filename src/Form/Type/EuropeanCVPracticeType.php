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
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPractice;
use Trexima\EuropeanCvBundle\Facade\Harvey;
use Trexima\EuropeanCvBundle\Form\Type\MonthYearRangeType;

use function Symfony\Component\Translation\t;

class EuropeanCVPracticeType extends AbstractType implements EventSubscriberInterface
{
    public function __construct(
        private readonly Harvey $harvey,
        private readonly TranslatorInterface $translator
    )
    {
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $builder
            ->add('iscoCode', SkIscoType::class, array_merge([
                'label' => t('trexima_european_cv.form_label.practice_isco_code_label', [], 'trexima_european_cv'),
                'data' => $entity,
                'multiple' => false,
                'required' => false,
                'by_reference' => false,
                'form_floating' => true,
                'mapped' => false,
                'class' => EuropeanCVPractice::class,
                'row_attr' => [
                    'class' => 'mt-3.5'
                ],
            ], ($options['field_options']['iscoCode'] ?? [])))
            ->add('employee', TextType::class, [
                'label' => t('trexima_european_cv.form_label.practice_employee_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.practice_employee_placeholder', [], 'trexima_european_cv'),
                ]
            ])
            ->add('dateRange', MonthYearRangeType::class, [
                'label' => false,
                'required' => false,
                'field_options' => [
                    'beginMonth' => [
                        'label' => t('trexima_european_cv.form_label.month_year_range_begin_month_placeholder', [], 'trexima_european_cv'),
                        'placeholder' => t('trexima_european_cv.form_label.month_year_range_begin_month_placeholder', [], 'trexima_european_cv'),
                    ],
                    'endMonth' => [
                        'label' => t('trexima_european_cv.form_label.month_year_range_begin_month_placeholder', [], 'trexima_european_cv'),
                        'placeholder' => t('trexima_european_cv.form_label.month_year_range_begin_month_placeholder', [], 'trexima_european_cv'),
                    ],
                    'beginYear' => [
                        'label' => t('trexima_european_cv.form_label.month_year_range_end_year_placeholder', [], 'trexima_european_cv'),
                        'placeholder' => t('trexima_european_cv.form_label.month_year_range_end_year_placeholder', [], 'trexima_european_cv'),
                    ],
                    'endYear' => [
                        'label' => t('trexima_european_cv.form_label.month_year_range_end_year_placeholder', [], 'trexima_european_cv'),
                        'placeholder' => t('trexima_european_cv.form_label.month_year_range_end_year_placeholder', [], 'trexima_european_cv'),
                    ],
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => t('trexima_european_cv.form_label.practice_description_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.practice_description_placeholder', [], 'trexima_european_cv')
                ]
            ])
            ->addEventSubscriber($this);
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCVPractice::class,
            'attr' => [
                'id' => 'EuropeanCVPracticeType'
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
        /** @var EuropeanCVPractice|null $data */
        $data = $formEvent->getData();
    
        $iscoCode = $formEvent->getForm()->get('iscoCode')->getData();
        $code = $iscoCode->getIscoCode();
        if ($code === $iscoCode->getTitle()) {
            $code = null;
        }
        $data->setIscoCode($code);
        $data->setTitle($iscoCode->getTitle());
    }

    public function onPreSetData(FormEvent $formEvent): void
    {
        /** @var EuropeanCVPractice|null $data */
        $data = $formEvent->getData();

        if (null === $data) {
            return;
        }

        if ($data->getIscoCode() === null && $data->getTitle() !== null) {
            $data->setIscoCode($data->getTitle());
        }
    }
}