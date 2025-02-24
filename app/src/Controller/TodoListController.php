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

class TodoListController extends AbstractController
{
    private ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    #[Route('/', name: 'app_todolists_all', methods: ['GET'])]
    function homepage(): Response
    {
        $todoLists = $this->apiService->get('api_todolists_all', []);

        return $this->render('list/homepage.html.twig', [
            'todolists' => $todoLists
        ]);
    }

    #[Route('/todolists/{id<\d+>}', name: 'app_todolists_detail', methods: ['GET'])]
    function itemPage(string $id): Response
    {
        $todolist = $this->apiService->get('api_todolists_get', ['id' => $id]);

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

        $this->apiService->post('api_todolists_create', [], [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => ['name' => $name]
        ]);

        $todoLists = $this->apiService->get('api_todolists_all', []);

        return $this->render('list/_todolist_stream.html.twig', [
            'todolists' => $todoLists
        ], new TurboStreamResponse());
    }

    #[Route('/todolists/{id<\d+>}', name: 'app_todolists_update', methods: ['PUT'])]
    function update(string $id, Request $request): Response
    {
        $name = $request->request->get('name');
        if (!$name) {
            return new JsonResponse(['error' => 'Missing name'], Response::HTTP_BAD_REQUEST);
        }

        $todolist = $this->apiService->put('api_todolists_update', ['id' => $id], [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => ['name' => $name]
        ]);

        return $this->render('list/_detail_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }

    #[Route('/todolists/{id<\d+>}', name: 'app_todolists_delete', methods: ['DELETE'])]
    function delete(string $id): Response
    {
        $this->apiService->delete('api_todolists_delete', ['id' => $id]);

        $todoLists = $this->apiService->get('api_todolists_all', []);

        return $this->render('list/_todolist_stream.html.twig', [
            'todolists' => $todoLists
        ], new TurboStreamResponse());
    }
}
