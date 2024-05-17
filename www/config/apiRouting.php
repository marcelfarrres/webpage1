<?php

declare(strict_types=1);

use PWP\ApiController\ForumApiController;
use PWP\ApiController\PostApiController;

use PWP\Middleware\AuthenticationMiddleware;
use PWP\Middleware\apiMiddleware;
use PWP\Middleware\UsernameMiddleware;

use PWP\Middleware\forumMiddleware;


//Needs Authentication Middleware:

//Forums
$app->get('/forums', ForumApiController::class . ':showForm')->add(UsernameMiddleware::class)->add(AuthenticationMiddleware::class);

$app->get('/api/forums', ForumApiController::class . ':getAllForums')->add(apiMiddleware::class);
$app->post('/api/forums', ForumApiController::class . ':postForum')->add(apiMiddleware::class);
$app->delete('/api/forums/{id}', ForumApiController::class . ':deleteForum')->add(forumMiddleware::class)->add(apiMiddleware::class);
$app->get('/api/forums/{id}', ForumApiController::class . ':getForum')->add(forumMiddleware::class)->add(apiMiddleware::class);



//Posts
$app->get('/forums/{id}/posts', PostApiController::class . ':showForm')->add(UsernameMiddleware::class)->add(AuthenticationMiddleware::class);

$app->get('/api/forums/{id}/posts', PostApiController::class . ':getAllPosts')->add(forumMiddleware::class)->add(apiMiddleware::class);
$app->post('/api/forums/{id}/posts', PostApiController::class . ':createPost')->add(apiMiddleware::class);




