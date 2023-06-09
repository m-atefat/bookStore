<?php

namespace App\DTOs;

class BookReviewDto
{
    private int $userId;

    private int $review;

    private string $comment = '';

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getReview(): int
    {
        return $this->review;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function setReview(int $review): static
    {
        $this->review = $review;
        return $this;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }
}
