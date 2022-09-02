<?php

namespace App\Entity;

use App\Repository\IngredientCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientCategoryRepository::class)]
class IngredientCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories2')]
    private ?self $category1 = null;

    #[ORM\OneToMany(mappedBy: 'category1', targetEntity: self::class)]
    private Collection $categories2;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories3')]
    private ?self $category2 = null;

    #[ORM\OneToMany(mappedBy: 'category2', targetEntity: self::class)]
    private Collection $categories3;

    #[ORM\OneToMany(mappedBy: 'category1', targetEntity: Ingredient::class)]
    private Collection $category1Ingredients;

    #[ORM\OneToMany(mappedBy: 'category2', targetEntity: Ingredient::class)]
    private Collection $category2Ingredients;

    #[ORM\OneToMany(mappedBy: 'category3', targetEntity: Ingredient::class)]
    private Collection $category3Ingredients;

    public function __construct()
    {
        $this->categories2 = new ArrayCollection();
        $this->categories3 = new ArrayCollection();
        $this->category1Ingredients = new ArrayCollection();
        $this->category2Ingredients = new ArrayCollection();
        $this->category3Ingredients = new ArrayCollection();
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

    public function getCategory1(): ?self
    {
        return $this->category1;
    }

    public function setCategory1(?self $category1): self
    {
        $this->category1 = $category1;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories2(): Collection
    {
        return $this->categories2;
    }

    public function addCategories2(self $categories2): self
    {
        if (!$this->categories2->contains($categories2)) {
            $this->categories2->add($categories2);
            $categories2->setCategory1($this);
        }

        return $this;
    }

    public function removeCategories2(self $categories2): self
    {
        if ($this->categories2->removeElement($categories2)) {
            // set the owning side to null (unless already changed)
            if ($categories2->getCategory1() === $this) {
                $categories2->setCategory1(null);
            }
        }

        return $this;
    }

    public function getCategory2(): ?self
    {
        return $this->category2;
    }

    public function setCategory2(?self $category2): self
    {
        $this->category2 = $category2;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories3(): Collection
    {
        return $this->categories3;
    }

    public function addCategories3(self $categories3): self
    {
        if (!$this->categories3->contains($categories3)) {
            $this->categories3->add($categories3);
            $categories3->setCategory2($this);
        }

        return $this;
    }

    public function removeCategories3(self $categories3): self
    {
        if ($this->categories3->removeElement($categories3)) {
            // set the owning side to null (unless already changed)
            if ($categories3->getCategory2() === $this) {
                $categories3->setCategory2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getCategory1Ingredients(): Collection
    {
        return $this->category1Ingredients;
    }

    public function addCategory1Ingredient(Ingredient $category1Ingredient): self
    {
        if (!$this->category1Ingredients->contains($category1Ingredient)) {
            $this->category1Ingredients->add($category1Ingredient);
            $category1Ingredient->setCategory1($this);
        }

        return $this;
    }

    public function removeCategory1Ingredient(Ingredient $category1Ingredient): self
    {
        if ($this->category1Ingredients->removeElement($category1Ingredient)) {
            // set the owning side to null (unless already changed)
            if ($category1Ingredient->getCategory1() === $this) {
                $category1Ingredient->setCategory1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getCategory2Ingredients(): Collection
    {
        return $this->category2Ingredients;
    }

    public function addCategory2Ingredient(Ingredient $category2Ingredient): self
    {
        if (!$this->category2Ingredients->contains($category2Ingredient)) {
            $this->category2Ingredients->add($category2Ingredient);
            $category2Ingredient->setCategory2($this);
        }

        return $this;
    }

    public function removeCategory2Ingredient(Ingredient $category2Ingredient): self
    {
        if ($this->category2Ingredients->removeElement($category2Ingredient)) {
            // set the owning side to null (unless already changed)
            if ($category2Ingredient->getCategory2() === $this) {
                $category2Ingredient->setCategory2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getCategory3Ingredients(): Collection
    {
        return $this->category3Ingredients;
    }

    public function addCategory3Ingredient(Ingredient $category3Ingredient): self
    {
        if (!$this->category3Ingredients->contains($category3Ingredient)) {
            $this->category3Ingredients->add($category3Ingredient);
            $category3Ingredient->setCategory3($this);
        }

        return $this;
    }

    public function removeCategory3Ingredient(Ingredient $category3Ingredient): self
    {
        if ($this->category3Ingredients->removeElement($category3Ingredient)) {
            // set the owning side to null (unless already changed)
            if ($category3Ingredient->getCategory3() === $this) {
                $category3Ingredient->setCategory3(null);
            }
        }

        return $this;
    }
}
