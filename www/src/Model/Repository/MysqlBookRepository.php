<?php

declare(strict_types=1);

namespace PWP\Model\Repository;

use PDO;
use PWP\Model\Book;
use PWP\Model\BookRepository;
use DateTime;

final class MysqlBookRepository implements BookRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $database;

    public function __construct(PDO $database) {
        $this->database = $database;
    }

    public function save(Book $book): void {
        $query = <<<'QUERY'
        INSERT INTO books(title, author, description, page_number, cover_image, created_at, updated_at)
        VALUES(:title, :author, :description, :page_number, :cover_image, :created_at, :updated_at)
        QUERY;
        
        $statement = $this->database->prepare($query);

        $title = $book->getTitle();
        $author = $book->getAuthor();
        $description = $book->getDescription();
        $pageNumber = $book->getPageNumber();
        $coverImage = $book->getCoverImage();
        $createdAt = $book->getCreatedAt()->format(self::DATE_FORMAT);
        $updatedAt = $book->getUpdatedAt()->format(self::DATE_FORMAT);

        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':author', $author, PDO::PARAM_STR);
        $statement->bindParam(':description', $description, PDO::PARAM_STR);
        $statement->bindParam(':page_number', $pageNumber, PDO::PARAM_INT);
        $statement->bindParam(':cover_image', $coverImage, PDO::PARAM_STR);
        $statement->bindParam(':created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }


    public function getAllBooks(): array {

        $books = [];
        $query = <<<'QUERY'
            SELECT * FROM books
        QUERY;

        $statement = $this->database->query($query);

        
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $createdAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['created_at']);
            $updatedAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['updated_at']);

            $book = new Book(
                $row['title'],
                $row['author'],
                $row['description'],
                (int)$row['page_number'],
                $row['cover_image'],
                $createdAt,
                $updatedAt
            );

            $books[] = $book;
        }

        return $books;
}

public function getBookById(int $id): Book {
    $query = <<<'QUERY'
        SELECT * FROM books WHERE id = :id
    QUERY;

    $statement = $this->database->prepare($query);
    $statement->bindParam(':id', $id, PDO::PARAM_STR);
    $statement->execute();

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return new Book(
            '',
            '',
            '',
            0,
            '',
            new DateTime(),
            new DateTime()
        );
    }

    $createdAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['created_at']);
    $updatedAt = DateTime::createFromFormat(self::DATE_FORMAT, $row['updated_at']);

    return new Book(
        $row['title'],
        $row['author'],
        $row['description'],
        (int)$row['page_number'],
        $row['cover_image'],
        $createdAt,
        $updatedAt
    );
}

public function getIdByTitle(string $title): int {
    $query = <<<'QUERY'
        SELECT id FROM books WHERE title = :title
    QUERY;

    $statement = $this->database->prepare($query);
    $statement->bindParam(':title', $title, PDO::PARAM_STR);
    $statement->execute();

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return 0; // Book not found
    }

    return $row['id'];
}



    

    
}
