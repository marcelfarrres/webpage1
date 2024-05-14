<?php

declare(strict_types=1);

namespace PWP\Model;

use DateTime;

class Rate
{
    private int $userId;
    private int $bookId;
    private int $rating;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        int $userId,
        int $bookId,
        int $rating,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->userId = $userId;
        $this->bookId = $bookId;
        $this->rating = $rating;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
