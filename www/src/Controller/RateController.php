<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use PWP\Model\BookRepository;
use PWP\Model\UserRepository;
use PWP\Model\RateRepository;

use Slim\Routing\RouteContext;


final class RateController
{
    private Twig $twig;
    private Messages $flash;
    private BookRepository $bookRepository; 
    private UserRepository $userRepository; 
    private RateRepository $rateRepository; 



    public function __construct(Twig $twig, Messages $flash, BookRepository $bookRepository, UserRepository $userRepository, RateRepository $rateRepository)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->bookRepository = $bookRepository;
        $this->rateRepository = $rateRepository;
        $this->userRepository = $userRepository;


    }

    public function putRating(Request $request, Response $response, array $args): Response
    {
        
        $data = $request->getParsedBody();
        

        $id = $args['id'] ?? '';
        
        $book = $this->bookRepository->getBookById((int)$id);
        $user = $this->userRepository->getUserbyEmail($_SESSION['email']);
        


        $this->rateRepository->putRating($user->id(),(int)$id, (int)$data['rating']);
        
        
        
        
       // Redirect to the book details page after adding the review
       $routeParser = RouteContext::fromRequest($request)->getRouteParser();
       return $response->withHeader('Location', $routeParser->urlFor('details', ['id' => $id]))->withStatus(302);

    }

    public function deleteRating(Request $request, Response $response, array $args): Response
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
