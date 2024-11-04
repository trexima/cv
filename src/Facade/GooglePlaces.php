<?php

namespace Trexima\EuropeanCvBundle\Facade;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Utils;
use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Trexima\EuropeanCvBundle\Entity\Enum\GooglePlacesAutocompleteTypeEnum;
use Trexima\EuropeanCvBundle\Exception\GooglePlacesException;

/**
 * Facade for working with Google Places API
 */
class GooglePlaces
{
    private const BASE_URI = 'https://maps.googleapis.com/maps/api/place/';
    private const DETAILS_URL = 'details/json';
    private const AUTOCOMPLETE_URL = 'autocomplete/json';

    private const DETAILS_CACHE_KEY_PREFIX = 'google-places-details-';
    private const AUTOCOMPLETE_CACHE_KEY_PREFIX = 'google-places-autocomplete-';

    private const AUTOCOMPLETE_CACHE_TTL = 86400; // 24 hours
    private const DETAILS_CACHE_TTL = 604800; // 7 days

    private readonly Client $client;

    public function __construct(private readonly string $apiKey, private readonly CacheInterface $cache)
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'connect_timeout' => 10,
            'timeout' => 15,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @param string $placeId The place id received from google api
     * @param string $language
     * @return array|null
     * @throws GooglePlacesException
     */
    public function findById(string $placeId, string $language = 'sk'): ?array
    {
        $query = [
            'place_id' => $placeId,
            'language' => $language,
            'fields' => \implode(',', [
                'address_component',
                'formatted_address',
                'geometry',
                'name',
                'place_id',
                'type',
            ]),
        ];

        return $this->doFindById($query);
    }

    /**
     * @param string $query
     * @param string[] $countries
     * @param string $language
     * @param float|null $locationLat Latitude around which to search places. Default is the center of Slovakia.
     * @param float|null $locationLng Longitude around which to search places. Default is the center of Slovakia.
     * @param int|null $radius Radius in meters in which results have a higher priority. Default is 100km from the center of Slovakia.
     * @param GooglePlacesAutocompleteTypeEnum[] $types Types to restrict autocomplete search to.
     * @return array
     * @throws GooglePlacesException
     * @throws \InvalidArgumentException When tne number of countries is more than 5
     */
    public function autocomplete(
        string $query,
        array $countries = [],
        string $language = 'sk',
        ?float $locationLat = 48.730733,
        ?float $locationLng = 19.457483,
        ?int $radius = 100000,
        array $types = [GooglePlacesAutocompleteTypeEnum::Geocode],
    ): array {
        if (\count($countries) > 5) {
            throw new \InvalidArgumentException('The maximum number of countries supported is 5');
        }

        if (
            (null === $locationLat && null !== $locationLng)
            || (null !== $locationLat && null === $locationLng)
        ) {
            throw new \InvalidArgumentException('Both location latitude and longitude must be defined');
        }

        if (null !== $locationLat && ($locationLat < -90 || $locationLat > 90)) {
            throw new \InvalidArgumentException('Latitude must be in range from -90 to 90 degrees');
        }

        if (null !== $locationLng && ($locationLng < -180 || $locationLng > 180)) {
            throw new \InvalidArgumentException('Longitude must be in range from -180 to 180 degrees');
        }

        if (null !== $radius && $radius <= 0) {
            throw new \InvalidArgumentException('Radius must be greater than zero');
        }

        if (empty($types)) {
            throw new \InvalidArgumentException('At least one type must be specified');
        }

        $types = \array_unique($types, \SORT_REGULAR);

        if (
            \in_array(GooglePlacesAutocompleteTypeEnum::Geocode, $types) && \count($types) > 1
            || \in_array(GooglePlacesAutocompleteTypeEnum::Establishment, $types) && \count($types) > 1
            || \in_array(GooglePlacesAutocompleteTypeEnum::Address, $types) && \count($types) > 1
            || \in_array(GooglePlacesAutocompleteTypeEnum::Regions, $types) && \count($types) > 1
            || \in_array(GooglePlacesAutocompleteTypeEnum::Cities, $types) && \count($types) > 1
        ) {
            throw new \InvalidArgumentException(
                'Geocode, Establishment, Address, Regions and Cities, Address types can\'t be combined with other types',
            );
        } elseif (\count($types) > 5) {
            throw new \InvalidArgumentException('Maximum number of types supported is 5');
        }

        $query = [
            'input' => $query,
            'language' => $language,
        ];

        if (null !== $locationLat && null !== $locationLng) {
            $query['location'] = "$locationLat,$locationLng";
        }

        if (null !== $radius) {
            $query['radius'] = $radius;
        }

        if (\count($countries) > 0) {
            $query['components'] = \implode('|', \array_map(fn ($v) => 'country:' . $v, $countries));
        }

        $query['types'] = \implode('|', \array_map(fn (GooglePlacesAutocompleteTypeEnum $type) => $type->value, $types));

        return $this->doAutocomplete($query);
    }

    /**
     * @throws GooglePlacesException
     */
    private function doFindById(array $query): ?array
    {
        $cacheKey = $this->buildCacheKey(self::DETAILS_CACHE_KEY_PREFIX, $query);

        $cached = $this->getFromCache($cacheKey, self::DETAILS_CACHE_TTL, function () use ($query) {
            try {
                $result = $this->get(self::DETAILS_URL, $query);
            } catch (GuzzleException $e) {
                throw new GooglePlacesException('Google Places details API Guzzle exception', $e->getCode(), $e);
            }

            $validStatuses = ['OK', 'ZERO_RESULTS', 'NOT_FOUND'];
            if (!isset($result['status']) || !\in_array($result['status'], $validStatuses)) {
                throw new GooglePlacesException(
                    \sprintf('Received error code from Google Places details API: %s', $result['status']),
                );
            }

            if (empty($result['result'])) {
                return null; // Place does not exist
            }

            if (!\is_array($result['result'])) {
                throw new GooglePlacesException('Received unexpected response from Google Places details API');
            }

            return $result['result'];
        });

        if (null !== $cached && !\is_array($cached)) {
            // In case invalid data from cache is returned, delete it to prevent further cached errors
            $this->cache->delete($cacheKey);
            throw new GooglePlacesException('Invalid cached result type received. Expected "array" or "null"');
        }

        return $cached;
    }

    /**
     * @throws GooglePlacesException
     */
    private function doAutocomplete(array $query): array
    {
        $cacheKey = $this->buildCacheKey(self::AUTOCOMPLETE_CACHE_KEY_PREFIX, $query);

        $cached = $this->getFromCache($cacheKey, self::AUTOCOMPLETE_CACHE_TTL, function () use ($query) {
            try {
                $result = $this->get(self::AUTOCOMPLETE_URL, $query);
            } catch (GuzzleException $e) {
                throw new GooglePlacesException('Google Places autocomplete API Guzzle exception', $e->getCode(), $e);
            }

            $validStatuses = ['OK', 'ZERO_RESULTS'];
            if (!isset($result['status']) || !\in_array($result['status'], $validStatuses)) {
                $error = $result['status'];
                if (isset($result['error_message'])) {
                    $error .= ', ' . $result['error_message'];
                }

                throw new GooglePlacesException(
                    \sprintf('Received error code from Google Places autocomplete API: %s', $error),
                );
            }

            if (empty($result['predictions'])) {
                return [];
            }

            if (!\is_array($result['predictions'])) {
                throw new GooglePlacesException('Received unexpected response from Google Places autocomplete API');
            }

            return $result['predictions'];
        });

        if (!\is_array($cached)) {
            // In case invalid data from cache is returned, delete it to prevent further cached errors
            $this->cache->delete($cacheKey);
            throw new GooglePlacesException('Invalid cached result type received. Expected "array"');
        }

        return $cached;
    }

    /**
     * @throws GuzzleException
     */
    private function get(string $uri, $query): array
    {
        $query = \array_merge($query, ['key' => $this->apiKey]);

        $jsonResponse = $this
            ->client
            ->get($uri, ['query' => $query])
            ->getBody()
            ->getContents();

        return Utils::jsonDecode($jsonResponse, true);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function getFromCache(string $key, int $cacheTTL, callable $callback): mixed
    {
        return $this->cache->get($key, function (CacheItemInterface $cacheItem) use ($cacheTTL, $callback) {
            $cacheItem->expiresAfter($cacheTTL);
            return $callback();
        });
    }

    private function buildCacheKey(string $prefix, array $query): string
    {
        return $prefix . \md5(\implode('&', $query));
    }
}
