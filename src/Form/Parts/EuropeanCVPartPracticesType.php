<?php

namespace Trexima\EuropeanCvBundle\Form\Parts;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPracticeProcessed;
use Trexima\EuropeanCvBundle\Form\Type\EuropeanCVPracticeType;
use Trexima\EuropeanCvBundle\Form\Type\EuropeanCVWorkBreakType;

/**
 * Practices.
 */
class EuropeanCVPartPracticesType extends AbstractType implements EventSubscriberInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('practices', CollectionType::class, [
                'entry_type' => EuropeanCVPracticeType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
            ->add('workBreaks', CollectionType::class, [
                'entry_type' => EuropeanCVWorkBreakType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
            ->addEventSubscriber($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCV::class,
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

    public function onPostSubmit(FormEvent $event): void
    {
        /** @var EuropeanCV $data */
        $europeanCV = $event->getData();
        $practices = $europeanCV->getPractices();
        $practicesProcessed = $europeanCV->getPracticesProcessed();

        $months = [];
        foreach ($practices as $practice) {
            if (empty($months[$practice->getIscoCode()])) {
                $months[$practice->getIscoCode()] = [
                    'title' => $practice->getTitle(),
                    'values' => [],
                ];
            }
            $months[$practice->getIscoCode()]['values'] = array_unique(array_merge(
                $months[$practice->getIscoCode()]['values'],
                $this->getMonthsFromMonthYearRange(
                    $practice->getDateRange()->getBeginMonth(),
                    $practice->getDateRange()->getBeginYear(),
                    $practice->getDateRange()->getEndMonth(),
                    $practice->getDateRange()->getEndYear()
                )
            ));
        }

        // remove not iscos which were removed
        $iscoCodes = array_keys($months);
        foreach ($practicesProcessed as $practiceProcessed) {
            if (!\in_array($practiceProcessed->getIscoCode(), $iscoCodes)) {
                $europeanCV->removePracticeProcessed($practiceProcessed);
            }
        }

        foreach ($months as $iscoCode => $month) {
            $practiceProcessed = null;
            // if isco exists get entity
            foreach ($practicesProcessed as $practice) {
                if ($practice->getIscoCode() == $iscoCode) {
                    $practiceProcessed = $practice;
                    break;
                }
            }
            // if isco does not exists create entity
            if (!$practiceProcessed) {
                $practiceProcessed = new EuropeanCVPracticeProcessed();
                $practiceProcessed->setEuropeanCV($europeanCV);
                $practiceProcessed->setIscoCode($iscoCode);
            }
            // set months
            $practiceProcessed->setTitle($month['title']);
            $practiceProcessed->setMonths(\count($month['values']));
            $europeanCV->addPracticeProcessed($practiceProcessed);
        }

        $event->setData($europeanCV);
    }

    private function getMonthsFromMonthYearRange($monthFrom, $yearFrom, $monthTo = null, $yearTo = null)
    {
        $now = new \DateTime();
        $months = [];

        // set today values if monthTo and yearTo are null
        if (null === $monthTo) {
            $monthTo = (int) $now->format('m');
        }
        if (null === $yearTo) {
            $yearTo = (int) $now->format('Y');
        }

        // handle invalied ranges
        if ($yearFrom > $yearTo) {
            return $months;
        }
        if ($yearFrom === $yearTo && $monthFrom > $monthTo) {
            return $months;
        }

        // add all year-month combinataions from range to array
        for ($i = $yearFrom; $i <= $yearTo; ++$i) {
            for ($j = 1; $j <= 12; ++$j) {
                if ($i === $yearFrom && $j < $monthFrom) {
                    continue;
                }
                if ($i === $yearTo && $j > $monthTo) {
                    continue;
                }
                $months[] = $i.'-'.$j;
            }
        }

        return $months;
    }
}
