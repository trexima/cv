<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Type\SubmitIconType;

class EuropeanCVType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('part1', EuropeanCVPart1Type::class);
        $builder->add('part2', EuropeanCVPart2Type::class);

        if ($options['is_user_logged_in']) {
            $builder->add('submit', SubmitIconType::class, [
                'label' => 'Uložiť životopis',
                'icon_left' => '<i class="far fa-save"></i>',
                'attr' => [
                    'class' => 'btn btn-block btn-primary'
                ]
            ]);
        }
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