<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataAccessor\CallbackAccessor;
use Symfony\Component\Form\Extension\Core\DataAccessor\ChainAccessor;
use Symfony\Component\Form\Extension\Core\DataAccessor\PropertyPathAccessor;
use Symfony\Component\Form\Extension\Core\DataMapper\DataMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\SexEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\TitleAfterEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\TitleBeforeEnum;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVAddress;
use Trexima\EuropeanCvBundle\Form\Type\EuropeanCVPhoneType;
use Trexima\EuropeanCvBundle\Form\Type\GooglePlaceAutocompleteType;

use function Symfony\Component\Translation\t;

/**
 * Basic user info
 */
class EuropeanCVPartBasicInfoType extends AbstractType
{
    private const MIME_TYPES = ['image/jpg', 'image/jpeg', 'image/png'];
    private const MAX_PHOTO_IMAGE_SIZE = 3; // In MB

    private readonly DataMapper $dataMapper;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
        $this->dataMapper = new DataMapper(
            new ChainAccessor([
                new CallbackAccessor(),
                new PropertyPathAccessor($propertyAccessor ?? PropertyAccess::createPropertyAccessor()),
            ])
        );
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
            'required' => false,
            'expanded' => true,
            'multiple' => false,
            'choice_label' => fn(SexEnum $choice) => match ($choice) {
                SexEnum::MALE => t('trexima_european_cv.form_label.sex_male', [], 'trexima_european_cv'),
                SexEnum::FEMALE => t('trexima_european_cv.form_label.sex_female', [], 'trexima_european_cv'),
                SexEnum::DO_NOT_STATE => t('trexima_european_cv.form_label.sex_do_not_state', [], 'trexima_european_cv'),
            },
            'label' => t('trexima_european_cv.form_label.sex_label', [], 'trexima_european_cv'),
            'placeholder' => false,
        ])
        ->add('photo', FileType::class, [
            'required' => false,
            'label' => false,
            'mapped' => false,
            'attr' => [
                'accept' => implode(',', self::MIME_TYPES) . ',.jpg,.jpeg,.png', 
                'data-ui--cropper-target'=>'input',
                'class' => 'opacity-0 w-100 h-100 position-absolute top-0 start-0 cursor-pointer z-index-1'
            ],
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
        ->add('titlesBefore', EnumType::class, array_merge([
            'class' => TitleBeforeEnum::class,
            'label' => t('trexima_european_cv.form_label.titles_before_label', [], 'trexima_european_cv'),
            'required' => false,
            'multiple' => true,
            'transform' => true,
        ], ($options['field_options']['titlesBefore'] ?? [])))
        ->add('titlesAfter', EnumType::class, [
            'class' => TitleAfterEnum::class,
            'label' => t('trexima_european_cv.form_label.titles_after_label', [], 'trexima_european_cv'),
            'transform' => true,
            'required' => false,
            'multiple' => true,
            'form_floating' => true,
            'select2'=> true,
            'select2_tags'=> true,
            'select2_theme' => 'worki-floating',
            'select2_placeholder' => t('trexima_european_cv.form_label.titles_before_placeholder', [], 'trexima_european_cv'),
            'select2_allow_clear' => true,
        ])
        ->add('nationalities', EnumType::class, [
            'class' => LanguageEnum::class,
            'label' => t('trexima_european_cv.form_label.nationalities_label', [], 'trexima_european_cv'),
            'choice_label' => function(LanguageEnum $choice) {
                return t('trexima_european_cv.form_label.nationality_' . $choice->value, [], 'trexima_european_cv')->trans($this->translator);
            },
            'transform' => true,
            'required' => false,
            'multiple' => true,
            'form_floating' => true,
            'select2'=> true,
            'select2_theme' => 'worki-floating',
            'select2_placeholder' => t('trexima_european_cv.form_label.titles_before_placeholder', [], 'trexima_european_cv'),
            'select2_allow_clear' => true,
        ])
        ->add('year', ChoiceType::class, [
            'required' => false,
            'choices' => range($now->format('Y'), 1922, -1),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => t('trexima_european_cv.form_label.year_label', [], 'trexima_european_cv'),
            'form_floating' => true,
            'select2'=> true,
            'select2_theme' => 'worki-floating',
            'select2_placeholder' => t('trexima_european_cv.form_label.year_placeholder', [], 'trexima_european_cv'),
        ])
        ->add('month', ChoiceType::class, [
            'required' => false,
            'choices' => range(1,12),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => t('trexima_european_cv.form_label.month_label', [], 'trexima_european_cv'),
            'form_floating' => true,
            'select2'=> true,
            'select2_theme' => 'worki-floating',
            'select2_placeholder' => t('trexima_european_cv.form_label.month_placeholder', [], 'trexima_european_cv'),
        ])
        ->add('day', ChoiceType::class, [
            'required' => false,
            'choices' => range(1,31),
            'choice_label' => function ($choice) {
                return $choice;
            },
            'label' => t('trexima_european_cv.form_label.day_label', [], 'trexima_european_cv'),
            'form_floating' => true,
            'select2'=> true,
            'select2_theme' => 'worki-floating',
            'select2_placeholder' => t('trexima_european_cv.form_label.day_placeholder', [], 'trexima_european_cv'),
        ])
        ->add('email', TextType::class, [
            'required' => true,
            'form_floating' => true,
            'label' => t('trexima_european_cv.form_label.email_label', [], 'trexima_european_cv'),
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
        ->add('address', GooglePlaceAutocompleteType::class, [
            'label' => t('cv.label.addresses'),
            'multiple' => false,
            'by_reference' => false,
            'form_floating' => true,
            'class' => EuropeanCVAddress::class,
            'select2' => true,
            'select2_theme' => 'worki-floating',
            'select2_placeholder' => t('job.label.write_an_address'),
            'select2_allow_clear' => true,
            'select2_autocomplete_url' => $this->urlGenerator->generate(
                'app_autocomplete_google_places',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'select2_minimum_input_length' => 1,
            'select2_filter_selected_results' => true,
            'select2_no_results_message' => t('app.general.places_autocomplete_no_results'),
        ])
        ->add('personalWebsites', CollectionType::class, [
            'label' => false,
            'required' => false,
            'entry_type' => TextType::class,
            'entry_options' => [
                'label' => t('trexima_european_cv.form_label.personal_websites_label', [], 'trexima_european_cv'),
                'help' => '<div><i class="fal fa-info-square me-2"></i>Môžete zadať viac odkazov. Zadajte napríklad: www.facebook.com/pavol.vzor.10</div>',
                'help_html' => true,
                'attr' => [
                    'data-form--job-target' => 'nameInput',
                    'data-ui--input-character-count-target' => 'input',
                    'data-controller' => 'ui--form-clear-feedback',
                    'class' => 'mt-3'
                ],
                'form_floating' => true,
            ],
            'by_reference' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            // 'form_floating' => true,
        ])
        ->add('description', TextareaType::class, [
            'label' => t('trexima_european_cv.form_label.description_label', [], 'trexima_european_cv'),
            'required' => false,
            'attr' => [
                'placeholder' => t('trexima_european_cv.form_label.description_placeholder', [], 'trexima_european_cv')
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
        $resolver->setDefaults([
            'data_class' => EuropeanCV::class,
            'field_options' => []
         ]);

        $resolver->setRequired([
            'photo_upload_route'
        ]);
    }
}