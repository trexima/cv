<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Embeddable\MonthYearRange;

use function Symfony\Component\Translation\t;

/**
 * Date range widget with partial dates support.
 */
class MonthYearRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $months = range(1, 12);
        $monthChoices = array_combine($months, $months);

        $years = range(date('Y'), date('Y') - 100);
        $yearChoices = array_combine($years, $years);

        $builder
            ->add('beginMonth', ChoiceType::class, array_merge([
                'label' => t('trexima_european_cv.form_label.month_year_range_begin_month_label', [], 'trexima_european_cv'),
                'required' => false,
                'placeholder' => t('trexima_european_cv.form_label.month_year_range_begin_month_placeholder', [], 'trexima_european_cv'),
                'choices' => $monthChoices,
                'attr' => [
                    'data-trexima-european-cv-dynamic-collection-sort-by' => 2,
                    'data-controller' => 'ui--select2',
                    'data-ui--select2-placeholder-value' => 'Vyberte mesiac',
                    'data-ui--select2-theme-value' => 'worki-floating',
                ],
            ], $options['field_options']['beginMonth'] ?? []))
            ->add('beginYear', ChoiceType::class, array_merge([
                'label' => false,
                'required' => false,
                'placeholder' => t('trexima_european_cv.form_label.month_year_range_begin_year_placeholder', [], 'trexima_european_cv'),
                'choices' => $yearChoices,
                'attr' => [
                    'data-trexima-european-cv-dynamic-collection-sort-by' => 1,
                    'data-controller' => 'ui--select2',
                    'data-ui--select2-placeholder-value' => 'Vyberte rok',
                    'data-ui--select2-theme-value' => 'worki-floating',
                ],
            ], $options['field_options']['beginYear'] ?? []))
            ->add('endMonth', ChoiceType::class, array_merge([
                'label' => t('trexima_european_cv.form_label.month_year_range_end_month_label', [], 'trexima_european_cv'),
                'required' => false,
                'placeholder' => t('trexima_european_cv.form_label.month_year_range_end_month_placeholder', [], 'trexima_european_cv'),
                'choices' => $monthChoices,
                'attr' => [
                    'data-trexima-european-cv-dynamic-collection-sort-by' => 1,
                    'data-controller' => 'ui--select2',
                    'data-ui--select2-placeholder-value' => 'Vyberte mesiac',
                    'data-ui--select2-theme-value' => 'worki-floating',
                ],
            ], $options['field_options']['endMonth'] ?? []))
            ->add('endYear', ChoiceType::class, array_merge([
                'label' => false,
                'required' => false,
                'placeholder' => t('trexima_european_cv.form_label.month_year_range_end_year_placeholder', [], 'trexima_european_cv'),
                'choices' => $yearChoices,
                'attr' => [
                    'data-trexima-european-cv-dynamic-collection-sort-by' => 1,
                    'data-controller' => 'ui--select2',
                    'data-ui--select2-placeholder-value' => 'Vyberte rok',
                    'data-ui--select2-theme-value' => 'worki-floating',
                ],
            ], $options['field_options']['endYear'] ?? []));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MonthYearRange::class,
            /*
             * Callback for empty_data is required because object
             * must be instantiate for every form element not only once!
             */
            'empty_data' => fn () => new MonthYearRange(),
            'field_options' => [],
        ]);
    }
}
