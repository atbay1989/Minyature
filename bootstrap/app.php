<?php

session_start();

use App\Commands\SendMessageCommand;
use App\Handlers\SendMessageHandler;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use League\Tactician\Setup\QuickStart;
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

$container->set('email', function () {
    $transport = (new Swift_SmtpTransport(getenv('SMTP_HOST'), 25))
    ->setUsername(getenv('SMTP_USERNAME'))
    ->setPassword(getenv('SMTP_PASSWORD'));

    return new Swift_Mailer($transport);
});

$container->set('bus', function ($container) {
    return QuickStart::create([
        SendMessageCommand::class => new SendMessageHandler(
            $container->get('view'),
            $container->get('email')
            )
    ]);
});

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../routes/web.php';
