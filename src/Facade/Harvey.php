<?php

namespace Trexima\EuropeanCvBundle\Facade;

use Symfony\Contracts\Cache\CacheInterface;
use Trexima\HarveyClient\Client;
use Trexima\HarveyClient\MethodParameterExtractor;

class Harvey
{
    private readonly Client $client;

    public function __construct(string $url, string $username, string $password, CacheInterface $cache)
    {
        $methodParameterExtractor = new MethodParameterExtractor($cache);
        $this->client = new Client($url, $username, $password, $methodParameterExtractor, $cache);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
