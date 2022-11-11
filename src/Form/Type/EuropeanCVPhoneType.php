<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPhone;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EuropeanCVPhoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', HiddenType::class)
            ->add('type', ChoiceType::class, [
                'label' => 'Typ',
                'placeholder' => 'Prosím, vyberte',
                'required' => false,
                'choices'  => array_flip(EuropeanCVPhone::TYPES)
            ])
            ->add('number', TextType::class, [
                'label' => 'Tel.',
                'required' => false,
                'attr' => [
                    'placeholder' => 'V tvare: +421918123456'
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
            'data_class' => EuropeanCVPhone::class
        ]);
    }
}