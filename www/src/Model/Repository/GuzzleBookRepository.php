<?php

declare(strict_types=1);

namespace PWP\Model\Repository; 

use GuzzleHttp\Client;
use PWP\Model\Book;
use PWP\Model\BookRepository;

final class GuzzleBookRepository implements BookRepository {
    private $apiUrl;
    private $apiKey;

    public function __construct() {
        $this->apiUrl = "https://openlibrary.org/";
    }

    public function getWorkByISBN($ISBN): Book {
        $client = new Client();
        
        $response = $client->request('GET', $this->apiUrl . 'isbn' . '/' . $ISBN . '.json', []);
        $responseBody = $response->getBody()->__toString();
        $responseArray = json_decode($responseBody, true);

        $book = new Book(
            $responseArray['title'],
            $responseArray['authors'][0]['key'],
            '',
            $responseArray['number_of_pages'],
            ''
        );

        return $book;
    }
}
