<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PWP\Model\Book;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Routing\RouteContext;
use PWP\Model\BookRepository;
use DateTime;


final class CatalogueController
{
    private Twig $twig;
    private Messages $flash;
    private BookRepository $bookRepository; 

    public function __construct(Twig $twig, Messages $flash, BookRepository $bookRepository)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->bookRepository = $bookRepository;
    }

    public function showBooks(Request $request, Response $response): Response
    {
        
        $books = $this->bookRepository->getAllBooks();
            
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render($response, 'catalogue.twig', [
            'books' => $books,
            'formAction' => $routeParser->urlFor("catalogue"),
            'formMethod' => "POST",
            'formActionDetails' => $routeParser->urlFor("details", ['id' => '1']),
            'formMethodDetails' => "GET"
        
        ]);
    }

    public function persistBook(Request $request, Response $response): Response
{
    $data = $request->getParsedBody();
    $files = $request->getUploadedFiles();
    
    // Create a new Book object
    $book = new Book(
        $data['title'],
        $data['author'],
        $data['description'],
        (int)$data['numberOfPages'],
        $data['coverImageUrl'] ?? null, // Use null coalescing operator to handle optional cover image URL
        new DateTime(), // Assuming current date and time for createdAt
        new DateTime()  // Assuming current date and time for updatedAt
    );
    
    // Save the book
    $this->bookRepository->save($book);
    
    // Redirect to the catalogue page
    $routeParser = RouteContext::fromRequest($request)->getRouteParser();
    return $response->withHeader('Location', $routeParser->urlFor('catalogue'))->withStatus(302);
}


}
