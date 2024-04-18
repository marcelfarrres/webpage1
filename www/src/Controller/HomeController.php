<?php

declare(strict_types=1);

namespace PWP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class HomeController
{

    private Twig $twig;

    // You can also use https://stitcher.io/blog/constructor-promotion-in-php-8
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function apply(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'home.twig');
    }
}