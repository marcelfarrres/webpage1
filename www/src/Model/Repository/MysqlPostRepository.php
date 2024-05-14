<?php

declare(strict_types=1);

namespace PWP\Model\Repository;

use PDO;
use PWP\Model\Post;
use PWP\Model\PostRepository;
use DateTime;

final class MysqlPostRepository implements PostRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function save(Post $post): void
    {
        $query = <<<'QUERY'
        INSERT INTO posts(user_id, forum_id, title, contents, created_at, updated_at)
        VALUES(:user_id, :forum_id, :title, :contents, :created_at, :updated_at)
        QUERY;

        $statement = $this->database->prepare($query);

        $userId = $post->getUserId();
        $forumId = $post->getForumId();
        $title = $post->getTitle();
        $contents = $post->getContents();
        $createdAt = $post->getCreatedAt()->format(self::DATE_FORMAT);
        $updatedAt = $post->getUpdatedAt()->format(self::DATE_FORMAT);

        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindParam(':forum_id', $forumId, PDO::PARAM_INT);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':contents', $contents, PDO::PARAM_STR);
        $statement->bindParam(':created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }

    public function getAllPosts(): array
    {
        $posts = [];
        $query = <<<'QUERY'
            SELECT * FROM posts
        QUERY;

        $statement = $this->database->query($query);

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $createdAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['created_at']);
            $updatedAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['updated_at']);

            $post = new Post(
                (int)$row['user_id'],
                (int)$row['forum_id'],
                $row['title'],
                $row['contents'],
                $createdAt,
                $updatedAt
            );

            $post->setId((int)$row['id']);

            $posts[] = $post;
        }

        return $posts;
    }

    public function getPostById(int $id): Post
    {
        $query = <<<'QUERY'
            SELECT * FROM posts WHERE id = :id
        QUERY;

        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return new Post(
                0,
                0,
                '',
                '',
                new DateTime(),
                new DateTime()
            );
        }

        $createdAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['created_at']);
        $updatedAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['updated_at']);

        $post = new Post(
            (int)$row['user_id'],
            (int)$row['forum_id'],
            $row['title'],
            $row['contents'],
            $createdAt,
            $updatedAt
        );

        $post->setId($row['id']);
        return $post;
    }

}