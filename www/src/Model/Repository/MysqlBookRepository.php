<?php

declare(strict_types=1);

namespace PWP\Model\Repository;

use PDO;
use PWP\Model\Book;
use PWP\Model\BookRepository;
use DateTime;

final class MysqlBookRepository //implements BookRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function save(Book $book): void
    {
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

    public function getBookByISBN($ISBN): Book
    {
        $query = <<<'QUERY'
        SELECT * FROM books WHERE ISBN = :ISBN
        QUERY;

        $statement = $this->database->prepare($query);
        $statement->bindParam(':ISBN', $ISBN, PDO::PARAM_STR);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        // Create new book
        $book = new Book(
            $data['title'],
            $data['author'],
            $data['description'],
            $data['page_number'],
            $data['cover_image'],
            new DateTime($data['created_at']),
            new DateTime($data['updated_at'])
        );

        return $book;
    }

    // Implement other methods as needed...
}
