<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\UX\Turbo\TurboStreamResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoListController extends AbstractController
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/', name: 'app_todolists_all', methods: ['GET'])]
    function homepage(): Response
    {
        $path = $this->generateUrl('api_todolists_all', [], UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request('GET', $url);
        $todoLists = $response->toArray();

        return $this->render('list/homepage.html.twig', [
            'lists' => $todoLists
        ]);
    }

    #[Route('/todolists/{id<\d+>}', name: 'app_todolists_detail', methods: ['GET'])]
    function itemPage(string $id): Response
    {

        $path = $this->generateUrl('api_todolists_get', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request('GET', $url);
        $todolist = $response->toArray();

        return $this->render('list/details.html.twig', [
            'todolist' => $todolist
        ]);
    }

    #[Route('/todolists', name: 'app_todolists_create', methods: ['POST'])]
    function create(Request $request): Response
    {
        $name = $request->request->get('name');
        if (!$name) {
            return new JsonResponse(['error' => 'Missing name'], Response::HTTP_BAD_REQUEST);
        }

        $path = $this->generateUrl('api_todolists_create', [], UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => ['name' => $name]
        ]);
        $todolist = $response->toArray();

        return $this->render('list/_todolist_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }

    #[Route('/todolists/{id<\d+>}', name: 'app_todolists_update', methods: ['PUT'])]
    function update(string $id, Request $request): Response
    {
        $name = $request->request->get('name');
        if (!$name) {
            return new JsonResponse(['error' => 'Missing name'], Response::HTTP_BAD_REQUEST);
        }

        $path = $this->generateUrl(
            'api_todolists_update',
            ['id' => $id],
            UrlGeneratorInterface::ABSOLUTE_PATH
        );
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request('PUT', $url, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => ['name' => $name]
        ]);
        $todolist = $response->toArray();

        return $this->render('list/_detail_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }

    #[Route('/todolists/{id<\d+>}', name: 'app_todolists_delete', methods: ['DELETE'])]
    function delete(string $id): Response
    {
        $path = $this->generateUrl('api_todolists_delete', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $this->httpClient->request('DELETE', $url);

        return $this->render('list/_todolist_remove_stream.html.twig', [
            'idList' => $id
        ], new TurboStreamResponse());
    }
}
