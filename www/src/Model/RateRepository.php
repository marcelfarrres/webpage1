<?php

declare(strict_types=1);


namespace PWP\Model;
use PWP\Model\Rate;

interface RateRepository
{
    public function putRating(int $userId, int $bookId, int $rating): void;

    public function deleteRating(int $userId, int $bookId): void;

    public function getRating(int $userId, int $bookId): ?Rate;

    
}