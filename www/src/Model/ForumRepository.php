<?php

declare(strict_types=1);


namespace PWP\Model;
use PWP\Model\Forum;

interface ForumRepository
{
    public function save(Forum $forum): void;

    public function getAllForums(): array;

    public function getForumById(int $id): Forum;

    
}