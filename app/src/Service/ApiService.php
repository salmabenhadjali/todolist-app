<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class ApiService
{
    private HttpClientInterface $httpClient;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(HttpClientInterface $httpClient, UrlGeneratorInterface $urlGenerator)
    {
        $this->httpClient = $httpClient;
        $this->urlGenerator = $urlGenerator;
    }

    function send(string $routeName, string $methods, array $params = [], $options = []): array
    {
        $path = $this->urlGenerator->generate($routeName, $params, UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request($methods, $url, $options);
        return $response->toArray();
    }

    function get(string $routeName, array $params = [], array $options = []): array
    {
        return $this->send($routeName, 'GET', $params, $options);
    }

    function post(string $routeName, array $params = [], array $options = []): array
    {
        return $this->send($routeName, 'POST', $params, $options);
    }

    function delete(string $routeName, array $params = [], array $options = []): array
    {
        return $this->send($routeName, 'DELETE', $params, $options);
    }

    function put(string $routeName, array $params = [], array $options = []): array
    {
        return $this->send($routeName, 'PUT', $params, $options);
    }
}
