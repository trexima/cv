<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;

use function Symfony\Component\Translation\t;

/**
 * AdditionalInformations.
 */
class EuropeanCVPartAdditionalInformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('additionalInformations', TextareaType::class, [
                'label' => t('trexima_european_cv.form_label.additional_informations', [], 'trexima_european_cv'),
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_placeholder.additional_informations', [], 'trexima_european_cv'),
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
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
        ]);

        $resolver->setRequired([
            'photo_upload_route',
        ]);
    }
}
