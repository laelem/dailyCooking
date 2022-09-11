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
        'closet' => 'placard',
        'fridge' => 'frigidaire',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de l\'ingrédient est requis.')]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Choice(callback: 'getWhereToKeepOptions', message: 'Cette option n\'est pas valide.')]
    private ?string $whereToKeep = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: RecipeIngredient::class)]
    private Collection $recipeIngredients;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?IngredientCategory $category = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'ingredients')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private Collection $tags;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public static function getWhereToKeepOptions(): array
    {
        return array_flip(array_merge(
          ['' => 'je ne sais pas'],
          self::WHERE_TO_KEEP_OPTIONS
        ));
    }

    public function getWhereToKeepLabel(): ?string
    {
        if ($this->whereToKeep) {
            return self::WHERE_TO_KEEP_OPTIONS[$this->whereToKeep];
        }

        return null;
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

    public function getCategory(): ?IngredientCategory
    {
        return $this->category;
    }

    public function setCategory(?IngredientCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
