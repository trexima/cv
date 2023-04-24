<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\DataAccessor\CallbackAccessor;
use Symfony\Component\Form\Extension\Core\DataAccessor\ChainAccessor;
use Symfony\Component\Form\Extension\Core\DataAccessor\PropertyPathAccessor;
use Symfony\Component\Form\Extension\Core\DataMapper\DataMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\SexEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\TitleAfterEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\TitleBeforeEnum;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVAddress;
use Trexima\EuropeanCvBundle\Form\Model\Photo;
use Trexima\EuropeanCvBundle\Form\Type\EuropeanCVPhoneType;
use Trexima\EuropeanCvBundle\Form\Type\GooglePlaceAutocompleteType;
use Trexima\EuropeanCvBundle\Form\Type\PhotoType;

use function Symfony\Component\Translation\t;

/**
 * Basic user info
 */
class EuropeanCVPartBasicInfoType extends AbstractType implements DataMapperInterface
{
    private readonly DataMapper $dataMapper;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly string $uploadUrl,
    ) {
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
        ->setDataMapper($this)
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
        ->add('photo', PhotoType::class, array_merge([
            'required' => false,
            'mapped' => false,
            'label' => false,
            'max_size' => 16 << 20,
            'max_size_message' => t(
                'job.max_file_size',
                ['limit' => 16, 'suffix' => 'MB'],
                'validators',
            ),
        ], ($options['field_options']['photo'] ?? [])))
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
        ->add('address', GooglePlaceAutocompleteType::class, array_merge([
            'label' => t('trexima_european_cv.form_label.address_label', [], 'trexima_european_cv'),
            'multiple' => false,
            'by_reference' => false,
            'form_floating' => true,
            'class' => EuropeanCVAddress::class,
        ], ($options['field_options']['address'] ?? [])))
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

    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        $this->dataMapper->mapFormsToData($forms, $viewData);
    }

    /**
     * @param EuropeanCV|null $viewData
     * @param \Traversable $forms
     */
    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        $this->dataMapper->mapDataToForms($viewData, $forms);

        $this->mapPhoto($viewData, $forms);
    }

    private function mapPhoto(EuropeanCV|null $viewData, \Traversable $forms): void
    {
        $forms = \iterator_to_array($forms);
        if (!isset($forms['photo'])) {
            return;
        }

        $photo = $viewData?->getPhoto();
        if (null === $photo) {
            $forms['photo']->setData(null);
            return;
        }

        $url = $this->uploadUrl . '/european-cv/images/' . $photo;

        $photoType = (new Photo())
            ->setExistingFileId('123')
            ->setExistingFileUrl($url)
            ->setFile(null);

        $forms['photo']->setData($photoType);
    }
}