<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPractice;
use Trexima\EuropeanCvBundle\Form\Type\MonthYearRangeType;

class EuropeanCVPracticeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Zamestnanie',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Napíšte zamestnanie alebo oblasť'
                ]
            ])
            ->add('employee', TextType::class, [
                'label' => 'Zamestnávateľ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Zamestnávateľ'
                ]
            ])
            ->add('dateRange', MonthYearRangeType::class, [
                'label' => 'Rozsah',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis práca',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Opíšte Vaše pracovné skúsenosti'
                ]
            ])
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCVPractice::class
        ]);
    }
}