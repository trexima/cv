<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Form\Type\JQueryFileUploadType;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\SexEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\TitleAfterEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\TitleBeforeEnum;
use Trexima\EuropeanCvBundle\Form\Type\EuropeanCVPhoneType;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;

use function Symfony\Component\Translation\t;

/**
 * Basic user info
 */
class EuropeanCVPartBasicInfoType extends AbstractType
{

    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = new \DateTime();

        $builder
        ->add('sex', EnumType::class, [
            'class' => SexEnum::class,
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'choice_label' => fn(SexEnum $choice) => match ($choice) {
                SexEnum::MALE => t('trexima_european_cv.form_label.sex_male', [], 'trexima_european_cv'),
                SexEnum::FEMALE => t('trexima_european_cv.form_label.sex_female', [], 'trexima_european_cv'),
                SexEnum::DO_NOT_STATE => t('trexima_european_cv.form_label.sex_do_not_state', [], 'trexima_european_cv'),
            },
            'label' => t('trexima_european_cv.form_label.sex_label', [], 'trexima_european_cv'),
        ])
        ->add('photo', JQueryFileUploadType::class, [
            'required' => false,
            'label' => t('trexima_european_cv.form_label.photo_label', [], 'trexima_european_cv'),
            'upload_route' => $options['photo_upload_route'],
        ])
        ->add('firstName', TextType::class, [
            'label' => t('trexima_european_cv.form_label.first_name_label', [], 'trexima_european_cv'),
            'attr' => [
                'placeholder' => t('trexima_european_cv.form_label.first_name_placeholder', [], 'trexima_european_cv')
            ],
            'required' => false
        ])
        ->add('lastName', TextType::class, [
            'label' => t('trexima_european_cv.form_label.last_name_label', [], 'trexima_european_cv'),
            'attr' => [
                'placeholder' => t('trexima_european_cv.form_label.last_name_placeholder', [], 'trexima_european_cv')
            ],
            'required' => false
        ])
        ->add('titlesBefore', Select2Type::class, [
            'label' => t('trexima_european_cv.form_label.titles_before_label', [], 'trexima_european_cv'),
            'placeholder' => t('trexima_european_cv.form_label.titles_before_placeholder', [], 'trexima_european_cv'),
            'required' => false,
            'multiple' => true,
            'choices' => $this->getTitlesBeforeArray(),
            'attr' => [
                'class' => 'data-trexima-european-cv-bind-select2'
            ]
        ])
        ->add('titlesAfter', Select2Type::class, [
            'label' => t('trexima_european_cv.form_label.titles_after_label', [], 'trexima_european_cv'),
            'placeholder' => t('trexima_european_cv.form_label.titles_after_placeholder', [], 'trexima_european_cv'),
            'required' => false,
            'multiple' => true,
            'choices' => $this->getTitlesAfterArray(),
        ])
        ->add('nationalities', Select2Type::class, [
            'required' => false,
            'multiple' => true,
            'choices' => $this->getNationalitiesArray(),
            'label' => t('trexima_european_cv.form_label.nationalities_label', [], 'trexima_european_cv'),
        ])
        ->add('year', ChoiceType::class, [
            'required' => false,
            'choices' => range($now->format('Y'), 1900, -1),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => t('trexima_european_cv.form_label.year_label', [], 'trexima_european_cv'),
        ])
        ->add('month', ChoiceType::class, [
            'required' => false,
            'choices' => range(1,12),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => t('trexima_european_cv.form_label.month_label', [], 'trexima_european_cv'),
        ])
        ->add('day', ChoiceType::class, [
            'required' => false,
            'choices' => range(1,31),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => t('trexima_european_cv.form_label.day_label', [], 'trexima_european_cv'),
        ])
        ->add('email', null, [
            'required' => false,
            'label' => t('trexima_european_cv.form_label.email_label', [], 'trexima_european_cv'),
            'attr' => [
                'placeholder' => t('trexima_european_cv.form_label.email_placeholder', [], 'trexima_european_cv')
            ]
        ])
        ->add('phones', CollectionType::class, [
            'entry_type' => EuropeanCVPhoneType::class,
            'entry_options' => ['label' => false],
            'by_reference' => false,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
        ])
        ->add('address', null, [
            'required' => false,
            'label' => t('trexima_european_cv.form_label.address_label', [], 'trexima_european_cv'),
            'attr' => [
                'placeholder' => t('trexima_european_cv.form_label.address_placeholder', [], 'trexima_european_cv'),
            ]
        ])
        ->add('personalWebsites', CollectionType::class, [
            'label' => t('trexima_european_cv.form_label.personal_websites_label', [], 'trexima_european_cv'),
            'required' => false,
            'entry_type' => TextType::class,
            'entry_options' => [
                'label' => false
            ],
            'by_reference' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
        ])
        ->add('description', TextareaType::class, [
            'label' => t('trexima_european_cv.form_label.description_label', [], 'trexima_european_cv'),
            'required' => false,
            'attr' => [
                'placeholder' => t('trexima_european_cv.form_label.description_placeholder', [], 'trexima_european_cv')
            ]
        ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $submittedData = $event->getData();

            // Default scheme
            // if ($submittedData['personalWebsites']) {
            //     $personalWebsiteScheme = parse_url((string) $submittedData['personalWebsites'], PHP_URL_SCHEME);
            //     if (!in_array($personalWebsiteScheme, ['http', 'https'])) {
            //         $submittedData['personalWebsites'] = 'http://'.$submittedData['personalWebsites'];
            //     }
            // }

            $collectionsWithPosition = [
                'practices',
                'educations',
                'languages',
                'phones',
                'additionalInformations',
                'attachments'
            ];

            foreach ($collectionsWithPosition as $collectionName) {
                if (!array_key_exists($collectionName, $submittedData)) {
                    continue;
                }

                $i = 0;
                // Set position to ensure the entities stay in the submitted order
                foreach ($submittedData[$collectionName] as &$item) {
                    // Every sortable collection must contains position in entries
                    $item['position'] = $i;
                    $i++;
                }
            }

            $event->setData($submittedData);
        });
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

    private function getTitlesBeforeArray() {
        $titles = [];
        foreach (TitleBeforeEnum::cases() as $title) {
            $titles[$title->value] = $title->value;
        }

        return $titles;
    }

    private function getTitlesAfterArray() {
        $titles = [];
        foreach (TitleAfterEnum::cases() as $title) {
            $titles[$title->value] = $title->value;
        }

        return $titles;
    }

    private function getNationalitiesArray() {
        $nationalities = [];
        foreach (LanguageEnum::cases() as $language) {
            $nationalities[t('trexima_european_cv.form_label.nationality_' . $language->value, [], 'trexima_european_cv')->trans($this->translator)] = $language->value;
        }

        return $nationalities;
    }
}