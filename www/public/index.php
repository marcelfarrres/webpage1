<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\TwigMiddleware;



require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../config/dependencies.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->add(TwigMiddleware::createFromContainer($app));
$app->addRoutingMiddleware();
$app->add(new MethodOverrideMiddleware());
$app->addErrorMiddleware(true, false, false);
$app->addBodyParsingMiddleware();

require_once __DIR__ . '/../config/routing.php';

require_once __DIR__ . '/../config/apiRouting.php';


$app->run();