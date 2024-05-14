<?php

declare(strict_types=1);


namespace PWP\Model;
use PWP\Model\Post;

interface PostRepository
{
    public function save(Post $post): void;
    public function getAllPosts(): array;
    public function getPostById(int $id): Post;

    
}