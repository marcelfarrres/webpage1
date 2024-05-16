<?php

declare(strict_types=1);

namespace PWP\ApiController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use PWP\Model\ForumRepository;
use PWP\Model\Forum;
use DateTime;

use Slim\Routing\RouteContext;


final class ForumApiController
{
    
    private ForumRepository $forumRepository; 
    private Twig $twig;
    


    public function __construct(Twig $twig, ForumRepository $forumRepository)
    {
        
        $this->twig = $twig;
        $this->forumRepository = $forumRepository;

    }


    public function showForm(Request $request, Response $response): Response

    {

        return $this->twig->render($response, 'forum.twig');

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
    
    $payload = json_encode($forumsData);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
}


public function postForum(Request $request, Response $response, array $args): Response
{
    $body = $request->getParsedBody();

    $title = $body['title'] ?? null;
    $description = $body['description'] ?? null;

    if (!$title || !$description) {
        $errorMessage = json_encode(['error' => 'Title and description are required']);
        $response->getBody()->write($errorMessage);
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $newForum = new Forum($title, $description, new DateTime, new DateTime);

    $this->forumRepository->save($newForum);

    $newForum = $this->forumRepository->getLastForumAdded();


    $responseData = [
        'id' => $newForum->getId(),
        'title' => $newForum->getTitle(),
        'description' => $newForum->getDescription()
    ];

    $payload = json_encode($responseData);

    $response->getBody()->write($payload);

    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
}


public function deleteForum(Request $request, Response $response, array $args): Response
{
    $forumId = $args['id'];

    $deleted = $this->forumRepository->deleteForum((int)$forumId);

    if ($deleted) {
        
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    } else {
       
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

   
}

public function getForum(Request $request, Response $response, array $args): Response
{
    $forumId = $args['id'];

    $forum = $this->forumRepository->getForumById((int)$forumId);


    $responseData = [
        'id' => $forum->getId(),
        'title' => $forum->getTitle(),
        'description' => $forum->getDescription()
    ];

    $payload = json_encode($responseData);

    $response->getBody()->write($payload);

    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');

   
}





}
