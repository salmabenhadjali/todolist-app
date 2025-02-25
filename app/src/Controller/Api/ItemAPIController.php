<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\ItemService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemAPIController extends AbstractController
{
    private LoggerInterface $logger;
    private ItemService $itemService;

    public function __construct(LoggerInterface $logger, ItemService $itemService)
    {
        $this->logger = $logger;
        $this->itemService = $itemService;
    }

    #[Route('/api/todolists/{idList<\d+>}/items', methods: ['POST'], name: 'api_items_create')]
    public function create(string $idList, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? null;

        if (!$title) {
            $this->logger->error('Invalid data for creating an Item');
            return $this->json(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        };

        $item = $this->itemService->create($idList, $title);

        $this->logger->info('Item cretaed with ID {id}', [
            'id' => $item['id'],
        ]);

        return $this->json($item, Response::HTTP_CREATED);
    }

    #[Route('/api/items/{id<\d+>}/sub-items', methods: ['POST'], name: 'api_subitems_create')]
    public function createSubItem(string $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? null;

        if (!$title) {
            $this->logger->error('Invalid data for creating an Item');
            return $this->json(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        };

        $item = $this->itemService->createSubItem($id, $title);

        $this->logger->info('Item cretaed with ID {id}', [
            'id' => $item['id'],
        ]);

        return $this->json($item, Response::HTTP_CREATED);
    }

    #[Route('/api/items/{id<\d+>}', methods: ['PUT'], name: 'api_items_update')]
    public function update(string $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? null;

        if (!$title) {
            $this->logger->error('Invalid data for updating an Item');
            return $this->json(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        };

        $item = $this->itemService->update($id, $title);

        $this->logger->info("Item updated with ID {id}", [
            'data' => $item,
        ]);

        return $this->json($item, Response::HTTP_OK);
    }

    #[Route('/api/items/{id<\d+>}', methods: ['DELETE'], name: 'api_items_delete')]
    public function delete(string $id): Response
    {
        $item = $this->itemService->delete($id);

        $this->logger->info("Item deleted with ID {id}");

        return $this->json($item, Response::HTTP_OK);
    }
}
