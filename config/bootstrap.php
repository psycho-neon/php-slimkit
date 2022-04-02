<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;

// Create ContainerBuilder instance and set the definitions
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

// Enable for production improvements
// $containerBuilder->enableCompilation(__DIR__ . '/../tmp/cache');
// $containerBuilder->writeProxiesToFile(true, __DIR__ . '/../tmp/cache');

// Build the PHP-DI container instance
$container = $containerBuilder->build();

// Create App Instance
$app = $container->get(App::class);

// Optional: set the endpoint of the RestAPI if its on a subfolder
// example: https://example.com/api/xxxxx
// $app->setBasePath("/api");

// Register middlewares
(require_once __DIR__ . '/middlewares.php')($app);

// Register all routes.
(require_once __DIR__ . '/routes.php')($app);
