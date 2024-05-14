<?php

declare(strict_types=1);

namespace PWP\Model;

use DateTime;

final class Review
{
    private int $userId;
    private int $bookId;
    private string $reviewText;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        int $userId,
        int $bookId,
        string $reviewText,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->userId = $userId;
        $this->bookId = $bookId;
        $this->reviewText = $reviewText;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function setBookId(int $bookId): void
    {
        $this->bookId = $bookId;
    }

    public function getReviewText(): string
    {
        return $this->reviewText;
    }

    public function setReviewText(string $reviewText): void
    {
        $this->reviewText = $reviewText;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
