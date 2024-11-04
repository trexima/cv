<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVLanguage;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageLevelEnum;

use function Symfony\Component\Translation\t;

class EuropeanCVLanguageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', EnumType::class, [
                'class' => LanguageEnum::class,
                'label' => t('trexima_european_cv.form_label.language_label', [], 'trexima_european_cv'),
                'placeholder' => t('trexima_european_cv.form_label.language_placeholder', [], 'trexima_european_cv'),
                'required' => false,
                'choice_label' => fn (LanguageEnum $choice) => match ($choice) {
                    default => t('trexima_european_cv.form_label.language_' . $choice->value, [], 'trexima_european_cv'),
                },
                'preferred_choices' => fn ($value, $key) => in_array($value, ['en', 'fr', 'de', 'ru', 'es', 'it'])
            ])
            ->add('level', EnumType::class, [
                'class' => LanguageLevelEnum::class,
                'required' => true,
                'label' => t('trexima_european_cv.form_label.language_level_label', [], 'trexima_european_cv'),
                'multiple' => false,
                'choice_label' => fn (LanguageLevelEnum $choice) => match ($choice) {
                    default => t('trexima_european_cv.form_label.language_level_' . strtolower($choice->value), [], 'trexima_european_cv'),
                },
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
            'data_class' => EuropeanCVLanguage::class
        ]);
    }
}
