<?php

declare(strict_types=1);

namespace PWP\Model\Repository;

use PDO;
use PWP\Model\Rate;
use DateTime;
use PWP\Model\RateRepository;

final class MysqlRateRepository implements RateRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function putRating(int $userId, int $bookId, int $rating): void
    {
        $query = <<<'QUERY'
            INSERT INTO ratings (user_id, book_id, rating, created_at, updated_at)
            VALUES (:userId, :bookId, :rating, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE rating = :rating, updated_at = CURRENT_TIMESTAMP
        QUERY;

        $statement = $this->database->prepare($query);

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $statement->bindParam(':rating', $rating, PDO::PARAM_INT);

        $statement->execute();
    }

    public function deleteRating(int $userId, int $bookId): void
    {
        $query = <<<'QUERY'
            DELETE FROM ratings WHERE user_id = :userId AND book_id = :bookId
        QUERY;

        $statement = $this->database->prepare($query);

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':bookId', $bookId, PDO::PARAM_INT);

        $statement->execute();
    }

    public function getRating(int $userId, int $bookId): ?Rate
    {
        $query = <<<'QUERY'
            SELECT * FROM ratings WHERE user_id = :userId AND book_id = :bookId
        QUERY;

        $statement = $this->database->prepare($query);

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':bookId', $bookId, PDO::PARAM_INT);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null; // Rating not found
        }

        $createdAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['created_at']);
        $updatedAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['updated_at']);

        return new Rate(
            (int)$row['user_id'],
            (int)$row['book_id'],
            (int)$row['rating'],
            $createdAt,
            $updatedAt
        );
    }
}
