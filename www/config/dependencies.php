<?php

declare(strict_types=1);

use DI\Container;


use Slim\Views\Twig;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;
use PWP\Model\Repository\PDOSingleton;
use PWP\Model\UserRepository;
use PWP\Model\Repository\MysqlUserRepository;
use PWP\Model\Service\GuzzleBookService;
use PWP\Model\BookService;
use PWP\Model\BookRepository;
use PWP\Model\Repository\MysqlBookRepository;
use PWP\Model\RateRepository;
use PWP\Model\ReviewRepository;
use PWP\Model\Repository\MysqlRateRepository;
use PWP\Model\Repository\MysqlReviewRepository;
use PWP\Model\ForumRepository;
use PWP\Model\Repository\MysqlForumRepository;
use PWP\Model\PostRepository;
use PWP\Model\Repository\MysqlPostRepository;










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

//MySqlRepos------------------------------------------------------------------------
$container->set(UserRepository::class, function (ContainerInterface $c) {
    return $c->get(MysqlUserRepository::class);
});

$container->set(BookRepository::class, function (ContainerInterface $c) {
    return $c->get(MysqlBookRepository::class);
});

$container->set(RateRepository::class, function (ContainerInterface $c) {
    return $c->get(MysqlRateRepository::class);
});

$container->set(ReviewRepository::class, function (ContainerInterface $c) {
    return $c->get(MysqlReviewRepository::class);
});

//API-MySqlRepos------------------------------------------------------------------------
$container->set(ForumRepository::class, function (ContainerInterface $c) {
    return $c->get(MysqlForumRepository::class);
});

$container->set(PostRepository::class, function (ContainerInterface $c) {
    return $c->get(MysqlPostRepository::class);
});

//Services------------------------------------------------------------------------
$container->set(BookService::class, function (ContainerInterface $c) {
    return $c->get(GuzzleBookService::class);
});