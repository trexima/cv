<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Trexima\EuropeanCvBundle\Facade\Harvey;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * It is expected that data used with this form type contains all necessary properties.
 */
class SkIscoType extends AbstractMappedAutocompleteType
{
    public function __construct(
        protected readonly Harvey $harvey,
        PropertyAccessorInterface $propertyAccessor = null,
    ) {
        parent::__construct($propertyAccessor);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'choice_label' => 'title',
                'choice_value' => 'iscoCode',
                'choice_property_mapper' => [
                    'iscoCode' => 'iscoCode',
                    'title' => 'title',
                ],
                'error_bubbling' => false,
                'select2_tags' => true
            ]);
    }

    protected function retrieveDataForValue(?string $value): ?array
    {
        if (null === $value || '' === $value) {
            return null;
        }

        $title = $value;

        try {
            $skIscos = $this->harvey->getClient()->searchIsco(null, null, null, [], $value);
            if (!empty($skIscos)) {
                $title = $skIscos[0]['title'];
            }
        } catch (\Exception) {
            return null;
        }

        return [
            'iscoCode' => $value,
            'title' => $title
        ];
    }
}
