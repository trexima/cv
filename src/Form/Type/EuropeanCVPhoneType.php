<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\PhonePrefixEnum;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPhone;

use function Symfony\Component\Translation\t;

class EuropeanCVPhoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $prefixChoices = [];
        $prefixExtraOptions = [];

        if (class_exists(PhoneNumberUtil::class)) {
            $util = PhoneNumberUtil::getInstance();
            $intlCountries = Countries::getNames();

            foreach ($util->getSupportedRegions() as $country) {
                if (!isset($intlCountries[$country])) {
                    continue;
                }

                $prefix = '+'.$util->getCountryCodeForRegion($country);

                $prefixChoices[$country.' '.$prefix] = $prefix;
            }

            $prefixExtraOptions['preferred_choices'] = ['+421', '+420'];
        } else {
            foreach (PhonePrefixEnum::cases() as $key => $case) {
                $prefixChoices[$case->value] = $case->value;
            }
        }

        $builder
            ->add('prefix', ChoiceType::class, [
                'label' => t('trexima_european_cv.form_label.phone_type_label', [], 'trexima_european_cv'),
                'choices' => $prefixChoices,
                'required' => false,
                'placeholder' => false,
                'attr' => [
                    'class' => "form-select border-end-1 pe-5",
                    'data-controller' => "ui--select2",
                    'data-ui--select2-theme-value' => "worki-floating",
                    'data-ui--select2-minimum-results-for-search-value' => "Infinity",
                   ' data-ui--select2-selection-css-class-value' => "rounded-end-0 pe-5",
                ],
                'form_floating' => true,
            ] + $prefixExtraOptions)
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
