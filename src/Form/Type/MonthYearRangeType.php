<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Embeddable\MonthYearRange;

/**
 * Date range widget with partial dates support.
 */
class MonthYearRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('beginMonth', ChoiceType::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Mesiac',
            'choices' => array_combine(range(1, 12), range(1, 12)),
            'attr' => [
                'data-trexima-european-cv-dynamic-collection-sort-by' => 2
            ]
        ])
        ->add('beginYear', ChoiceType::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Rok',
            'choices' => array_reverse(array_combine(range(date('Y')-100, date('Y')), range(date('Y')-100, date('Y'))), true),
            'attr' => [
                'data-trexima-european-cv-dynamic-collection-sort-by' => 1
            ]
        ])
        ->add('endMonth', ChoiceType::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Mesiac',
            'choices' => array_combine(range(1, 12), range(1, 12))
        ])
        ->add('endYear', ChoiceType::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Rok',
            'choices' => array_reverse(array_combine(range(date('Y')-100, date('Y')), range(date('Y')-100, date('Y'))), true)
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MonthYearRange::class,
            /**
             * Callback for empty_data is required because object
             * must be instantiate for every form element not only once!
             */
            'empty_data' => fn() => new MonthYearRange()
        ]);
    }
}