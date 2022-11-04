<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Type\JQueryFileUploadType;
use Symfony\Component\Validator\Constraints\Count;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\SexEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\TitleAfterEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\TitleBeforeEnum;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;

use function Symfony\Component\Translation\t;

/**
 * Basic user info
 */
class EuropeanCVPart1Type extends AbstractType
{

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
            //TODO
            'required' => false,
            'label' => 'Fotografia',
            'upload_route' => $options['photo_upload_route'],
        ])
        // todo add title before and after

        ->add('firstName', TextType::class, [
            'label' => 'Meno',
            'attr' => [
                'placeholder' => 'Ing. Pavol Vzor'
            ]
        ])
        ->add('lastName', TextType::class, [
            'label' => 'Priezvisko',
            'attr' => [
                'placeholder' => 'Ing. Pavol Vzor'
            ]
        ])
        ->add('titlesBefore', Select2Type::class, [
            'label' => 'Tituly pred',
            'placeholder' => 'Prosím, vyberte možnosť',
            'required' => false,
            'multiple' => true,
            'choices' => $this->getTitlesBeforeArray(),
        ])
        ->add('titlesAfter', Select2Type::class, [
            'label' => 'Tituly po',
            'placeholder' => 'Prosím, vyberte možnosť',
            'required' => false,
            'multiple' => true,
            'choices' => $this->getTitlesAfterArray(),
        ])
        // ->add('nationality', Select2Type::class, [
        //     'label' => 'Štátna príslušnosť',
        //     'placeholder' => 'Prosím, vyberte možnosť',
        //     'multiple' => true,
        //     'choices' => $this->getNationalityArray(),
        // ])
        ->add('nationalities', Select2Type::class, [
            // 'placeholder' => t('trexima_european_cv.form_placeholder.language', [], 'trexima_european_cv'),
            'required' => true,
            'multiple' => true,
            'choices' => [
                LanguageEnum::LANGUAGE_SK,
                LanguageEnum::LANGUAGE_CS,
                LanguageEnum::LANGUAGE_EN,
                LanguageEnum::LANGUAGE_DE,
                LanguageEnum::LANGUAGE_HU,
                LanguageEnum::LANGUAGE_RU,
                LanguageEnum::LANGUAGE_UK,
                LanguageEnum::LANGUAGE_PL,
                LanguageEnum::LANGUAGE_FR,
                LanguageEnum::LANGUAGE_IT,
                LanguageEnum::LANGUAGE_ES,
                LanguageEnum::LANGUAGE_PT,
                LanguageEnum::LANGUAGE_NL,
                LanguageEnum::LANGUAGE_DA,
                LanguageEnum::LANGUAGE_FI,
                LanguageEnum::LANGUAGE_SV,
                LanguageEnum::LANGUAGE_NO,
                LanguageEnum::LANGUAGE_EL,
                LanguageEnum::LANGUAGE_IS,
                LanguageEnum::LANGUAGE_GA,
                LanguageEnum::LANGUAGE_LV,
                LanguageEnum::LANGUAGE_ET,
                LanguageEnum::LANGUAGE_LT,
                LanguageEnum::LANGUAGE_SL,
                LanguageEnum::LANGUAGE_MT,
                LanguageEnum::LANGUAGE_RO,
                LanguageEnum::LANGUAGE_BG,
                LanguageEnum::LANGUAGE_HR,
                LanguageEnum::LANGUAGE_SR,
                LanguageEnum::LANGUAGE_LA,
                LanguageEnum::LANGUAGE_ZH,
                LanguageEnum::LANGUAGE_VI,
                LanguageEnum::LANGUAGE_JA,
                LanguageEnum::LANGUAGE_KO,
                LanguageEnum::LANGUAGE_OTHER,
            ],
            'choice_label' => fn(LanguageEnum $choice) => match ($choice) {
                LanguageEnum::LANGUAGE_SK => t('trexima_european_cv.form_label.nationality_sk', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_CS => t('trexima_european_cv.form_label.nationality_cs', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_EN => t('trexima_european_cv.form_label.nationality_en', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_DE => t('trexima_european_cv.form_label.nationality_de', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_HU => t('trexima_european_cv.form_label.nationality_hu', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_RU => t('trexima_european_cv.form_label.nationality_ru', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_UK => t('trexima_european_cv.form_label.nationality_uk', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_PL => t('trexima_european_cv.form_label.nationality_pl', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_FR => t('trexima_european_cv.form_label.nationality_fr', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_IT => t('trexima_european_cv.form_label.nationality_it', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_ES => t('trexima_european_cv.form_label.nationality_es', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_PT => t('trexima_european_cv.form_label.nationality_pt', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_NL => t('trexima_european_cv.form_label.nationality_nl', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_DA => t('trexima_european_cv.form_label.nationality_da', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_FI => t('trexima_european_cv.form_label.nationality_fi', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_SV => t('trexima_european_cv.form_label.nationality_sv', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_NO => t('trexima_european_cv.form_label.nationality_no', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_EL => t('trexima_european_cv.form_label.nationality_el', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_IS => t('trexima_european_cv.form_label.nationality_is', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_GA => t('trexima_european_cv.form_label.nationality_ga', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_LV => t('trexima_european_cv.form_label.nationality_lv', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_ET => t('trexima_european_cv.form_label.nationality_et', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_LT => t('trexima_european_cv.form_label.nationality_lt', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_SL => t('trexima_european_cv.form_label.nationality_sl', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_MT => t('trexima_european_cv.form_label.nationality_mt', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_RO => t('trexima_european_cv.form_label.nationality_ro', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_BG => t('trexima_european_cv.form_label.nationality_bg', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_HR => t('trexima_european_cv.form_label.nationality_hr', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_SR => t('trexima_european_cv.form_label.nationality_sr', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_LA => t('trexima_european_cv.form_label.nationality_la', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_ZH => t('trexima_european_cv.form_label.nationality_zh', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_VI => t('trexima_european_cv.form_label.nationality_vi', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_JA => t('trexima_european_cv.form_label.nationality_ja', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_KO => t('trexima_european_cv.form_label.nationality_ko', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_OTHER => t('trexima_european_cv.form_label.nationality_other', [], 'trexima_european_cv'),
            },
            'label' => 'Štátna príslušnosť',
            // 'attr' => [
            //     'data-trexima-european-cv-bind-select2' => true,
            // ],
        ])
        ->add('year', ChoiceType::class, [
            'choices' => range($now->format('Y'), 1900, -1),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => 'Rok narodenia',
        ])
        ->add('month', ChoiceType::class, [
            'choices' => range(1,12),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => 'Mesiac narodenia',
        ])
        ->add('day', ChoiceType::class, [
            'choices' => range(1,31),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => 'Deň narodenia',
        ])
        ->add('email', null, [
            'label' => 'E-mail',
            'attr' => [
                'placeholder' => 'vzor@vzor.sk'
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
            'attr' => [
                'data-parsley-trexima-european-cv-dynamic-collection-min' => $options['phones_min'],
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vyplňte aspoň %s telefónne čislo'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['phones_min'],
                    'minMessage' => 'Vyplňte aspoň {{ limit }} telefónne čislo'
                ])
            ]
        ])
        ->add('address', null, [
            'label' => 'Adresa',
            'attr' => [
                'placeholder' => 'Vzorová 3, 841 01 Bratislava IV, Slovenská republika'
            ]
        ])
        ->add('personalWebsites', CollectionType::class, [
            'label' => 'Osobná webová stránka',
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
            'label' => 'Stručný text o vás, ktorý sa zobrazí v hlavičke životopisu',
            'attr' => [
                'placeholder' => 'O vás'
            ]
        ])
        ;

        // if ($options['is_user_logged_in']) {
        //     $builder->add('submit', SubmitIconType::class, array(
        //         'label' => 'Uložiť životopis',
        //         'icon_left' => '<i class="far fa-save"></i>',
        //         'attr' => array(
        //             'class' => 'btn btn-block btn-primary'
        //         )
        //     ));
        // }

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
}