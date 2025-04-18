<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Slim\Views\Twig;

final class HomeController
{
    private Twig $twig;

    private Messages $flash;

    public function __construct(Twig $twig, Messages $flash)
    {
        $this->twig = $twig;
        $this->flash = $flash;
    }

    public function apply(Request $request, Response $response): Response
    {
        $messages = $this->flash->getMessages();

        $notifications = $messages['notifications'] ?? [];

        return $this->twig->render($response, 'home.twig', [
            'notifications' => $notifications
        ]);
    }
}