<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPractice;
use Trexima\EuropeanCvBundle\Form\Type\AtomicDateRangeType;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;

class EuropeanCVPracticeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', HiddenType::class)
            ->add('dateRange', AtomicDateRangeType::class, [
                'required' => false,
            ])->add('job', null, [
                'label' => 'Zamestnanie alebo pracovné zaradenie',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Kuchár'
                ]
            ])
            ->add('mainActivities', null, [
                'label' => 'Hlavné činnosti a zodpovednosť',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Napr. príprava jedál národných kuchýň a ďalších špecialít.'
                ]
            ])
            ->add('jobAddress', null, [
                'label' => 'Názov a adresa zamestnávateľa',
                'required' => false,
                'attr' => [
                    'placeholder' => 'VZOR, spol. s r.o., Vzorová 1, 844 07 Bratislava IV, Slovenská republika'
                ]
            ])
            ->add('industry', null, [
                'label' => 'Odvetvie hospodárstva',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Gastronómia'
                ]
            ]);
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