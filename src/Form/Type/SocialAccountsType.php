<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Trexima\EuropeanCvBundle\Validator as AppAssert;

use function Symfony\Component\Translation\t;

class SocialAccountsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('facebook', UrlType::class, [
                'form_floating' => true,
                'required' => false,
                'label' => t('trexima_european_cv.form_label.facebook_label', [], 'trexima_european_cv'),
                'constraints' => [
                    new AppAssert\WebUrl(
                        pattern: '/^https?:\/\/(www\.)?facebook\.com\/[^\/]+(\/.*)?/',
                        message: 'trexima_european_cv.url_is_not_valid',
                    ),
                ],
            ])
            ->add('instagram', UrlType::class, [
                'form_floating' => true,
                'required' => false,
                'label' => t('trexima_european_cv.form_label.instagram_label', [], 'trexima_european_cv'),
                'constraints' => [
                    new AppAssert\WebUrl(
                        pattern: '/^https?:\/\/(www\.)?instagram\.com\/[^\/]+(\/.*)?/',
                        message: 'trexima_european_cv.url_is_not_valid',
                    ),
                ],
            ])
            ->add('linkedIn', UrlType::class, [
                'form_floating' => true,
                'required' => false,
                'label' => t('trexima_european_cv.form_label.linkedin_label', [], 'trexima_european_cv'),
                'constraints' => [
                    new AppAssert\WebUrl(
                        pattern: '/^https?:\/\/(www\.)?linkedin\.com\/[^\/]+(\/.*)?/',
                        message: 'trexima_european_cv.url_is_not_valid',
                    ),
                ],
            ])
            ->addEventListener(FormEvents::SUBMIT, $this->onSubmit(...));
    }

    public function onSubmit(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();

        $keys = ['facebook', 'instagram', 'linkedIn'];
        foreach ($keys as $key) {
            if (\array_key_exists($key, $data) && !$data[$key]) {
                unset($data[$key]);
            }
        }

        $formEvent->setData($data);
    }
}
