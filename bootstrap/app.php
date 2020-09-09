<?php

session_start();

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Slim\Views\Twig;
use Slim\Factory\AppFactory;
use Slim\Views\TwigExtension;
use Slim\Psr7\Factory\UriFactory;
use Slim\Flash\Messages;


require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv(__DIR__ . '/../'))->load();
} catch (InvalidPathException $e) {
    //
}

$container = new DI\Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('settings', function () {
    return [
        'app' => [
            'name' => getenv('APP_NAME')
        ]
    ];
});

$container->set('view', function ($container) use ($app) {
    $twig = new Twig(__DIR__ . '/../resources/views', [
        'cache' => false
    ]);

    $twig->getEnvironment()->addGlobal('messages', $container->get('flash')->getMessages());

    $twig->addExtension(
        new TwigExtension(
            $app->getRouteCollector()->getRouteParser(),
            (new UriFactory)->createFromGlobals($_SERVER),
            '/'
        )
    );

    return $twig;
});

$container->set('flash', function () {
    return new Messages();
});

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../routes/web.php';
