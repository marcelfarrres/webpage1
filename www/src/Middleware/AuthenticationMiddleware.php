<?php

declare(strict_types=1);

namespace PWP\Middleware;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;


use Slim\Routing\RouteContext;

use PWP\Model\UserRepository;
use Slim\Flash\Messages;

final class AuthenticationMiddleware
{
    public function __construct(
        private Messages $flash,
        private UserRepository $userRepository
    ) {}

    public function __invoke(Request $request, RequestHandler $next): ResponseInterface
    {
        //check if the user is not logged in
        if (!isset($_SESSION['email']) || !$this->userRepository->checkIfUserExists($_SESSION['email'])) {
            $this->flash->addMessage('AuthenticationMiddlewareMessage', 'User not signed in!');
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return (new Response())->withHeader('Location', $routeParser->urlFor("sign-in"))->withStatus(302);
        }

        

        return $next->handle($request);
    }
}
