<?php

declare(strict_types=1);

namespace PWP\Middleware;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;


use Slim\Routing\RouteContext;

use PWP\Model\UserRepository;


final class apiMiddleware
{
    public function __construct(
        
        private UserRepository $userRepository
    ) {}

    public function __invoke(Request $request, RequestHandler $next): ResponseInterface
    {
        //check if the user is not logged in
        if (!isset($_SESSION['email']) ) {
            $ErrorBody = [
                 "message"=> "This API can only be used by authenticated users."
            ];
            $payload = json_encode($ErrorBody);

            $response = new Response();
            $response->getBody()->write($payload);
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        //check if the usernsame is not null
        if (!$this->userRepository->checkIfUsernameExists($_SESSION['email'])) {
            $ErrorBody = [
                "message"=> "This API can only be used by users with a defined username."
           ];
           $payload = json_encode($ErrorBody);

           $response = new Response();
           $response->getBody()->write($payload);
           return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        
        
        return $next->handle($request);
    }
}
