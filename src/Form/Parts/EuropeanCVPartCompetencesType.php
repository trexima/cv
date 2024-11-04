<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\CompetenceEnum;

use function Symfony\Component\Translation\t;

/**
 * Competences
 */
class EuropeanCVPartCompetencesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competences', EnumType::class, [
                'class' => CompetenceEnum::class,
                'label' => t('trexima_european_cv.form_label.competences_label', [], 'trexima_european_cv'),
                'required' => false,
                'multiple' => true,
                'form_floating' => true,
                'transform' => true,
                'select2' => true,
                'select2_tags' => true,
                'select2_theme' => 'worki-floating',
                'select2_placeholder' => t('trexima_european_cv.form_label.competences_placeholder', [], 'trexima_european_cv'),
                'select2_allow_clear' => true,
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
