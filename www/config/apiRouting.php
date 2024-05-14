<?php

declare(strict_types=1);

use PWP\ApiController\ForumApiController;

//Needs Authentication Middleware:               
$app->get('/api/forums', ForumApiController::class . ':getAllForums')->setName('profile');