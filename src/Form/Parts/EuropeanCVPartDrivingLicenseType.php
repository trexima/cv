<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Type\DrivingLicenseType;
use Trexima\EuropeanCvBundle\Form\Type\EuropeanCVDrivingLicenseType;

use function Symfony\Component\Translation\t;

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
                'label' => t('trexima_european_cv.form_label.driving_license_owner_label', [], 'trexima_european_cv'),
                'placeholder' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'no' => false,
                    'yes' => true
                ],
                'choice_label' => function ($choice, $key, $value) {
                    if (true === $choice) {
                        return t('trexima_european_cv.form_label.driving_license_owner_true_choice_label', [], 'trexima_european_cv');
                    }
                    if (false === $choice) {
                        return t('trexima_european_cv.form_label.driving_license_owner_false_choice_label', [], 'trexima_european_cv');
                    }
                    return strtoupper($key);
                },
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