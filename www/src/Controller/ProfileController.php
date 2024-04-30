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
    private UserRepository $userRepository; // Assuming you have a UserRepository

    public function __construct(Twig $twig, Messages $flash, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->userRepository = $userRepository;
    }

    public function showProfile(Request $request, Response $response): Response
    {
        // Assuming you have authenticated the user and retrieved their details
        
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

        // Validate and process form data...

        
        $errors = [];
        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        } else {
            // TODO check if username is unique 
            $this->userRepository->updateUserUsername($_SESSION['email'], $data['username'] );
        }

        if (isset($files['profile-picture']) && $files['profile-picture']->getError() === UPLOAD_ERR_OK) {
            //TODO how to persist the image
        } else {
            $errors['profile_picture'] = 'Profile picture upload error';
        }

        // If there are errors, redirect back to profile page with errors
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
            
            return $this->twig->render($response, 'home.twig', []);

        }

        

        
    }
}
