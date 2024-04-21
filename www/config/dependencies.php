<?php

declare(strict_types=1);

use DI\Container;
use Slim\Views\Twig;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;
use PWP\Model\Repository\PDOSingleton;
use PWP\Model\UserRepository;
use PWP\Model\Repository\MysqlUserRepository;


$container = new Container();

$container->set('view', function () {
    return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
});

$container->set(Twig::class, function (ContainerInterface $c) {
    return $c->get('view');
});

$container->set(Messages::class,  function () {
    return new Messages();
});

$container->set(PDO::class, function () {
    return PDOSingleton::getInstance(
        $_ENV['MYSQL_USER'],
        $_ENV['MYSQL_PASSWORD'],
        $_ENV['MYSQL_HOST'],
        $_ENV['MYSQL_PORT'],
        $_ENV['MYSQL_DATABASE']
    )->connection();
});

$container->set(UserRepository::class, function (ContainerInterface $c) {
    return $c->get(MysqlUserRepository::class);
});