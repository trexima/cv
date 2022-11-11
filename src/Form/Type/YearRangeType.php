<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Embeddable\YearRange;

/**
 * Year range widget with partial dates support.
 */
class YearRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('beginYear', ChoiceType::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Rok nástupu',
            'choices' => array_reverse(array_combine(range(date('Y')-100, date('Y')), range(date('Y')-100, date('Y'))), true),
            'attr' => [
                'data-trexima-european-cv-dynamic-collection-sort-by' => 1
            ]
        ])
        ->add('endYear', ChoiceType::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Rok ukončenia',
            'choices' => array_reverse(array_combine(range(date('Y')-100, date('Y')), range(date('Y')-100, date('Y'))), true)
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => YearRange::class,
            /**
             * Callback for empty_data is required because object
             * must be instantiate for every form element not only once!
             */
            'empty_data' => fn() => new YearRange()
        ]);
    }
}