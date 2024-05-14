<?php

declare(strict_types=1);

namespace PWP\Model\Repository;

use PDO;
use PWP\Model\Forum;
use PWP\Model\ForumRepository;
use DateTime;

final class MysqlForumRepository implements ForumRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $database;

    public function __construct(PDO $database) {
        $this->database = $database;
    }

    public function save(Forum $forum): void {
        $query = <<<'QUERY'
        INSERT INTO forums(title, description, created_at, updated_at)
        VALUES(:title, :description, :created_at, :updated_at)
        QUERY;
        
        $statement = $this->database->prepare($query);

        $title = $forum->getTitle();
        $description = $forum->getDescription();
        $createdAt = $forum->getCreatedAt()->format(self::DATE_FORMAT);
        $updatedAt = $forum->getUpdatedAt()->format(self::DATE_FORMAT);

        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':description', $description, PDO::PARAM_STR);
        $statement->bindParam(':created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }

    public function getAllForums(): array {

        $forums = [];
        $query = <<<'QUERY'
            SELECT * FROM forums
        QUERY;

        $statement = $this->database->query($query);

        
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $createdAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['created_at']);
            $updatedAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['updated_at']);

            $forum = new Forum(
                $row['title'],
                $row['description'],
                $createdAt,
                $updatedAt
            );

            $forum->setId( (int)$row['id']);

            $forums[] = $forum;
        }

        return $forums;
    }

    public function getForumById(int $id): Forum {
        $query = <<<'QUERY'
            SELECT * FROM forums WHERE id = :id
        QUERY;

        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return new Forum(
                '',
                '',
                new DateTime(),
                new DateTime()
            );
        }

        $createdAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['created_at']);
        $updatedAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['updated_at']);

        $forum = new Forum(
            $row['title'],
            $row['description'],
            $createdAt,
            $updatedAt
        );

        $forum->setId($row['id']);
        return $forum;
    }

}
