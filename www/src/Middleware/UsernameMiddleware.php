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

final class UsernameMiddleware
{
    public function __construct(
        private Messages $flash,
        private UserRepository $userRepository
    ) {}

    public function __invoke(Request $request, RequestHandler $next): ResponseInterface
    {
        

        //check if the usernsame is not null
        if (!$this->userRepository->checkIfUsernameExists($_SESSION['email'])) {
            $this->flash->addMessage('AuthenticationMiddlewareMessage', 'Please add a username!');
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return (new Response())->withHeader('Location', $routeParser->urlFor("profile"))->withStatus(302);
        }

        return $next->handle($request);
    }
}
