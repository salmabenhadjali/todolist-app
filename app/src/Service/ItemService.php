<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Item;
use DateTimeImmutable;
use App\Entity\TodoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ItemService
{
    private EntityManagerInterface $entityManager;
    private NormalizerInterface $normalizer;

    public function __construct(EntityManagerInterface $entityManager, NormalizerInterface $normalizer)
    {
        $this->entityManager = $entityManager;
        $this->normalizer = $normalizer;
    }

    public function create(string $idList, string $title): array
    {
        $todolist = $this->entityManager->getRepository(TodoList::class)->find($idList);

        $now = new DateTimeImmutable();

        $item = new Item();
        $item->setTitle($title)
            ->setTodoList($todolist)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return $this->normalizer->normalize($item, null, ['groups' => 'item_list']);
    }

    public function update(string $id, string $title): array
    {
        $item = $this->entityManager->getRepository(Item::class)->find($id);

        $now = new DateTimeImmutable();
        $item->setTitle($title)
            ->setUpdatedAt($now);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return $this->normalizer->normalize($item, null, ['groups' => 'todo_list']);
    }

    public function delete(string $id): void
    {
        $item = $this->entityManager->getRepository(Item::class)->find($id);
        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }
}
