<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class RecipePortionNumber
{
    private Recipe $recipe;
    private int $portionNumber;
    /**
     * @var Collection<int, RecipeIngredientPortionNumber> $recipeIngredientPortionNumberList
     */
    private Collection $recipeIngredientPortionNumberList;

    public function __construct()
    {
        $this->recipeIngredientPortionNumberList = new ArrayCollection();
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredientPortionNumber>
     */
    public function getRecipeIngredientPortionNumberList(): Collection
    {
        return $this->recipeIngredientPortionNumberList;
    }

    /**
     * @param Collection<int, RecipeIngredientPortionNumber> $recipeIngredientPortionNumberList
     */
    public function setRecipeIngredientPortionNumberList(Collection $recipeIngredientPortionNumberList): self
    {
        $this->recipeIngredientPortionNumberList = $recipeIngredientPortionNumberList;

        return $this;
    }

    public function addRecipeIngredientPortionNumber(RecipeIngredientPortionNumber $recipeIngredientPortionNumber): self
    {
        if (!$this->recipeIngredientPortionNumberList->contains($recipeIngredientPortionNumber)) {
            $this->recipeIngredientPortionNumberList->add($recipeIngredientPortionNumber);
        }

        return $this;
    }

    public function removeRecipeIngredientPortionNumber(RecipeIngredientPortionNumber $recipeIngredientPortionNumber): self
    {
        $this->recipeIngredientPortionNumberList->removeElement($recipeIngredientPortionNumber);

        return $this;
    }

    public function getPortionNumber(): int
    {
        return $this->portionNumber;
    }

    public function setPortionNumber(int $portionNumber): self
    {
        $this->portionNumber = $portionNumber;

        return $this;
    }
}
