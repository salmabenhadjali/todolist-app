<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: "item")]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['item_list', 'todo_list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['item_list', 'todo_list'])]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[Groups(['item_list'])]
    private ?TodoList $todoList = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    #[Groups(['item_list', 'todo_list'])]
    private ?bool $is_completed = false;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subItem')]
    private ?self $parentItem = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentItem')]
    #[Groups(['item_list', 'todo_list'])]
    private Collection $subItems;

    public function __construct()
    {
        $this->subItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTodoList(): ?TodoList
    {
        return $this->todoList;
    }

    public function setTodoList(?TodoList $todoList): static
    {
        $this->todoList = $todoList;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->is_completed;
    }

    public function setIsCompleted(bool $is_completed): static
    {
        $this->is_completed = $is_completed;

        return $this;
    }

    public function getParentItem(): ?self
    {
        return $this->parentItem;
    }

    public function setParentItem(?self $parentItem): static
    {
        $this->parentItem = $parentItem;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubItems(): Collection
    {
        return $this->subItems;
    }

    public function addSubItem(self $subItem): static
    {
        if (!$this->subItems->contains($subItem)) {
            $this->subItems->add($subItem);
            $subItem->setParentItem($this);
        }

        return $this;
    }

    public function removeSubItem(self $subItem): static
    {
        if ($this->subItems->removeElement($subItem)) {
            // set the owning side to null (unless already changed)
            if ($subItem->getParentItem() === $this) {
                $subItem->setParentItem(null);
            }
        }

        return $this;
    }
}
