<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\StyleEnum;

use function Symfony\Component\Translation\t;

/**
 * Language and tamplate selection
 */
class EuropeanCVPartCVTemplateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('language', ChoiceType::class, [
            // 'placeholder' => t('trexima_european_cv.form_placeholder.language', [], 'trexima_european_cv'),
            'required' => true,
            'choices' => [
                LanguageEnum::LANGUAGE_SK,
                LanguageEnum::LANGUAGE_CS,
                LanguageEnum::LANGUAGE_EN,
                LanguageEnum::LANGUAGE_DE,
                LanguageEnum::LANGUAGE_HU,
            ],
            'choice_label' => fn(LanguageEnum $choice) => match ($choice) {
                LanguageEnum::LANGUAGE_SK => t('trexima_european_cv.form_label.language_sk', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_CS => t('trexima_european_cv.form_label.language_cs', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_EN => t('trexima_european_cv.form_label.language_en', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_DE => t('trexima_european_cv.form_label.language_de', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_HU => t('trexima_european_cv.form_label.language_hu', [], 'trexima_european_cv'),
            },
            'label' => t('trexima_european_cv.form_label.language', [], 'trexima_european_cv'),
        ])
        ->add('style', EnumType::class, [
            'class' => StyleEnum::class,
            // 'placeholder' => t('trexima_european_cv.form_placeholder.style', [], 'trexima_european_cv'),
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'choice_label' => fn(StyleEnum $choice) => match ($choice) {
                default => $choice->value
            },
            // 'label' => t('trexima_european_cv.form_label.style', [], 'trexima_european_cv'),
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