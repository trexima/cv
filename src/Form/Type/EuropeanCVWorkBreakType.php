<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Entity\Enum\WorkBreakEnum;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVWorkBreak;
use Trexima\EuropeanCvBundle\Form\Type\MonthYearRangeType;

use function Symfony\Component\Translation\t;

class EuropeanCVWorkBreakType extends AbstractType implements EventSubscriberInterface
{   
    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EnumType::class, [
                'class' => WorkBreakEnum::class,
                'required' => true,
                'label' => t('trexima_european_cv.form_label.work_break_type', [], 'trexima_european_cv'),
                'placeholder' => t('trexima_european_cv.form_label.work_break_type', [], 'trexima_european_cv'),
                'multiple' => false,
                'choice_label' => fn(WorkBreakEnum $choice) => match ($choice) {
                    default => t('trexima_european_cv.form_label.work_break_' . strtolower($choice->value), [], 'trexima_european_cv'),
                },
                'attr' => [
                    'data-trexima-european-cv-dynamic-collection-sort-by' => 1,
                    'data-controller' => 'ui--select2',
                    'data-ui--select2-placeholder-value' => t('trexima_european_cv.form_label.work_break_type', [], 'trexima_european_cv')->trans($this->translator),
                    'data-ui--select2-theme-value' => 'worki-floating'
                ]
            ])
            ->add('dateRange', MonthYearRangeType::class, [
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Valid(),
                ],
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
                'label' => t('trexima_european_cv.form_label.work_break_description', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_placeholder.work_break_description', [], 'trexima_european_cv'),
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
            'data_class' => EuropeanCVWorkBreak::class,
            'attr' => [
                'id' => 'EuropeanCVWorkBreakType'
            ],
        ]);
    }
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPostSubmit(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();
        $type = $data->getType();
        if ($type !== null) {
            $title = t('trexima_european_cv.form_label.work_break_' . strtolower($type->value), [], 'trexima_european_cv')->trans($this->translator);
            $data->setTitle($title);
        }
    }
}