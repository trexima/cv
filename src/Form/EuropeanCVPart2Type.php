<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Type\DrivingLicenseType;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;
use Trexima\EuropeanCvBundle\Form\Type\SubmitIconType;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Education
 */
class EuropeanCVPart2Type extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EuropeanCV|null $europeanCv */
        $europeanCv = $builder->getData();
        $now = new \DateTime();

        $builder
            ->add('educations', CollectionType::class, [
                'entry_type' => EuropeanCVEducationType::class,
                'entry_options' => [
                    'label' => false
                ],
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'required' => false,
                // 'attr' => [
                //     'data-parsley-trexima-european-cv-dynamic-collection-min' => $options['educations_min'],
                //     'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vyplňte aspoň %s vzdelanie'
                // ],
                // 'constraints' => [
                //     new Count([
                //         'min' => $options['educations_min'],
                //         'minMessage' => 'Vyplňte aspoň {{ limit }} vzdelanie'
                //     ])
                // ]
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