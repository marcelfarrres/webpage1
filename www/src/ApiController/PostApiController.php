<?php

declare(strict_types=1);

namespace PWP\ApiController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use PWP\Model\PostRepository;
use PWP\Model\UserRepository;

use PWP\Model\Post;
use DateTime;

final class PostApiController
{
    private PostRepository $postRepository; 
    private UserRepository $userRepository; 

    private Twig $twig;

    public function __construct(Twig $twig, PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;


    }
    public function showForm(Request $request, Response $response, array $args): Response

    {
        $forumId = $args['id'] ?? '';
        

        return $this->twig->render($response, 'post.twig', [
            'forumId' => $forumId
        ]);

    }

    public function getAllPosts(Request $request, Response $response, array $args): Response
    {
        $forumId = $args['id'] ?? '';
        $posts = $this->postRepository->getAllPosts((int)$forumId);
        
        // Filter
        $postsData = [];

        foreach ($posts as $post) {
            $postData = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'contents' => $post->getContents()
            ];

            $postsData[] = $postData;
        }
        
        $payload = json_encode($postsData);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createPost(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $title = $body['title'] ?? null;
        $contents = $body['contents'] ?? null;
        $forumId = $args['id'];
       


        if (!$title || !$contents) {
            $errorMessage = json_encode(['error' => 'Title and contents are required']);
            $response->getBody()->write($errorMessage);
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        //get the USER id
        $user = $this->userRepository->getUserbyEmail($_SESSION['email']);

        $newPost = new Post($user->id(), (int) $forumId, $title, $contents, new DateTime, new DateTime,);

        $this->postRepository->save($newPost);

        $newPost = $this->postRepository->getLastPostAdded();

        $responseData = [
            'id' => $newPost->getId(),
            'title' => $newPost->getTitle(),
            'contents' => $newPost->getContents()
        ];

        $payload = json_encode($responseData);

        $response->getBody()->write($payload);

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

   
}
