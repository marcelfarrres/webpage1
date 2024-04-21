<?php

declare(strict_types=1);

use PWP\Controller\HomeController;
use PWP\Middleware\AfterMiddleware;
use PWP\Middleware\SessionMiddleware;
use PWP\Controller\FlashController;
use PWP\Controller\CreateUserController;

$app->add(SessionMiddleware::class);
$app->get('/', HomeController::class . ':apply')->setName('home');
$app->add(AfterMiddleware::class);

$app->get('/flash', FlashController::class . ':addMessage')->setName('flash');

$app->post('/user', CreateUserController::class . ':apply')->setName('create_user');