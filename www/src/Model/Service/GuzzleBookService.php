<?php

declare(strict_types=1);

namespace PWP\Model\Service; 

use GuzzleHttp\Client;
use PWP\Model\Book;
use PWP\Model\BookService;
use DateTime;


final class GuzzleBookService implements BookService{
    private $apiUrl;
    private $apiKey;

  

    public function __construct() {
        $this->apiUrl = "https://openlibrary.org";
    }


    public function getBookByISBN(string $ISBN): array {
        $client = new Client();
        
        $response = $client->request('GET', $this->apiUrl . '/' . 'isbn' . '/' . $ISBN . '.json', []);
        $responseBody = $response->getBody()->__toString();
        $responseArray = json_decode($responseBody, true);
    
        $book = new Book(
            $responseArray['title'],
            '',
            '',
            $responseArray['number_of_pages'] ?? 0,
            'https://covers.openlibrary.org/b/id/' . $responseArray['covers'][0] . '-L.jpg',
            new DateTime(),
            new DateTime()

        );
    
        // Return an array with both the book object and the key from the response
        return [
            'book' => $book,
            'workKey' => $responseArray['works'][0]['key'] 
        ];
    }
    

    public function updateBookByWorkId(string $workId, Book $book): array {
        $client = new Client();
        
        $response = $client->request('GET', $this->apiUrl . $workId . '.json', []);
        $responseBody = $response->getBody()->__toString();
        $responseArray = json_decode($responseBody, true);
       
        
        //$book->setDescription($responseArray['description']);

        return [
            'book' => $book,
            'authorKey' => $responseArray['authors'][0]['author']['key']
        ];
        
    }

    public function updateBookByAuthorId(string $authorId, Book $book): Book {
        $client = new Client();
        
        $response = $client->request('GET', $this->apiUrl . $authorId . '.json', []);
        $responseBody = $response->getBody()->__toString();
        $responseArray = json_decode($responseBody, true);
       

        $book->setAuthor($responseArray['name']);

        return $book;
        
    }

    
}
