<?php

declare(strict_types=1);

namespace PWP\Controller;

use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Slim\Flash\Messages;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PWP\Model\User;
use PWP\Model\UserRepository;

use DateTime;
use Exception;

final class SignInController

{
    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private Messages $flash
    ) {}

    public function showForm(Request $request, Response $response): Response
    {

        $messages = $this->flash->getMessages();
        $AuthenticationMiddlewareMessages = $messages['AuthenticationMiddlewareMessage'] ?? [];

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

       
        return $this->twig->render($response, 'sign-in.twig', [
            'formAction' => $routeParser->urlFor("sign-in"),
            'formMethod' => "POST",
            'AuthenticationMiddlewareMessage' => !empty($AuthenticationMiddlewareMessages) ? $AuthenticationMiddlewareMessages[0] : null

            
        ]);
    }

    public function handleFormSubmission(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
    
        $errors = [];
    
        // Validate email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'The email address is not valid.';
        } elseif (strpos($data['email'], '@salle.url.edu') === false) { 
            $errors['email'] = 'Only emails from the domain @salle.url.edu are accepted.';
        } elseif ( !($this->userRepository->checkIfUserExists($data['email'])) ) {
            $errors['email'] = 'User with this email address does not exist.';
        }
        
        // Validate password
        if (empty($data['password']) || strlen($data['password']) < 7) {
            $errors['password'] = 'The password must contain at least 7 characters.';
        } elseif (!preg_match('/[A-Z]/', $data['password']) || !preg_match('/[a-z]/', $data['password']) || !preg_match('/[0-9]/', $data['password'])) {
            $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
        }  elseif ( !($this->userRepository->checkIfUserExistsAndPasswordMatches($data['email'], $data['password'] )) ) {
            $errors['password'] = 'Your email and/or password are incorrect.';
        }
    
        // If there are no errors, proceed with the sign in
        if (empty($errors)) {
            
            try {
               
                
                $_SESSION['email'] = $data['email'];
               

                
                // Redirect to success page
                $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                return $response->withHeader('Location',  $routeParser->urlFor("home"))->withStatus(302);
            } catch (Exception $exception) {
                // Handle exceptions
                $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                return $response->withHeader('Location',  $routeParser->urlFor("visits"))->withStatus(401);
            }
        } else {
            // Render the form again with errors if there are any
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
    
            return $this->twig->render($response, 'sign-in.twig', [
                'formErrors' => $errors,
                'formData' => $data,
                'formAction' => $routeParser->urlFor("sign-in"),
                'formMethod' => "POST"
            ]);
        }
    }
    }
