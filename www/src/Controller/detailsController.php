<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use PWP\Model\BookRepository;
use Slim\Routing\RouteContext;


final class DetailsController
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

    public function showDetails(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? '';
        
        $book = $this->bookRepository->getBookById((int)$id);
        
        if (!$book) {
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location',  $routeParser->urlFor("home"))->withStatus(302);
        }
        
        
        return $this->twig->render($response, 'details.twig', [
            'book' => $book
        ]);

    }


}
