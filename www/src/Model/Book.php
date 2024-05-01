<?php

declare(strict_types=1);

namespace PWP\Model;

use DateTime;

final class Book
{
    private string $title;
    private string $author;
    private string $description;
    private int $pageNumber;
    private ?string $coverImage;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        string $title,
        string $author,
        string $description,
        int $pageNumber,
        ?string $coverImage = null,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->title = $title;
        $this->author = $author;
        $this->description = $description;
        $this->pageNumber = $pageNumber;
        $this->coverImage = $coverImage;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
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
