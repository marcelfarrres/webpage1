<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;

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
        $userEmail = $_SESSION['email'];
        $user = $this->userRepository->getUserbyEmail($userEmail);

        return $this->twig->render($response, 'profile.twig', ['user' => $user]);
    }

    public function updateProfile(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $files = $request->getUploadedFiles();

        // Validate and process form data...

        // Example validation:
        $errors = [];
        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        } else {
            // Check if username is unique (you need to implement this logic)
        }

        if ($files['profile-picture']->getError() === UPLOAD_ERR_OK) {
            // Validate profile picture
        } else {
            $errors['profile_picture'] = 'Profile picture upload error';
        }

        // If there are errors, redirect back to profile page with errors
        if (!empty($errors)) {
            $this->flash->addMessage('errors', $errors);
            return $response->withRedirect('/profile');
        }

        // If everything is valid, update user profile and redirect to profile page
        // Update user's username and profile picture in database

        // Redirect to profile page
        return $response->withRedirect('/profile');
    }
}
