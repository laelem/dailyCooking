<?php

namespace App\Entity;

use App\Repository\StockIngredientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StockIngredientRepository::class)]
class StockIngredient
{
    const STOCK_STATUS_OPTIONS = [
        'soon_out_of_stock' => 'Bientôt épuisé(e)',
        'out_of_stock' => 'Épuisé(e)',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stockIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "L'ingrédient est requis.")]
    #[Assert\Type(type: Ingredient::class, message: "Cette valeur n'est pas du bon type.")]
    #[Assert\Valid]
    private ?Ingredient $ingredient = null;

    #[ORM\ManyToOne]
    #[Assert\Type(type: QuantityType::class, message: "Cette valeur n'est pas du bon type.")]
    #[Assert\Valid]
    private ?QuantityType $quantityType = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true, options: ["unsigned" => true])]
    private ?string $quantityNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le commentaire ne peut pas être vide.", allowNull: true, normalizer: 'trim')]
    #[Assert\Length(max: 255, maxMessage: "Le commentaire ne peut excéder 255 caractères.")]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expiresAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Choice(callback: 'getStockStatusOptions', message: 'Cette option n\'est pas valide.')]
    private ?string $stockStatus = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantityType(): ?QuantityType
    {
        return $this->quantityType;
    }

    public function setQuantityType(?QuantityType $quantityType): self
    {
        $this->quantityType = $quantityType;

        return $this;
    }

    public function getQuantityNumber(): ?string
    {
        return $this->quantityNumber;
    }

    public function setQuantityNumber(?string $quantityNumber): self
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

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getStockStatus(): ?string
    {
        return $this->stockStatus;
    }

    public function setStockStatus(?string $stockStatus): self
    {
        $this->stockStatus = $stockStatus;

        return $this;
    }

    public static function getStockStatusOptions(): array
    {
        return array_merge(
            ['-' => null],
            array_flip(self::STOCK_STATUS_OPTIONS)
        );
    }

    public function getStockStatusLabel(): ?string
    {
        if ($this->stockStatus) {
            return self::STOCK_STATUS_OPTIONS[$this->stockStatus];
        }

        return null;
    }
}
