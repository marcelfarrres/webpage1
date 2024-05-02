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
        
        $books = [];
        //TODO: MysqlBookRepository instead of mocking the books
        array_push($books, new Book('title1', 'author1', ' ', 0, ' '),new Book('title2', 'author2', ' ', 1, ' '));
        
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render($response, 'catalogue.twig', [
            'books' => $books,
            'formAction' => $routeParser->urlFor("catalogue"),
            'formMethod' => "POST"
        
        ]);
    }

    public function update(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $files = $request->getUploadedFiles();
        
        $user = $this->userRepository->getUserbyEmail($_SESSION['email']);

        $errors = [];
        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        } else if(!$this->userRepository->isUsernameUnique($data['username'])){
            $errors['username'] = 'Username already in use :(';
           
        } else{
            $this->userRepository->updateUserUsername($_SESSION['email'], $data['username'] );
        }

        if (isset($files['profile-picture'])){
            if ( $files['profile-picture']->getError() === UPLOAD_ERR_OK) {
                //TODO how to persist the image
            } else {
                $errors['profile_picture'] = 'Profile picture upload error';
            }
        }
        
        //Get the user Updated:
        $user = $this->userRepository->getUserbyEmail($_SESSION['email']);
        
        if (!empty($errors)) {
           
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();

            return $this->twig->render($response, 'profile.twig', [
                'formErrors' => $errors,
                'user' => $user,
                'formAction' => $routeParser->urlFor("profile"),
                'formMethod' => "POST"
            ]);
           
        }else{
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location',  $routeParser->urlFor("home"))->withStatus(302);

        }

        

        
    }
}
