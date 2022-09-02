<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    const WHERE_TO_KEEP_OPTIONS = [
        'je ne sais pas' => '',
        'placard' => 'closet',
        'frigidaire' => 'fridge',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Choice(choices: self::WHERE_TO_KEEP_OPTIONS)]
    private ?string $whereToKeep = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: RecipeIngredient::class)]
    private Collection $recipeIngredients;

    #[ORM\ManyToOne(inversedBy: 'category1Ingredients')]
    private ?IngredientCategory $category1 = null;

    #[ORM\ManyToOne(inversedBy: 'category2Ingredients')]
    private ?IngredientCategory $category2 = null;

    #[ORM\ManyToOne(inversedBy: 'category3Ingredients')]
    private ?IngredientCategory $category3 = null;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
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

    public function getWhereToKeep(): ?string
    {
        return $this->whereToKeep;
    }

    public function getWhereToKeepLabel(): ?string
    {
        return array_flip(self::WHERE_TO_KEEP_OPTIONS)[$this->whereToKeep];
    }

    public function setWhereToKeep(?string $whereToKeep): self
    {
        $this->whereToKeep = $whereToKeep;

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
            $recipeIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getIngredient() === $this) {
                $recipeIngredient->setIngredient(null);
            }
        }

        return $this;
    }

    public function getCategory1(): ?IngredientCategory
    {
        return $this->category1;
    }

    public function setCategory1(?IngredientCategory $category1): self
    {
        $this->category1 = $category1;

        return $this;
    }

    public function getCategory2(): ?IngredientCategory
    {
        return $this->category2;
    }

    public function setCategory2(?IngredientCategory $category2): self
    {
        $this->category2 = $category2;

        return $this;
    }

    public function getCategory3(): ?IngredientCategory
    {
        return $this->category3;
    }

    public function setCategory3(?IngredientCategory $category3): self
    {
        $this->category3 = $category3;

        return $this;
    }
}
