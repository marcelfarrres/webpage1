<?php

declare(strict_types=1);

namespace PWP\Controller;

use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PWP\Model\User;
use PWP\Model\UserRepository;

use DateTime;
use Exception;

final class SignUpController

{
    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository
    ) {}

    public function showForm(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render($response, 'sign-up.twig', [
            'formAction' => $routeParser->urlFor("sign-up"),
            'formMethod' => "POST"
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
        } elseif ($this->userRepository->checkIfUserExists($data['email'])) {
            $errors['email'] = 'The email address is already registered.';
        }
    
        // Validate password
        if (empty($data['password']) || strlen($data['password']) < 7) {
            $errors['password'] = 'The password must contain at least 7 characters.';
        } elseif (!preg_match('/[A-Z]/', $data['password']) || !preg_match('/[a-z]/', $data['password']) || !preg_match('/[0-9]/', $data['password'])) {
            $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
        }
    
        // Validate repeat password
        if ($data['password'] !== $data['repeatPassword']) {
            $errors['repeatPassword'] = 'Passwords do not match.';
        }
        if ($data['password'] !== $data['repeatPassword']) {
            $errors['password'] = 'Passwords do not match.';
        }
    
    
        // If there are no errors, proceed with user creation
        if (empty($errors)) {
            
            
            try {
                // Create new user
                $user = new User(
                    $data['email'],
                    $data['password'],
                    new DateTime(),
                    new DateTime()
                );
                
                // Save the user
                $this->userRepository->save($user);

    
                // Redirect to success page
                $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                return $response->withHeader('Location',  $routeParser->urlFor("sign-in"))->withStatus(302);
            } catch (Exception $exception) {
                // Handle exceptions
                // Render the form again with errors if there are any
                $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                 
                return $this->twig->render($response, 'sign-up.twig', [
                    'formErrors' => $errors,
                    'exception' => $exception->getMessage(),
                    'formData' => $data,
                    'formAction' => $routeParser->urlFor("sign-up"),
                    'formMethod' => "POST"
                ]);
                }
        } else {
            // Render the form again with errors if there are any
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
    
            return $this->twig->render($response, 'sign-up.twig', [
                'formErrors' => $errors,
                'formData' => $data,
                'formAction' => $routeParser->urlFor("sign-up"),
                'formMethod' => "POST"
            ]);
        }
    }
    }
