<?php

declare(strict_types=1);

namespace PWP\Middleware;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


use Slim\Routing\RouteContext;

use PWP\Model\UserRepository;
use Slim\Flash\Messages;

final class AuthenticationMiddleware
{
    public function __construct(
        private Messages $flash,
        private UserRepository $userRepository
    ) {}

    public function __invoke(Request $request, RequestHandler $next): Response
    {
        // Check if the user is not signed in
        if (!isset($_SESSION['email']) || !$this->userRepository->checkIfUserExists($_SESSION['email'])) {
            // User is not signed in, redirect to home page
            $this->flash->addMessage('AuthenticationMiddlewareMessage', 'User not signed in!');
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            //TODO: How can I create a response in other way.
            return (new \Slim\Psr7\Response())->withHeader('Location', $routeParser->urlFor("sign-in"))->withStatus(302);
        }

        // User is signed in, proceed with the request
        return $next->handle($request);
    }
}
