<?php

namespace App\Entity;

use App\Repository\RecipeIngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient = null;

    #[ORM\OneToMany(mappedBy: 'recipeIngredient', targetEntity: RecipeIngredientPortionNumber::class, cascade: ['persist', 'remove'])]
    private Collection $portionNumbers;

    public function __construct()
    {
        $this->portionNumbers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
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

    /**
     * @return Collection<int, RecipeIngredientPortionNumber>
     */
    public function getPortionNumbers(): Collection
    {
        return $this->portionNumbers;
    }

    /**
     * @param ArrayCollection|Collection $portionNumbers
     */
    public function setPortionNumbers(ArrayCollection|Collection $portionNumbers): RecipeIngredient
    {
        $this->portionNumbers = $portionNumbers;
        return $this;
    }
}
