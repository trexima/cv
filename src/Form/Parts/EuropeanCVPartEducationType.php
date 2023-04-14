<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Enum\EducationTypeEnum;
use Trexima\EuropeanCvBundle\Form\Type\EuropeanCVEducationType;

use function Symfony\Component\Translation\t;

/**
 * Education
 */
class EuropeanCVPartEducationType extends AbstractType implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addElementarySchool', EuropeanCVEducationType::class, [
                'education_type' => EducationTypeEnum::EDUCATION_ELEMENTARY_SCHOOL,
                'by_reference' => false,
                'label' => false,
                'required' => false,
                'mapped' => false,
            ])
            ->add('saveElementarySchool', SubmitType::class, [
                'label' => t('trexima_european_cv.form_label.save_education_button', [], 'trexima_european_cv'),
                'attr' => [
                    'class' => 'btn-lg-lg btn-primary text-wrap'
                ]
            ])
            ->add('addHighSchool', EuropeanCVEducationType::class, [
                'education_type' => EducationTypeEnum::EDUCATION_HIGH_SCHOOL,
                'by_reference' => false,
                'label' => false,
                'required' => false,
                'mapped' => false,
            ])
            ->add('saveHighSchool', SubmitType::class, [
                'label' => t('trexima_european_cv.form_label.save_education_button', [], 'trexima_european_cv'),
                'attr' => [
                    'class' => 'btn-lg-lg btn-primary text-wrap'
                ]
            ])
            ->add('addUniversity', EuropeanCVEducationType::class, [
                'education_type' => EducationTypeEnum::EDUCATION_UNIVERSITY,
                'by_reference' => false,
                'label' => false,
                'required' => false,
                'mapped' => false,
            ])
            ->add('saveUniversity', SubmitType::class, [
                'label' => t('trexima_european_cv.form_label.save_education_button', [], 'trexima_european_cv'),
                'attr' => [
                    'class' => 'btn-lg-lg btn-primary text-wrap'
                ]
            ])
            ->addEventSubscriber($this)
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

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPostSubmit(FormEvent $formEvent): void
    {
        /** @var EuropeanCV $data */
        $data = $formEvent->getData();

        $education = $formEvent->getForm()->get('addElementarySchool')->getData();
        if ($education->getTitle()) {
            $data->addEducation($education);
        }

        $education = $formEvent->getForm()->get('addHighSchool')->getData();
        if ($education->getTitle()) {
            $data->addEducation($education);
        }
    
        $education = $formEvent->getForm()->get('addUniversity')->getData();
        if ($education->getTitle()) {
            $data->addEducation($education);
        }
    }
}