<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVLanguage;

/**
 * Languages.
 */
class EuropeanCVPartLanguagesType extends AbstractType implements EventSubscriberInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('languages', CollectionType::class, [
                'custom_style' => true,
                'label' => false,
                'entry_type' => $options['languageTypeClass'],
                'entry_options' => [
                    'label' => false,
                    'data_class' => EuropeanCVLanguage::class,
                    'error_bubbling' => false,
                    'child_config' => [
                        'language' => [
                            'required' => false,
                        ],
                        'level' => [
                            'required' => false,
                            'allow_undefined_option' => true,
                        ],
                    ],
                ],
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => fn ($data) => null === $data?->getLanguage(),
                'error_bubbling' => false,
                'constraints' => [
                    new Valid(),
                ],
                'required' => false,
                'child_config' => [
                    'level' => [
                        'transform' => true,
                    ],
                ],
            ])
            ->addEventSubscriber($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCV::class,
            'languageTypeClass' => null,
        ]);

        $resolver->setRequired([
            'photo_upload_route',
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

        foreach ($data->getLanguages() as $language) {
            if (null === $language->getEuropeanCV()) {
                $language->setEuropeanCV($data);
            }
        }
    }
}
