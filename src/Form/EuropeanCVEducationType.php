<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVEducation;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\EducationTypeEnum;
use Trexima\EuropeanCvBundle\Form\Type\AtomicDateRangeType;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;
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
                'label' => 'Názov školy',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Názov školy'
                ]
            ])
            ->add('type', EnumType::class, [
                'class' => EducationTypeEnum::class,
                // 'placeholder' => t('trexima_european_cv.form_placeholder.style', [], 'trexima_european_cv'),
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'choice_label' => fn(EducationTypeEnum $choice) => match ($choice) {
                    EducationTypeEnum::EDUCATION_ELEMENTARY_SCHOOL => t('trexima_european_cv.form_label.education_elementary_school', [], 'trexima_european_cv'),
                    EducationTypeEnum::EDUCATION_HIGH_SCHOOL => t('trexima_european_cv.form_label.education_high_school', [], 'trexima_european_cv'),
                    EducationTypeEnum::EDUCATION_UNIVERSITY => t('trexima_european_cv.form_label.education_university', [], 'trexima_european_cv'),
                },
                // 'label' => t('trexima_european_cv.form_label.style', [], 'trexima_european_cv'),
            ])
            ->add('yearRange', YearRangeType::class, [
                'required' => false,
                'label' => 'Rozsah',
            ])
            
            ->add('description', TextareaType::class, [
                'label' => 'Opis Štúdia',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Opíšte vaše štúdium'
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