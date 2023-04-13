<?php

namespace Trexima\EuropeanCvBundle\Util;

use Trexima\EuropeanCvBundle\Entity\AbstractGoogleAddress;

class GooglePlaceUtil
{
    private function __construct()
    {
    }

    /**
     * Map data from google place result to a more readable data appropriate for {@link AbstractGoogleAddress}
     */
    public static function mapComponents(array $place, string $language = 'sk'): array
    {
        $country = static::getAddressComponent('country', $place);
        $al1 = static::getAddressComponent('administrative_area_level_1', $place);
        $al2 = static::getAddressComponent('administrative_area_level_2', $place);
        $al3 = static::getAddressComponent('administrative_area_level_3', $place);
        $locality = static::getAddressComponent('locality', $place);
        $sl1 = static::getAddressComponent('sublocality_level_1', $place);
        $sl2 = static::getAddressComponent('sublocality_level_2', $place);
        $sl3 = static::getAddressComponent('sublocality_level_3', $place);
        $route = static::getAddressComponent('route', $place);
        $premise = static::getAddressComponent('premise', $place);
        $subpremise = static::getAddressComponent('subpremise', $place);
        $streetNumber = static::getAddressComponent('street_number', $place);
        $postalTown = static::getAddressComponent('postal_town', $place);
        $postalCode = static::getAddressComponent('postal_code', $place);
        $location = static::getLocation($place);

        return [
            'googlePlaceId' => $place['place_id'],
            'languageCode' => $language,
            'formattedAddress' => $place['formatted_address'] ?? '',
            'country' => $country['long_name'],
            'countryIso2Code' => $country['short_name'],
            'administrativeAreaLevel1' => $al1['long_name'] ?? null,
            'administrativeAreaLevel2' => $al2['long_name'] ?? null,
            'administrativeAreaLevel3' => $al3['long_name'] ?? null,
            'locality' => $locality['long_name'] ?? null,
            'sublocalityLevel1' => $sl1['long_name'] ?? null,
            'sublocalityLevel2' => $sl2['long_name'] ?? null,
            'sublocalityLevel3' => $sl3['long_name'] ?? null,
            'route' => $route['long_name'] ?? null,
            'premise' => $premise['long_name'] ?? null,
            'subpremise' => $subpremise['long_name'] ?? null,
            'streetNumber' => $streetNumber['long_name'] ?? null,
            'postalTown' => $postalTown['long_name'] ?? null,
            'postalCode' => $postalCode['long_name'] ?? null,
            'latitude' => $location['lat'] ?? null,
            'longitude' => $location['lng'] ?? null,
        ];
    }

    public static function getAddressComponent(string $component, array $place): ?array
    {
        if (isset($place['address_components']) && \is_array($place['address_components'])) {
            foreach ($place['address_components'] as $cmp) {
                if (isset($cmp['types']) && \is_array($cmp['types']) && \in_array($component, $cmp['types'], true)) {
                    return $cmp;
                }
            }
        }

        return null;
    }

    public static function getLocation(array $place): ?array
    {
        if (isset($place['geometry']['location']) && \is_array($place['geometry']['location'])) {
            return $place['geometry']['location'];
        }

        return null;
    }
}
