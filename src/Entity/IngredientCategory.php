<?php

namespace App\Entity;

use App\Repository\IngredientCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: IngredientCategoryRepository::class)]
class IngredientCategory
{
    const POSITION_FIRST = 'first';
    const POSITION_LAST = 'last';
    const POSITION_AFTER = 'after';

    const POSITION_ENUM_CHOICES = [
        'En premier'         => self::POSITION_FIRST,
        'En dernier'         => self::POSITION_LAST,
        'Après la catégorie' => self::POSITION_AFTER,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de la catégorie est requise.", normalizer: 'trim')]
    #[Assert\Length(max: 255, maxMessage: "Le nom de la catégorie ne peut excéder 255 caractères.")]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Ingredient::class)]
    private Collection $ingredients;

    #[ORM\Column(options: ["unsigned" => true])]
    private ?float $position = null;

    #[Assert\Choice(choices: self::POSITION_ENUM_CHOICES, message: "Cette option n'est pas valide.")]
    #[Assert\NotNull(message: "Vous devez indiquer une position.")]
    private ?string $positionEnum = self::POSITION_LAST;

    #[Assert\Type(type: IngredientCategory::class, message: "Cette valeur n'est pas du bon type.", groups: ['categoryBasedPosition'])]
    #[Assert\NotNull(message: "Vous devez indiquer une catégorie.", groups: ['categoryBasedPosition'])]
    private ?IngredientCategory $beforeCategory = null;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
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
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setCategory($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getCategory() === $this) {
                $ingredient->setCategory(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?float
    {
        return $this->position;
    }

    public function setPosition(float $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getBeforeCategory(): ?IngredientCategory
    {
        return $this->beforeCategory;
    }

    public function setBeforeCategory(?IngredientCategory $beforeCategory): IngredientCategory
    {
        $this->beforeCategory = $beforeCategory;

        return $this;
    }

    public function getPositionEnum(): ?string
    {
        return $this->positionEnum;
    }

    public function setPositionEnum(?string $positionEnum): IngredientCategory
    {
        $this->positionEnum = $positionEnum;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getId() && $this->getBeforeCategory() && $this->getBeforeCategory()->getId() == $this->getId()) {
            $context->buildViolation("Cette catégorie n'est pas valide.")
                ->atPath('beforeCategory')
                ->addViolation();
        }
    }
}
