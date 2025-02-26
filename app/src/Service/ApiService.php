<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class ApiService
{
    private HttpClientInterface $httpClient;
    private UrlGeneratorInterface $urlGenerator;
    private string $apiBaseUrl;
    private RequestStack $requestStack;

    public function __construct(HttpClientInterface $httpClient, UrlGeneratorInterface $urlGenerator, string $apiBaseUrl, RequestStack $requestStack)
    {
        $this->httpClient = $httpClient;
        $this->urlGenerator = $urlGenerator;
        $this->apiBaseUrl = $apiBaseUrl;
        $this->requestStack = $requestStack;
    }

    function send(string $routeName, string $methods, array $params = [], $options = []): array
    {
        // Get the current request from the RequestStack
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new \RuntimeException('No request available.');
        }

        $jwtToken = $request->getSession()->get('api_token');

        if (!$jwtToken) {
            throw new \RuntimeException('No API token found in session.');
        }

        $url = $this->urlGenerator->generate($routeName, $params);

        $response = $this->httpClient->request($methods, $this->apiBaseUrl . $url, [
            ...$options,
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'max_redirects' => 0, // Disable redirects
        ]);

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            throw new \RuntimeException('API request failed: ' . $response->getContent(false));
        }

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
