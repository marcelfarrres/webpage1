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
            
        } else if(!$this->userRepository->isUsernameUnique($data['username'])){
            $errors['username'] = 'Username already in use :(';
           
        } else{
            $this->userRepository->updateUserUsername($_SESSION['email'], $data['username'] );
        }

        //get the profile User Updated
        
        if (isset($files['profile-picture'])){
            
            if ($files['profile-picture']->getError() === UPLOAD_ERR_OK) {
                
                $uploadDir = __DIR__ . '/../../public/uploads/';
                $uploadFile = $uploadDir . basename($files['profile-picture']->getClientFilename());

        
                if (move_uploaded_file($files['profile-picture']->getStream()->getMetadata('uri'), $uploadFile)) 
                    {
                    if($this->userRepository->updateProfileImage($_SESSION['email'], basename($files['profile-picture']->getClientFilename())))
                    {
                        //all good
                    }else{
                        $errors['profile_picture'] = 'Error storing the profile picture in the Database';
                    }

                } else {
                    $errors['profile_picture'] = 'Error occurred while uploading profile picture';
                }
            } else {
                
                
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
