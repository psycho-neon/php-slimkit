<?php

declare(strict_types=1);

use App\Support\JwtAuth;
use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Laminas\Config\Config;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;

return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    // The Slim RouterParser and ResponseFactoryInterface
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },

    // Global Configuration Setting.
    Config::class => function (ContainerInterface $container) {
        return new Config(require_once __DIR__ . '/settings.php');
    },

    // Databases.
    DatabaseManager::class => function (ContainerInterface $container) {
        $config = $container->get(Config::class);
        $dbConfig = $config->get('db')->toArray();

        return new DatabaseManager(new DatabaseConfig($dbConfig));
    },
    DatabaseInterface::class => function (ContainerInterface $container) {
        $database = $container->get(DatabaseManager::class)->database('default');

        // set foreign key, and wal mode
        $database->execute('PRAGMA foreign_keys=ON;');
        $database->execute('PRAGMA journal_mode=WAL;');

        return $database;
    },

    // JWT Token Interface.
    Configuration::class => function (ContainerInterface $container) {
        $config = $container->get(Config::class);

        $jwtSettings = $config->get('jwt');
        $key = $jwtSettings['keyword'];

        // Use Symetrical configuration for now.
        return Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($key)
        );
    },
    JwtAuth::class => function (ContainerInterface $container) {
        /** @var Configuration */
        $configuration = $container->get(Configuration::class);

        /** @var Config */
        $config = $container->get(Config::class);

        $jwtSettings = $config->get('jwt');
        $issuer = $jwtSettings['issuer'];
        $lifetime = $jwtSettings['lifetime'];

        return new JwtAuth($configuration, $issuer, $lifetime);
    },
];
