<?php

declare(strict_types=1);

namespace PWP\Model\Service;

use GuzzleHttp\Client;
use PWP\Model\Book;
use PWP\Model\BookService;
use DateTime;

final class AjaxForumService 
{
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = "https://openlibrary.org";
    }

    // Function to fetch books from the API
    public function fetchBooks()
    {
        $client = new Client();
        $response = $client->request('GET', $this->apiUrl . '/api/books', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    // Function to create a new book via API
    public function createBook(Book $book)
    {
        $client = new Client();
        $response = $client->request('POST', $this->apiUrl . '/api/books', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'description' => $book->getDescription(),
                'pageNumber' => $book->getPageNumber(),
                'coverImage' => $book->getCoverImage(),
                'createdAt' => $book->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $book->getUpdatedAt()->format('Y-m-d H:i:s')
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    // Function to fetch a book by ID from the API
    public function fetchBookById(int $id)
    {
        $client = new Client();
        $response = $client->request('GET', $this->apiUrl . '/api/books/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    // Function to delete a book by ID via API
    public function deleteBook(int $id)
    {
        $client = new Client();
        $response = $client->request('DELETE', $this->apiUrl . '/api/books/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey
            ]
        ]);

        return $response->getStatusCode() === 200;
    }
}
