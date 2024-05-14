<?php

declare(strict_types=1);


namespace PWP\Model;
use PWP\Model\Rate;

interface ReviewRepository
{
    

    public function addReview(int $userId, int $bookId, string $reviewText): void;
    public function deleteReview(int $userId, int $bookId): void;
    public function getReview(int $userId, int $bookId): ?Review;

    
}