<?php

declare(strict_types=1);

use PWP\Controller\HomeController;
use PWP\Middleware\AuthenticationMiddleware;
use PWP\Middleware\UsernameMiddleware;

use PWP\Middleware\SessionMiddleware;
use PWP\Controller\SignUpController;
use PWP\Controller\SignInController;
use PWP\Controller\ProfileController;
use PWP\Controller\CatalogueController;
use PWP\Controller\DetailsController;
use PWP\Controller\RateController;
use PWP\Controller\ReviewController;

$app->add(SessionMiddleware::class);


$app->get('/', HomeController::class . ':apply')->setName('home');

$app->get('/sign-up', SignUpController::class . ':showForm');

$app->post('/sign-up', SignUpController::class . ':handleFormSubmission')->setName('sign-up');

$app->get('/sign-in', SignInController::class . ':showForm');

$app->post('/sign-in', SignInController::class . ':handleFormSubmission')->setName('sign-in');


//Needs Authentication Middleware:               
$app->get('/profile', ProfileController::class . ':showProfile')->setName('profile')->add(AuthenticationMiddleware::class);
$app->post('/profile', ProfileController::class . ':updateProfile')->add(AuthenticationMiddleware::class);

$app->get('/catalogue', CatalogueController::class . ':showBooks')->setName('catalogue')->add(UsernameMiddleware::class)->add(AuthenticationMiddleware::class);
$app->post('/catalogue', CatalogueController::class . ':persistBook')->add(AuthenticationMiddleware::class);

$app->get('/details/{id}', DetailsController::class . ':showDetails')->setName('details')->add(UsernameMiddleware::class)->add(AuthenticationMiddleware::class);

$app->put('/catalogue/{id}/rate', RateController::class . ':putRating')->setName('rate')->add(AuthenticationMiddleware::class);
$app->delete('/catalogue/{id}/rate', RateController::class . ':deleteRating')->add(AuthenticationMiddleware::class);


$app->put('/catalogue/{id}/review', ReviewController::class . ':putReview')->setName('review')->add(AuthenticationMiddleware::class);
$app->delete('/catalogue/{id}/review', ReviewController::class . ':deleteReview')->add(AuthenticationMiddleware::class);






