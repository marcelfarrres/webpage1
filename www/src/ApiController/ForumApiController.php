<?php

declare(strict_types=1);

namespace PWP\ApiController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PWP\Model\ForumRepository;

use Slim\Routing\RouteContext;


final class ForumApiController
{
    
    private ForumRepository $forumRepository; 
    


    public function __construct(ForumRepository $forumRepository)
    {
        
        $this->forumRepository = $forumRepository;
    }

    public function getAllForums(Request $request, Response $response, array $args): Response
{
    $forums = $this->forumRepository->getAllForums();
    
    //filter
    $forumsData = [];

    foreach ($forums as $forum) {
        $forumData = [
            'id' => $forum->getId(),
            'title' => $forum->getTitle(),
            'description' => $forum->getDescription()
        ];

        $forumsData[] = $forumData;
    }
    
    $jsonData = json_encode($forumsData);

    $response->getBody()->write($jsonData);
    return $response->withHeader('Content-Type', 'application/json');
}

    


}
