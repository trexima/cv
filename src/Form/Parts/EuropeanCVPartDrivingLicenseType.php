<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        $entity = $builder->getData();
        $drivingLicenses = [];
        foreach($entity->getDrivingLicenses() as $drivingLicense) {
            $drivingLicenses[] = $drivingLicense->getDrivingLicense()->value;
        }
        $builder
            ->add('drivingLicenseOwner', CheckboxType::class, [
                'required' => false,
                'label' => t('trexima_european_cv.form_label.driving_license_owner_label', [], 'trexima_european_cv'),
                'toggle' => [
                    [
                        'key' => '1',
                        'value' => [
                            '#driving_license_europeanCV_drivingLicenses',
                        ]
                    ]
                ]
            ])
            ->add('drivingLicenses', DrivingLicenseType::class, [
                'entry_type' => EuropeanCVDrivingLicenseType::class,
                'label' => false,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'd-flex my-2 align-items-center gap-4'
                    ]
                ],
                'required' => false,
                'hidden' => empty($entity?->getDrivingLicenseOwner()),
                'existing_licenses' => $drivingLicenses,
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
         ]);

        $resolver->setRequired([
            'photo_upload_route'
        ]);
    }
}