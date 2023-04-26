<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
class Warehouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'warehouse')]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'warehouse', targetEntity: WarehouseItem::class, orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addWarehouse($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeWarehouse($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, WarehouseItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(WarehouseItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setWarehouse($this);
        }

        return $this;
    }

    public function removeItem(WarehouseItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getWarehouse() === $this) {
                $item->setWarehouse(null);
            }
        }

        return $this;
    }

}
