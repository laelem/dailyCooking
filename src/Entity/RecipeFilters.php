<?php

namespace App\Entity;

class RecipeFilters
{
    private ?string $title = null;

    public function __construct()
    {
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
}
