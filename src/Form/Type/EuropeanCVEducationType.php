<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVEducation;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\EducationTypeEnum;
use Trexima\EuropeanCvBundle\Form\Type\YearRangeType;

use function Symfony\Component\Translation\t;

class EuropeanCVEducationType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => t('trexima_european_cv.form_label.education_title_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.education_title_placeholder', [], 'trexima_european_cv')
                ]
            ])
            ->add('type', EnumType::class, [
                'class' => EducationTypeEnum::class,
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'choice_label' => fn(EducationTypeEnum $choice) => match ($choice) {
                    EducationTypeEnum::EDUCATION_ELEMENTARY_SCHOOL => t('trexima_european_cv.form_label.education_elementary_school', [], 'trexima_european_cv'),
                    EducationTypeEnum::EDUCATION_HIGH_SCHOOL => t('trexima_european_cv.form_label.education_high_school', [], 'trexima_european_cv'),
                    EducationTypeEnum::EDUCATION_UNIVERSITY => t('trexima_european_cv.form_label.education_university', [], 'trexima_european_cv'),
                    EducationTypeEnum::EDUCATION_CERTIFICATE => t('trexima_european_cv.form_label.education_certificate', [], 'trexima_european_cv'),
                },
            ])
            ->add('yearRange', YearRangeType::class, [
                'required' => false,
                'label' => t('trexima_european_cv.form_label.education_year_range_label', [], 'trexima_european_cv')
            ])
            
            ->add('description', TextareaType::class, [
                'label' => t('trexima_european_cv.form_label.education_description_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.education_description_placeholder', [], 'trexima_european_cv')
                ]
            ])
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(['data_class' => EuropeanCVEducation::class]);
    }
}