<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Entity\Enum\WorkBreakEnum;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVWorkBreak;

use function Symfony\Component\Translation\t;

class EuropeanCVWorkBreakType extends AbstractType implements EventSubscriberInterface
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => t('trexima_european_cv.form_label.work_break_description', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_placeholder.work_break_description', [], 'trexima_european_cv'),
                ],
            ])
            ->addEventSubscriber($this);
    }

    private function addTypeField(FormInterface $form, array $choices): void
    {
        /** @var EuropeanCVWorkBreak $data */
        $data = $form->getData();

        $form
            ->add('type', ChoiceType::class, [
                'required' => false,
                'mapped' => false,
                'label' => t('trexima_european_cv.form_label.work_break_type', [], 'trexima_european_cv'),
                'placeholder' => t('trexima_european_cv.form_label.work_break_type', [], 'trexima_european_cv'),
                'multiple' => false,
                'by_reference' => false,
                'data' => $data->getType()?->value ?: $data->getTitle(),
                'choices' => $choices,
                'choice_label' => function ($choice) {
                    if ($choice instanceof WorkBreakEnum) {
                        return t('trexima_european_cv.form_label.work_break_'.strtolower($choice->value), [], 'trexima_european_cv');
                    }

                    return $choice;
                },
                'choice_value' => function ($choice) {
                    if ($choice instanceof WorkBreakEnum) {
                        return $choice->value;
                    }

                    return $choice;
                },
                'attr' => [
                    'data-trexima-european-cv-dynamic-collection-sort-by' => 1,
                    'data-controller' => 'ui--select2',
                    'data-ui--select2-placeholder-value' => t('trexima_european_cv.form_label.work_break_type', [], 'trexima_european_cv')->trans($this->translator),
                    'data-ui--select2-theme-value' => 'worki-floating',
                    'data-ui--select2-tags-value' => true,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCVWorkBreak::class,
            'error_mapping' => [
                'title' => 'type',
            ],
            'attr' => [
                'id' => 'EuropeanCVWorkBreakType',
            ],
        ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'postSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function postSetData(FormEvent $formEvent): void
    {
        /** @var EuropeanCVWorkBreak $data */
        $data = $formEvent->getData();

        $choices = WorkBreakEnum::cases();

        if (!$data->getType() && ($title = $data->getTitle())) {
            array_unshift($choices, $title);
        }

        $this->addTypeField($formEvent->getForm(), $choices);
    }

    public function preSubmit(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();

        $choices = WorkBreakEnum::cases();

        if (!WorkBreakEnum::tryFrom((int) $type = $data['type'])) {
            array_unshift($choices, $type);
        }

        $this->addTypeField($formEvent->getForm(), $choices);
    }

    public function onSubmit(FormEvent $formEvent): void
    {
        /** @var EuropeanCVWorkBreak|null $data */
        $data = $formEvent->getData();

        if (null === $data) {
            return;
        }

        if ($type = $formEvent->getForm()->get('type')->getData()) {
            if ($type instanceof WorkBreakEnum) {
                $data->setType($type);
                $title = t('trexima_european_cv.form_label.work_break_'.strtolower($type->value), [], 'trexima_european_cv')->trans($this->translator);
                $data->setTitle($title);
            } else {
                $data->setType(null);
                $data->setTitle($type);
            }
        }
    }
}
