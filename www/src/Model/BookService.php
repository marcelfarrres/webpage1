<?php

declare(strict_types=1);

namespace PWP\Model;
use PWP\Model\Book;

interface BookService
{
    public function getBookByISBN(string $ISBN): array;
    //return [ 'book' => $book, 'workKey' => $responseArray['works'][0]['key'] ];

    public function updateBookByWorkId(string $workId, Book $book): array;
    //return [ 'book' => $book, 'authorKey' => $responseArray['authors'][0]['author']['key'] ];
    
    public function updateBookByAuthorId(string $authorId, Book $book): Book;
    // return $book;
}