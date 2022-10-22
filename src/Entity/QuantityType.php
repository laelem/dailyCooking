<?php

namespace App\Entity;

use App\Repository\QuantityTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuantityTypeRepository::class)]
class QuantityType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du type de quantité est requis.", normalizer: 'trim')]
    #[Assert\Length(max: 255, maxMessage: "Le nom du type de quantité ne peut excéder 255 caractères.")]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le nom du type de quantité au pluriel ne peut pas être vide.", allowNull: true, normalizer: 'trim')]
    #[Assert\Length(max: 255, maxMessage: "Le nom du type de quantité au pluriel ne peut excéder 255 caractères.")]
    private ?string $pluralName = null;

    #[ORM\OneToMany(mappedBy: 'defaultQuantityType', targetEntity: Ingredient::class)]
    private Collection $ingredients;

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

    public function getPluralName(): ?string
    {
        return $this->pluralName;
    }

    public function setPluralName(?string $pluralName): self
    {
        $this->pluralName = $pluralName;

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
            $ingredient->setDefaultQuantityType($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getDefaultQuantityType() === $this) {
                $ingredient->setDefaultQuantityType(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
