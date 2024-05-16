<?php

declare(strict_types=1);

use PWP\ApiController\ForumApiController;
use PWP\ApiController\PostApiController;


//Needs Authentication Middleware:

//Forums
$app->get('/forums', ForumApiController::class . ':showForm');

$app->get('/api/forums', ForumApiController::class . ':getAllForums');
$app->post('/api/forums', ForumApiController::class . ':postForum');
$app->delete('/api/forums/{id}', ForumApiController::class . ':deleteForum');
$app->get('/api/forums/{id}', ForumApiController::class . ':getForum');



//Posts
$app->get('/forums/{id}/posts', PostApiController::class . ':showForm');

$app->get('/api/forums/{id}/posts', PostApiController::class . ':getAllPosts');
$app->post('/api/forums/{id}/posts', PostApiController::class . ':createPost');




