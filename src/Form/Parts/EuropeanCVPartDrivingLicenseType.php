<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Type\DrivingLicenseType;
use Trexima\EuropeanCvBundle\Form\Type\EuropeanCVDrivingLicenseType;

/**
 * Driving license
 */
class EuropeanCVPartDrivingLicenseType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('drivingLicenseOwner', ChoiceType::class, [
                'required' => false,
                'label' => 'Vodičský preukaz',
                'placeholder' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Nemám vodičský preukaz' => false,
                    'Mám vodičský preukaz' => true
                ],
                'choice_attr' => fn($choiceValue, $key, $value) => [
                    'data-trexima-european-cv-group-trigger' => 'europeancv-driving-license'
                ]
            ])
            ->add('drivingLicenses', DrivingLicenseType::class, [
                'entry_type' => EuropeanCVDrivingLicenseType::class,
                'label' => false,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'required' => false
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
            'data_class' => EuropeanCV::class,
            'is_user_logged_in' => false,
            'translation_domain' => 'trexima_european_cv',
            'sex_required' => false,
            'phones_min' => 0,
            'practices_min' => 0,
            'educations_min' => 0,
            'languages_min' => 0,
            'additional_informations_min' => 0,
            'attachments_min' => 0
         ]);

        $resolver->setRequired([
            'photo_upload_route'
        ]);
    }
}