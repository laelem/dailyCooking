<?php

namespace App\Entity;

class AddRecipePortionNumber
{
    private ?int $portionNumber = null;

    public function __construct()
    {
    }

    public function getPortionNumber(): ?int
    {
        return $this->portionNumber;
    }

    public function setPortionNumber(?int $portionNumber): AddRecipePortionNumber
    {
        $this->portionNumber = $portionNumber;

        return $this;
    }
}
