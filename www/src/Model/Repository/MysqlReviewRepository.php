<?php

declare(strict_types=1);

namespace PWP\Model\Repository;

use PDO;
use PWP\Model\Review;
use DateTime;
use PWP\Model\ReviewRepository;

final class MysqlReviewRepository implements ReviewRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function addReview(int $userId, int $bookId, string $reviewText): void
    {
        $query = <<<'QUERY'
            INSERT INTO reviews (user_id, book_id, review_text, created_at, updated_at)
            VALUES (:userId, :bookId, :reviewText, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE review_text = :reviewText, updated_at = CURRENT_TIMESTAMP
        QUERY;


        $statement = $this->database->prepare($query);

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $statement->bindParam(':reviewText', $reviewText, PDO::PARAM_STR);

        $statement->execute();
    }

    public function deleteReview(int $userId, int $bookId): void
    {
        $query = <<<'QUERY'
            DELETE FROM reviews WHERE user_id = :userId AND book_id = :bookId
        QUERY;

        $statement = $this->database->prepare($query);

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':bookId', $bookId, PDO::PARAM_INT);

        $statement->execute();
    }

    public function getReview(int $userId, int $bookId): ?Review
    {
        $query = <<<'QUERY'
            SELECT * FROM reviews WHERE user_id = :userId AND book_id = :bookId
        QUERY;

        $statement = $this->database->prepare($query);

        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':bookId', $bookId, PDO::PARAM_INT);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null; // Review not found
        }

        $createdAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['created_at']);
        $updatedAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['updated_at']);

        return new Review(
            (int)$row['user_id'],
            (int)$row['book_id'],
            $row['review_text'],
            $createdAt,
            $updatedAt
        );
    }
}
