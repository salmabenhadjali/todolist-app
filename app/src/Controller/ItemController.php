<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ApiService;
use Symfony\UX\Turbo\TurboStreamResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemController extends AbstractController
{
    private ApiService $apiService;
    private HttpClientInterface $httpClient;

    public function __construct(ApiService $apiService, HttpClientInterface $httpClient)
    {
        $this->apiService = $apiService;
        $this->httpClient = $httpClient;
    }

    #[Route('/todolists/{idList<\d+>}/items', name: 'app_items_create', methods: ['POST'])]
    function create(Request $request, string $idList): Response
    {
        $title = $request->request->get('title');
        if (!$title) {
            return new JsonResponse(['error' => 'Missing title'], Response::HTTP_BAD_REQUEST);
        }

        // $item = $this->apiService->post('api_items_create', ['idList' => $idList], [
        //     'headers' => [
        //         'Content-Type' => 'application/json'
        //     ],
        //     'json' => ['title' => $title]
        // ]);
        $path = $this->generateUrl('api_items_create', ['idList' => $idList], UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => ['title' => $title]
        ]);
        $item = $response->toArray();
        dump(__FUNCTION__, $item);

        // $todolist = $this->apiService->get('api_todolists_get', ['id' => $idList]);

        $path = $this->generateUrl('api_todolists_get', ['id' => $idList], UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request('GET', $url);
        $todolist = $response->toArray();
        dump(__FUNCTION__, $todolist);

        return $this->render('list/_detail_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }

    #[Route('/items/{id<\d+>}', name: 'app_items_update', methods: ['PUT'])]
    function update(string $id, Request $request): Response
    {
        $title = $request->request->get('title');
        if (!$title) {
            return new JsonResponse(['error' => 'Missing title'], Response::HTTP_BAD_REQUEST);
        }

        $item = $this->apiService->post('api_todolists_update', ['id' => $id], [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => ['title' => $title]
        ]);
        dump(__FUNCTION__, $item);

        $todolist = $this->apiService->get('api_todolists_get', ['id' => $item['todolist']]);
        dump(__FUNCTION__, $todolist);

        return $this->render('list/_detail_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }

    #[Route('/items/{id<\d+>}', name: 'app_items_delete', methods: ['DELETE'])]
    function delete(string $id): Response
    {
        $item = $this->apiService->get('api_todolists_delete', ['id' => $id]);
        dump(__FUNCTION__, $item);

        return $this->render('list/_todolist_remove_stream.html.twig', [
            'idList' => $id
        ], new TurboStreamResponse());
    }
}
