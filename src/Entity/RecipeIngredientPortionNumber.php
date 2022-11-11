<?php

namespace App\Entity;

use App\Repository\RecipeIngredientPortionNumberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientPortionNumberRepository::class)]
class RecipeIngredientPortionNumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: "portionNumbers")]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecipeIngredient $recipeIngredient = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, options: ["unsigned" => true])]
    private ?int $portionNumber = null;

    #[ORM\ManyToOne]
    private ?QuantityType $quantityType = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true, options: ["unsigned" => true])]
    private $quantityNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipeIngredient(): ?RecipeIngredient
    {
        return $this->recipeIngredient;
    }

    public function setRecipeIngredient(?RecipeIngredient $recipeIngredient): self
    {
        $this->recipeIngredient = $recipeIngredient;

        return $this;
    }

    public function getPortionNumber(): ?int
    {
        return $this->portionNumber;
    }

    public function setPortionNumber(?int $portionNumber): self
    {
        $this->portionNumber = $portionNumber;

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

    public function getQuantityNumber()
    {
        return $this->quantityNumber;
    }

    public function setQuantityNumber($quantityNumber): self
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
}
