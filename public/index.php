<?php

declare(strict_types=1);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Load the project's environment variables
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required([
    'SENDGRID_API_KEY',
    'SENDGRID_RECIPIENT_ADDRESS',
    'SENDGRID_RECIPIENT_NAME',
    'SENDGRID_SENDER_ADDRESS',
    'SENDGRID_SENDER_NAME',
    'SENDGRID_WEBHOOK_PUBLIC_KEY',
]);

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var \Mezzio\Application $app */
    $app = $container->get(\Mezzio\Application::class);
    $factory = $container->get(\Mezzio\MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    (require 'config/pipeline.php')($app, $factory, $container);
    (require 'config/routes.php')($app, $factory, $container);

    $app->run();
})();
