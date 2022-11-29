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
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPractice;
use Trexima\EuropeanCvBundle\Facade\Harvey;
use Trexima\EuropeanCvBundle\Form\Type\MonthYearRangeType;

class EuropeanCVPracticeType extends AbstractType implements EventSubscriberInterface
{
    public function __construct(private readonly Harvey $harvey)
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
                'label' => 'Zamestnanie',
                'required' => false,
                'by_reference' => false,
                'multiple' => false,
                'help' => 'Viac info: <a target="_blank" href="https://www.hisco.sk/stromova-struktura">hisco.sk</a>',
                'help_html' => true,
                'placeholder' => 'Prosím, vyberte možnosť',
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
                'label' => 'Zamestnávateľ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Zamestnávateľ'
                ]
            ])
            ->add('dateRange', MonthYearRangeType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis práca',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Opíšte Vaše pracovné skúsenosti'
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