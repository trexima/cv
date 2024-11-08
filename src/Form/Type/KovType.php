<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Trexima\EuropeanCvBundle\Facade\Harvey;

/**
 * It is expected that data used with this form type contains all necessary properties.
 */
class KovType extends AbstractMappedAutocompleteType
{
    public function __construct(
        protected readonly Harvey $harvey,
        ?PropertyAccessorInterface $propertyAccessor = null,
    ) {
        parent::__construct($propertyAccessor);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'choice_label' => 'kovTitle',
                'choice_value' => 'kovCode',
                'choice_property_mapper' => [
                    'kovCode' => 'kovCode',
                    'kovTitle' => 'kovTitle',
                ],
                'error_bubbling' => false,
                'select2_tags' => true,
            ]);
    }

    protected function retrieveDataForValue(?string $value): ?array
    {
        if (null === $value || '' === $value) {
            return null;
        }

        $title = $value;

        try {
            $kovs = $this->harvey->getClient()->searchKov(null, $value);
            if (!empty($kovs)) {
                $kov = $kovs[0];
                $title = $kov['code'].' '.$kov['title'].' ('.$this->getKovLevelTitle($kov['kovLevel']).')';
            }
        } catch (\Exception) {
            return null;
        }

        return [
            'kovCode' => $value,
            'kovTitle' => $title,
        ];
    }

    protected function createChoice(mixed &$data, mixed $retrievedData, array $options): void
    {
        $cls = $options['class'];

        if (null === $data) {
            $data = new $cls();
        }

        foreach ($options['choice_property_mapper'] as $k => $v) {
            if ($this->propertyAccessor->isWritable($data, $k)) {
                $this->propertyAccessor->setValue($data, $k, $retrievedData[$v]);
            }
        }
    }

    private function getKovLevelTitle($kov)
    {
        $kovLevel = $this->harvey->getClient()->getKovLevel(str_replace('/api/kov-level/', '', $kov));

        return $kovLevel['title'] ?? '';
    }
}
