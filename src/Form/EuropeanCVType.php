<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartAdditionalInformationsType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartBasicInfoType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartCompetencesType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartCVTemplateType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartDigitalSkillsType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartDrivingLicenseType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartEducationType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartHobbiesType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartLanguagesType;
use Trexima\EuropeanCvBundle\Form\Parts\EuropeanCVPartPracticesType;
use Trexima\EuropeanCvBundle\Form\Type\SubmitIconType;

use function Symfony\Component\Translation\t;

class EuropeanCVType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('template', EuropeanCVPartCVTemplateType::class);
        $builder->add('basicInfo', EuropeanCVPartBasicInfoType::class);
        $builder->add('education', EuropeanCVPartEducationType::class);
        $builder->add('practices', EuropeanCVPartPracticesType::class);
        $builder->add('languages', EuropeanCVPartLanguagesType::class);
        $builder->add('digitalSkills', EuropeanCVPartDigitalSkillsType::class);
        $builder->add('competences', EuropeanCVPartCompetencesType::class);
        $builder->add('drivingLicense', EuropeanCVPartDrivingLicenseType::class);
        $builder->add('hobbies', EuropeanCVPartHobbiesType::class);
        $builder->add('additionalInformations', EuropeanCVPartAdditionalInformationsType::class);

        if ($options['is_user_logged_in']) {
            $builder->add('submit', SubmitIconType::class, [
                'label' => t('trexima_european_cv.form_label.submit_label', [], 'trexima_european_cv'),
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