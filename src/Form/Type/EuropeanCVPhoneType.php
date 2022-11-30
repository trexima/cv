<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPhone;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

class EuropeanCVPhoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', HiddenType::class)
            ->add('type', ChoiceType::class, [
                'label' => t('trexima_european_cv.form_label.phone_type_label', [], 'trexima_european_cv'),
                'placeholder' => t('trexima_european_cv.form_label.phone_type_placeholder', [], 'trexima_european_cv'),
                'required' => false,
                'choices'  => array_flip(EuropeanCVPhone::TYPES)
            ])
            ->add('number', TextType::class, [
                'label' => t('trexima_european_cv.form_label.phone_number_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.phone_number_placeholder', [], 'trexima_european_cv')
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