<?php

namespace Trexima\EuropeanCvBundle\Entity\Enum;

enum GooglePlacesAutocompleteTypeEnum: string
{
    case Geocode = 'geocode';
    // Business places
    case Establishment = 'establishment';
    // Generic address with precision on street with or without street number
    case Address = 'address';
    case Regions = '(regions)';
    case Cities = '(cities)';

    case Country = 'country';
    case Locality = 'locality';
    case AdministrativeAreaLevel1 = 'administrative_area_level_1';
    case AdministrativeAreaLevel2 = 'administrative_area_level_2';
    case AdministrativeAreaLevel3 = 'administrative_area_level_3';
    case Sublocality = 'sublocality';
    case SublocalityLevel1 = 'sublocality_level_1';
    case SublocalityLevel2 = 'sublocality_level_2';
    case Route = 'route';
    // Street address containing always a street number
    case StreetAddress = 'street_address';
}
