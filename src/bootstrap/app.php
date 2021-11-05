<?php

declare(strict_types=1);

/**
 * If you wish to enable sessions. Sessions can be put on middlewares where session is required.
 * session_cache_limiter('');
 * session_start();
 */

use DI\ContainerBuilder;

require __DIR__ . '/../../vendor/autoload.php';


// Create ContainerBuilder instance and set the definitions
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');

// Build the PHP-DI container instance
$container = $containerBuilder->build();

// Create our APP Instance
$app = $container->get(App::class);

// Optional: set the endpoint of the RestAPI if its on a subfolder
// example: https://example.com/api/xxxxx
// set the base path to "/api"
$app->setBasePath("/");

// error handling directive
// set addErrorMiddleware to true to show any errors.
$app->addRoutingMiddleware();
$app->addErrorMiddleware(false, false, false);

// Register Middlewares

// Register all routes.
require __DIR__ . '/../app/routes.php';
