<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PWP\Model\RateRepository;
use PWP\Model\ReviewRepository;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use PWP\Model\BookRepository;
use PWP\Model\UserRepository;

use Slim\Routing\RouteContext;


final class DetailsController
{
    private Twig $twig;
    private Messages $flash;
    private BookRepository $bookRepository; 
    private ReviewRepository $reviewRepository;
    private RateRepository $rateRepository;

    private UserRepository $userRepository;


    public function __construct(Twig $twig, Messages $flash, BookRepository $bookRepository, ReviewRepository $reviewRepository, 
    UserRepository $userRepository, RateRepository $rateRepository)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->bookRepository = $bookRepository;
        $this->reviewRepository = $reviewRepository;
        $this->userRepository = $userRepository;
        $this->rateRepository = $rateRepository;



    }

    public function showDetails(Request $request, Response $response, array $args): Response
    {
        $id_book = $args['id'] ?? '';
        
      
        $user = $this->userRepository->getUserbyEmail($_SESSION['email']);
        $book = $this->bookRepository->getBookById((int)$id_book);
        $review = $this->reviewRepository->getReview($user->id(), (int)$id_book);
        $rate = $this->rateRepository->getRating($user->id(), (int)$id_book);
        
        if (!$book) {
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location',  $routeParser->urlFor("home"))->withStatus(302);
        }
        
        return $this->twig->render($response, 'details.twig', [
            'book' => $book,
            'review' => $review->getReviewText(),
            'rate' => $rate->getRating()
        ]);

    }
    


}
