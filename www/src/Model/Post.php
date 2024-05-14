<?php

declare(strict_types=1);

namespace PWP\Model;

use DateTime;

final class Post
{
    private int $id;
    private int $userId;
    private int $forumId;
    private string $title;
    private ?string $contents;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        int $userId,
        int $forumId,
        string $title,
        ?string $contents,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = 0;
        $this->userId = $userId;
        $this->forumId = $forumId;
        $this->title = $title;
        $this->contents = $contents;
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

    public function getForumId(): int
    {
        return $this->forumId;
    }

    public function setForumId(int $forumId): void
    {
        $this->forumId = $forumId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function setContents(?string $contents): void
    {
        $this->contents = $contents;
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}