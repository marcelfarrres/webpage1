<?php

declare(strict_types=1);

use PWP\Controller\HomeController;

$app->get('/', HomeController::class . ':apply')->setName('home');
