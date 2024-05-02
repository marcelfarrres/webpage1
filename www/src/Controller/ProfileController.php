<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Routing\RouteContext;


use PWP\Model\UserRepository;

final class ProfileController
{
    private Twig $twig;
    private Messages $flash;
    private UserRepository $userRepository; 

    public function __construct(Twig $twig, Messages $flash, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->userRepository = $userRepository;
    }

    public function showProfile(Request $request, Response $response): Response
    {
        
        
        $user = $this->userRepository->getUserbyEmail( $_SESSION['email']);
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render($response, 'profile.twig', [
            'user' => $user,
            'formAction' => $routeParser->urlFor("profile"),
            'formMethod' => "POST"
        
        ]);
    }

    public function updateProfile(Request $request, Response $response): Response
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
