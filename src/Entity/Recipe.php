<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class, cascade: ['persist', 'remove'])]
    private Collection $recipeIngredients;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeStep::class, cascade: ['persist', 'remove'])]
    private Collection $recipeSteps;

    /**
     * @var Collection<int, RecipePortionNumber>
     */
    private Collection $portions;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
        $this->recipeSteps = new ArrayCollection();

        $this->hydratePortions();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeStep>
     */
    public function getRecipeSteps(): Collection
    {
        return $this->recipeSteps;
    }

    public function addRecipeStep(RecipeStep $recipeStep): self
    {
        if (!$this->recipeSteps->contains($recipeStep)) {
            $this->recipeSteps->add($recipeStep);
            $recipeStep->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeStep(RecipeStep $recipeStep): self
    {
        if ($this->recipeSteps->removeElement($recipeStep)) {
            // set the owning side to null (unless already changed)
            if ($recipeStep->getRecipe() === $this) {
                $recipeStep->setRecipe(null);
            }
        }

        return $this;
    }

    public function hydratePortions(): self
    {
        $ingredientsByPortions = [];

        foreach ($this->getRecipeIngredients() as $recipeIngredient) {
            foreach($recipeIngredient->getPortionNumbers() as $recipeIngredientPortionNumber) {
                if (!isset($ingredientsByPortions[$recipeIngredientPortionNumber->getPortionNumber()])) {
                    $ingredientsByPortions[$recipeIngredientPortionNumber->getPortionNumber()] = [];
                }
                $ingredientsByPortions[$recipeIngredientPortionNumber->getPortionNumber()][] = $recipeIngredientPortionNumber;
            }
        }

        $this->setPortions(new ArrayCollection());
        foreach ($ingredientsByPortions as $portionNumber => $ingredientsByPortion) {
            $recipePortionNumber = (new RecipePortionNumber())->setPortionNumber($portionNumber);
            foreach ($ingredientsByPortion as $ingredient) {
                $recipePortionNumber->addRecipeIngredientPortionNumber($ingredient);
            }
            $this->addPortion($recipePortionNumber);
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipePortionNumber>
     */
    public function getPortions(): Collection
    {
        return $this->portions;
    }

    /**
     * @param Collection<int, RecipePortionNumber> $portions
     * @return Recipe
     */
    public function setPortions(Collection $portions): self
    {
        $this->portions = $portions;

        return $this;
    }

    public function addPortion(RecipePortionNumber $portion): self
    {
        if (!$this->portions->contains($portion)) {
            $this->portions->add($portion);
            $portion->setRecipe($this);
        }

        return $this;
    }

    public function removePortion(RecipePortionNumber $portion): self
    {
        if ($this->portions->removeElement($portion)) {
            // set the owning side to null (unless already changed)
            if ($portion->getRecipe() === $this) {
                $portion->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return array<int>
     */
    public function getPortionNumberList(): array
    {
        return $this->getPortions()->map(function(RecipePortionNumber $e) {
            return $e->getPortionNumber();
        })->toArray();
    }
}
