<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Trexima\EuropeanCvBundle\Entity\Enum\LanguageEnum;
use Trexima\EuropeanCvBundle\Entity\Enum\StyleEnum;

use function Symfony\Component\Translation\t;

/**
 * Language and tamplate selection
 */
class EuropeanCVPartCVTemplateType extends AbstractType implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('language', EnumType::class, [
            'class' => LanguageEnum::class,
            'required' => true,
            'choices' => [
                LanguageEnum::LANGUAGE_SK,
                LanguageEnum::LANGUAGE_CS,
                LanguageEnum::LANGUAGE_EN,
                LanguageEnum::LANGUAGE_DE,
                LanguageEnum::LANGUAGE_HU,
            ],
            'choice_label' => fn(LanguageEnum $choice) => match ($choice) {
                LanguageEnum::LANGUAGE_SK => t('trexima_european_cv.form_label.language_sk', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_CS => t('trexima_european_cv.form_label.language_cs', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_EN => t('trexima_european_cv.form_label.language_en', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_DE => t('trexima_european_cv.form_label.language_de', [], 'trexima_european_cv'),
                LanguageEnum::LANGUAGE_HU => t('trexima_european_cv.form_label.language_hu', [], 'trexima_european_cv'),
            },
            'label' => t('trexima_european_cv.form_label.language', [], 'trexima_european_cv'),
            'placeholder' => false,
            'multiple' => false,
            'form_floating' => true,
            'select2'=> true,
            'select2_theme' => 'worki-floating',
        ])
        ->add('style', EnumType::class, [
            'class' => StyleEnum::class,
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'choice_label' => fn(StyleEnum $choice) => match ($choice) {
                default => $choice->value
            },
        ])
        ->add('template', HiddenType::class, [
            'required' => false,
            'mapped' => false,
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
         ]);

        $resolver->setRequired([
            'photo_upload_route'
        ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'onPostSet',
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function onPostSet(FormEvent $formEvent)
    {
        $form = $formEvent->getForm();
        $data = $formEvent->getData();
        $form->get('template')->setData($data->getStyle()->value);
    }

    public function onSubmit(FormEvent $formEvent)
    {
        $form = $formEvent->getForm();
        $data = $form->getData();
        $data->setStyle(StyleEnum::tryFrom($form->get('template')->getData() ?: StyleEnum::STYLE_01));
    }
}