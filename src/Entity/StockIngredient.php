<?php

namespace App\Entity;

use App\Repository\StockIngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockIngredientRepository::class)]
class StockIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stockIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient = null;

    #[ORM\ManyToOne]
    private ?QuantityType $quantityType = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    private ?string $quantityNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expiresAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $stockStatus = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getQuantityType(): ?QuantityType
    {
        return $this->quantityType;
    }

    public function setQuantityType(?QuantityType $quantityType): self
    {
        $this->quantityType = $quantityType;

        return $this;
    }

    public function getQuantityNumber(): ?string
    {
        return $this->quantityNumber;
    }

    public function setQuantityNumber(?string $quantityNumber): self
    {
        $this->quantityNumber = $quantityNumber;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getStockStatus(): ?string
    {
        return $this->stockStatus;
    }

    public function setStockStatus(?string $stockStatus): self
    {
        $this->stockStatus = $stockStatus;

        return $this;
    }
}
