<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $unit = null;

    #[ORM\Column]
    private ?float $vat = null;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: WarehouseItem::class, orphanRemoval: true)]
    private Collection $warehouses;

    public function __construct()
    {
        $this->warehouses = new ArrayCollection();
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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @return Collection<int, WarehouseItem>
     */
    public function getWarehouses(): Collection
    {
        return $this->warehouses;
    }

    public function addWarehouse(WarehouseItem $warehouse): self
    {
        if (!$this->warehouses->contains($warehouse)) {
            $this->warehouses->add($warehouse);
            $warehouse->setItem($this);
        }

        return $this;
    }

    public function removeWarehouse(WarehouseItem $warehouse): self
    {
        if ($this->warehouses->removeElement($warehouse)) {
            // set the owning side to null (unless already changed)
            if ($warehouse->getItem() === $this) {
                $warehouse->setItem(null);
            }
        }

        return $this;
    }
}
