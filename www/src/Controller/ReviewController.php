<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use PWP\Model\BookRepository;
use PWP\Model\UserRepository;
use PWP\Model\ReviewRepository;

use Slim\Routing\RouteContext;


final class ReviewController
{
    private Twig $twig;
    private Messages $flash;
    private BookRepository $bookRepository; 
    private UserRepository $userRepository; 
    private ReviewRepository $reviewRepository; 



    public function __construct(Twig $twig, Messages $flash, BookRepository $bookRepository, UserRepository $userRepository, ReviewRepository $reviewRepository)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->bookRepository = $bookRepository;
        $this->reviewRepository = $reviewRepository;
        $this->userRepository = $userRepository;
    }

    public function putReview(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $id = $args['id'] ?? '';
        
        $book = $this->bookRepository->getBookById((int)$id);
        $user = $this->userRepository->getUserbyEmail($_SESSION['email']);
       
        $this->reviewRepository->addReview($user->id(), (int)$id, $data['reviewText']);
        
        // Redirect to the book details page after adding the review
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response->withHeader('Location', $routeParser->urlFor('details', ['id' => $id]))->withStatus(302);
    }

    public function deleteReview(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? '';
        
        $book = $this->bookRepository->getBookById((int)$id);
        
        if (!$book) {
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location',  $routeParser->urlFor("home"))->withStatus(302);
        }
        
        // Delete the review logic here
        
        // Redirect to the book details page after deleting the review
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response->withHeader('Location', $routeParser->urlFor('book_details', ['id' => $id]))->withStatus(302);
    }

}
