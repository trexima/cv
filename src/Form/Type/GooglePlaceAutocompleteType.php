<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Trexima\EuropeanCvBundle\Entity\AbstractGoogleAddress;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Trexima\EuropeanCvBundle\Facade\GooglePlaces;
use Trexima\EuropeanCvBundle\Util\GooglePlaceUtil;

/**
 * It is expected that data used with this form type contains all necessary properties.
 * Used mainly with {@link AbstractGoogleAddress}
 */
class GooglePlaceAutocompleteType extends AbstractMappedAutocompleteType
{
    public function __construct(
        protected readonly GooglePlaces $googlePlaces,
        PropertyAccessorInterface $propertyAccessor = null,
    ) {
        parent::__construct($propertyAccessor);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'choice_label' => 'formattedAddress',
                'choice_value' => 'googlePlaceId',
                'choice_property_mapper' => [
                    'googlePlaceId' => 'googlePlaceId',
                    'languageCode' => 'languageCode',
                    'formattedAddress' => 'formattedAddress',
                    'country' => 'country',
                    'countryIso2Code' => 'countryIso2Code',
                    'administrativeAreaLevel1' => 'administrativeAreaLevel1',
                    'administrativeAreaLevel2' => 'administrativeAreaLevel2',
                    'administrativeAreaLevel3' => 'administrativeAreaLevel3',
                    'locality' => 'locality',
                    'sublocalityLevel1' => 'sublocalityLevel1',
                    'sublocalityLevel2' => 'sublocalityLevel2',
                    'sublocalityLevel3' => 'sublocalityLevel3',
                    'route' => 'route',
                    'premise' => 'premise',
                    'subpremise' => 'subpremise',
                    'streetNumber' => 'streetNumber',
                    'postalTown' => 'postalTown',
                    'postalCode' => 'postalCode',
                    'latitude' => 'latitude',
                    'longitude' => 'longitude',
                ],
            ]);
    }

    protected function retrieveDataForValue(?string $value): ?array
    {
        if (null === $value) {
            return null;
        }

        try {
            $googlePlace = $this->googlePlaces->findById($value);
        } catch (\Exception) {
            return null;
        }

        if (null === $googlePlace) {
            return null;
        }

        // TODO: different languages
        return GooglePlaceUtil::mapComponents($googlePlace);
    }
}
