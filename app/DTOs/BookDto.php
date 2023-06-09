<?php

namespace App\DTOs;

class BookDto
{
    private string $isbn = '';

    private string $title = '';

    private string $description = '';

    private array $authorIds = [];

    public function getIsbn(): int
    {
        return $this->isbn;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthorIds(): array
    {
        return $this->authorIds;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;
        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function setAuthorIds(array $authorIds): static
    {
        $this->authorIds = $authorIds;
        return $this;
    }
}
