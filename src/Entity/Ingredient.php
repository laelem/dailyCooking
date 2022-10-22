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
        'closet' => 'Placard',
        'fridge' => 'Frigidaire',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de l\'ingrédient est requis.', normalizer: 'trim')]
    #[Assert\Length(max: 255, maxMessage: "Le nom de l'ingrédient ne peut excéder 255 caractères.")]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Choice(callback: 'getWhereToKeepOptions', message: 'Cette option n\'est pas valide.')]
    private ?string $whereToKeep = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: RecipeIngredient::class)]
    private Collection $recipeIngredients;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'La catégorie est requise.')]
    #[Assert\Type(type: IngredientCategory::class, message: "Cette valeur n'est pas du bon type.")]
    #[Assert\Valid]
    private ?IngredientCategory $category = null;

    #[ORM\ManyToMany(targetEntity: IngredientTag::class, inversedBy: 'ingredients')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    #[Assert\Valid]
    #[Assert\All([
        new Assert\Type(type: IngredientTag::class, message: "Cette valeur n'est pas du bon type."),
    ])]
    private Collection $tags;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    private ?QuantityType $defaultQuantityType = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: StockIngredient::class)]
    private Collection $stockIngredients;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->stockIngredients = new ArrayCollection();
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
        return array_merge(
            ['Je ne sais pas' => null],
            array_flip(self::WHERE_TO_KEEP_OPTIONS)
        );
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
     * @return Collection<int, IngredientTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(IngredientTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(IngredientTag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getDefaultQuantityType(): ?QuantityType
    {
        return $this->defaultQuantityType;
    }

    public function setDefaultQuantityType(?QuantityType $defaultQuantityType): self
    {
        $this->defaultQuantityType = $defaultQuantityType;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, StockIngredient>
     */
    public function getStockIngredients(): Collection
    {
        return $this->stockIngredients;
    }

    public function addStockIngredient(StockIngredient $stockIngredient): self
    {
        if (!$this->stockIngredients->contains($stockIngredient)) {
            $this->stockIngredients->add($stockIngredient);
            $stockIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removeStockIngredient(StockIngredient $stockIngredient): self
    {
        if ($this->stockIngredients->removeElement($stockIngredient)) {
            // set the owning side to null (unless already changed)
            if ($stockIngredient->getIngredient() === $this) {
                $stockIngredient->setIngredient(null);
            }
        }

        return $this;
    }
}
