<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
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
        $entity = $builder->getData();
        $type = $entity->getType();

        $typeChoices = WorkBreakEnum::cases();
        if (!empty($entity->getTitle() && empty($entity->getType()))) {
            $typeChoices[] = $entity->getTitle();
            $type = $entity->getTitle();
        } else {
            $type = $type?->value;
        }

        $builder
            ->add('type', ChoiceType::class, [
                'required' => true,
                'mapped' => false,
                'label' => t('trexima_european_cv.form_label.work_break_type', [], 'trexima_european_cv'),
                'placeholder' => t('trexima_european_cv.form_label.work_break_type', [], 'trexima_european_cv'),
                'multiple' => false,
                'by_reference' => false,
                'data' => $type,
                'choices' => $typeChoices,
                'choice_label' => function ($choice) {
                    if ($choice instanceof WorkBreakEnum) {
                        return t('trexima_european_cv.form_label.work_break_' . strtolower($choice->value), [], 'trexima_european_cv');
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
                'constraints' => [
                    new Length(max: 100),
                ],
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
            $builder->get('type')->resetViewTransformers();
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
            FormEvents::POST_SUBMIT => 'onSubmit',
        ];
    }

    public function onSubmit(FormEvent $formEvent): void
    {
        /** @var EuropeanCVWorkBreak|null $data */
        $data = $formEvent->getData();

        if (null === $data) {
            return;
        }

        $type = $formEvent->getForm()->get('type')->getData();

        $isEnum = false;
        if ($type !== null) {
            foreach (WorkBreakEnum::cases() as $case) {
                if ($case->value == $type) {
                    $data->setType($case);
                    $title = t('trexima_european_cv.form_label.work_break_' . strtolower($case->value), [], 'trexima_european_cv')->trans($this->translator);
                    $data->setTitle($title);
                    $isEnum = true;
                }
            }
            if (!$isEnum) {
                $data->setType(null);
                $data->setTitle($type);
            }
        }
    }
}