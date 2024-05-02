<?php

declare(strict_types=1);

use PWP\Controller\HomeController;
use PWP\Middleware\AuthenticationMiddleware;
use PWP\Middleware\SessionMiddleware;
use PWP\Controller\FlashController;
use PWP\Controller\CreateUserController;
use PWP\Controller\SignUpController;
use PWP\Controller\SignInController;
use PWP\Controller\ProfileController;
use PWP\Controller\CatalogueController;


$app->add(SessionMiddleware::class);
$app->get('/', HomeController::class . ':apply')->setName('home');


$app->get('/flash', FlashController::class . ':addMessage')->setName('flash');

$app->post('/user', CreateUserController::class . ':apply')->setName('create_user');

$app->get('/sign-up', SignUpController::class . ':showForm');

$app->post('/sign-up', SignUpController::class . ':handleFormSubmission')->setName('sign-up');

$app->get('/sign-in', SignInController::class . ':showForm');

$app->post('/sign-in', SignInController::class . ':handleFormSubmission')->setName('sign-in');


//Needs Authentication Middleware:
$app->get('/profile', ProfileController::class . ':showProfile')->setName('profile')->add(AuthenticationMiddleware::class);
$app->post('/profile', ProfileController::class . ':updateProfile');

$app->get('/catalogue', CatalogueController::class . ':showBooks')->setName('catalogue')->add(AuthenticationMiddleware::class);
$app->post('/catalogue', CatalogueController::class . ':updateProfile');
