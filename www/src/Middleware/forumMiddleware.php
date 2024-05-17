<?php

declare(strict_types=1);

namespace PWP\Middleware;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

use Slim\Routing\RouteContext;

use PWP\Model\ForumRepository;

final class forumMiddleware
{
    public function __construct(private ForumRepository $forumRepository) {}

    public function __invoke(Request $request, RequestHandler $next): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        
        // Check if the route exists
        if ($route !== null) {
            // Get the route arguments
            $args = $route->getArguments();
            $forumId = $args['id'] ?? null;

            if ($forumId !== null) {
                $forum = $this->forumRepository->getForumById((int)$forumId);

                if ($forum->getTitle() == 'ERROR404' && $forum->getDescription() == 'ERROR404') {
                    $errorMessage = json_encode(['message' => 'Forum with id ' . $forumId . ' does not exist']);
                    $response = new Response();
                    $response->getBody()->write($errorMessage);
                    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
                }
            }
        }

        return $next->handle($request);
    }
}
