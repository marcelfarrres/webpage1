<?php

declare(strict_types=1);

namespace PWP\Controller;

use DateTime;
use Exception;
use PWP\Model\User;
use PWP\Model\UserRepository;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class CreateUserController
{

    private Twig $twig;
    private UserRepository $userRepository;

    public function __construct(Twig $twig, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }
   

    public function apply(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            // TODO - Validate data before instantiating the user
            $user = new User(
                $data['email'] ?? '',
                $data['password'] ?? '',
                new DateTime(),
                new DateTime()
            );

            $this->userRepository->save($user);
        } catch (Exception $exception) {
            // You could render a .twig template here to show the error
            $response->getBody()
                ->write('Unexpected error: ' . $exception->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);
    }
}