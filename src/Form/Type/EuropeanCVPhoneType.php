<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPhone;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\PhonePrefixEnum;

use function Symfony\Component\Translation\t;

class EuropeanCVPhoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prefix', EnumType::class, [
                'label' => t('trexima_european_cv.form_label.phone_type_label', [], 'trexima_european_cv'),
                'required' => false,
                'placeholder' => false,
                'class'  => PhonePrefixEnum::class,
                'attr' => [
                    'class' => "form-select border-end-1 pe-5",
                    'data-controller' => "ui--select2",
                    'data-ui--select2-theme-value' => "worki-floating",
                    'data-ui--select2-minimum-results-for-search-value' => "Infinity",
                   ' data-ui--select2-selection-css-class-value' => "rounded-end-0 pe-5",
                ],
                'form_floating' => true
            ])
            ->add('number', TextType::class, [
                'label' => t('trexima_european_cv.form_label.phone_number_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.phone_number_placeholder', [], 'trexima_european_cv'),
                    'class' => 'form-control-md'
                ],
                'form_floating' => true
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCVPhone::class
        ]);
    }
}
