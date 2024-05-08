<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Entity>
     */
    #[ORM\OneToMany(targetEntity: Entity::class, mappedBy: 'category', orphanRemoval: true)]
    private Collection $entities;

    public function __construct()
    {
        $this->entities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Entity>
     */
    public function getEntities(): Collection
    {
        return $this->entities;
    }

    public function addEntity(Entity $entity): static
    {
        if (!$this->entities->contains($entity)) {
            $this->entities->add($entity);
            $entity->setCategoryId($this);
        }

        return $this;
    }

    public function removeEntity(Entity $entity): static
    {
        if ($this->entities->removeElement($entity)) {
            // set the owning side to null (unless already changed)
            if ($entity->getCategoryId() === $this) {
                $entity->setCategoryId(null);
            }
        }

        return $this;
    }
}
