<?php

declare(strict_types=1);

use PWP\Controller\HomeController;
use PWP\Middleware\AfterMiddleware;
use PWP\Middleware\SessionMiddleware;
use PWP\Controller\FlashController;
use PWP\Controller\CreateUserController;
use PWP\Controller\SignUpController;
use PWP\Controller\SignInController;
use PWP\Controller\ProfileController;


$app->add(SessionMiddleware::class);
$app->get('/', HomeController::class . ':apply')->setName('home');
$app->add(AfterMiddleware::class);

$app->get('/flash', FlashController::class . ':addMessage')->setName('flash');

$app->post('/user', CreateUserController::class . ':apply')->setName('create_user');

$app->get('/sign-up', SignUpController::class . ':showForm');

$app->post('/sign-up', SignUpController::class . ':handleFormSubmission')->setName('sign-up');

$app->get('/sign-in', SignInController::class . ':showForm');

$app->post('/sign-in', SignInController::class . ':handleFormSubmission')->setName('sign-in');

$app->get('/profile', ProfileController::class . ':showProfile')->setName('profile');
