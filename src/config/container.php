<?php

declare(strict_types=1);

use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Laminas\Config\Config;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

// Invoke all class here with AutoWiring functionality
return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    /**
     * Global Configuration Setting
     */
    Config::class => function (ContainerInterface $container) {
        return new Config(require __DIR__ . "/settings.php");
    },

    /**
     * Databases
     */
    DatabaseManager::class => function (ContainerInterface $container) {
        /**
         * @var \Laminas\Config\Config
         */
        $config = $container->get(Config::class);

        $dbConfig = $config->get('db')->toArray();

        return new DatabaseManager(new DatabaseConfig($dbConfig));
    },
    DatabaseInterface::class => function(ContainerInterface $container) {
        return $container->get(DatabaseManager::class)->database('default');
    },

    /**
     * Other Dependency Injection Modules here
     */
];
