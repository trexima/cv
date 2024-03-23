<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
                        'class' => 'row row-cols-1 row-cols-sm-auto gx-4 gy-1 mb-3 align-items-center'
                    ]
                ],
                'required' => false,
            ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->children['drivingLicenses']->vars['hidden'] = !$form->getData()?->getDrivingLicenseOwner();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
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
