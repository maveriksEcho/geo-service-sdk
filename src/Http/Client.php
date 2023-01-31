<?php

namespace GeoService\Http;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

class Client
{
    protected $http;
    private $config;

    public function __construct(Factory $factory, array $config = [])
    {
        $this->processOptions($config);

        $this->http = $factory->asJson()
            ->baseUrl($this->config['url'])
            ->async($this->config['async'] ?? false)
            ->timeout(30);
    }

    public function getHttp()
    {
        return $this->http;
    }

    public function processOptions($config): void
    {
        $this->config = $config;
    }

    public function request(string $method, string $uri, array $options = []): PromiseInterface|Response
    {
        return $this->http->send($method, $uri, $options);
    }

    public function get(string $uri, array $options = []): PromiseInterface|Response
    {
        return $this->http->get($uri, $options);
    }

    public function post(string $uri, array $options = []): PromiseInterface|Response
    {
        return $this->http->post($uri, $options);
    }
}