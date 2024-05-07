<?php

declare(strict_types=1);


namespace PWP\Model;
use PWP\Model\Book;

interface BookRepository
{
 
    public function save(Book $book): void;

    public function getAllBooks(): array;

    public function getBookById(int $id): Book;

    public function getIdByTitle(string $title): int;
}