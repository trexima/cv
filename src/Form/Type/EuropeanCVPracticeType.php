<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPractice;
use Trexima\EuropeanCvBundle\Facade\Harvey;
use Trexima\EuropeanCvBundle\Form\Type\MonthYearRangeType;

use function Symfony\Component\Translation\t;

class EuropeanCVPracticeType extends AbstractType implements EventSubscriberInterface
{
    public function __construct(
        private readonly Harvey $harvey,
        private readonly TranslatorInterface $translator
    )
    {
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $harvey = $this->harvey;
        $builder
            ->add('iscoCode', Select2Type::class, [
                'label' => t('trexima_european_cv.form_label.practice_isco_code_label', [], 'trexima_european_cv'),
                'required' => false,
                'by_reference' => false,
                'multiple' => false,
                'help' => t('trexima_european_cv.form_label.practice_isco_code_help', [], 'trexima_european_cv')->trans($this->translator) . ' <a target="_blank" href="https://www.hisco.sk/stromova-struktura">hisco.sk</a>',
                'help_html' => true,
                'placeholder' => t('trexima_european_cv.form_label.practice_isco_code_placeholder', [], 'trexima_european_cv'),
                'ajax_route_path' => '/trexima-european-cv-bundle-harvey/isco-autocomplete',
                'choice_label' => function (string $code) use ($harvey) {
                    $isco = $harvey->getClient()->getIsco($code);
                    return sprintf('%s %s', $isco['code'], $isco['title']);
                },
                'attr' => [
                    'class' => 'data-trexima-european-cv-bind-select2'
                ],
                'choice_loader' => new Select2ChoiceLoader()
            ])
            ->add('employee', TextType::class, [
                'label' => t('trexima_european_cv.form_label.practice_employee_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.practice_employee_placeholder', [], 'trexima_european_cv'),
                ]
            ])
            ->add('dateRange', MonthYearRangeType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => t('trexima_european_cv.form_label.practice_description_label', [], 'trexima_european_cv'),
                'required' => false,
                'attr' => [
                    'placeholder' => t('trexima_european_cv.form_label.practice_description_placeholder', [], 'trexima_european_cv')
                ]
            ])
            ->addEventSubscriber($this);
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCVPractice::class
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
        $data = $formEvent->getData();
        if ($data->getIscoCode() !== null) {
            $isco = $this->harvey->getClient()->getIsco($data->getIscoCode());
            if (!empty($isco)) {
                $data->setTitle($isco['title']);
            }
        }
    }
}