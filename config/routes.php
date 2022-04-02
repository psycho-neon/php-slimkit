<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

return function (Slim\App $app) {
    $app->get('/example', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
        // example message
        $response->getBody()->write('Example response.');

        return $response;
    });

    $app->get('/auth', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
        // Guarded API Route
        $response->getBody()->write('You have accessed an authenticated-only page!');

        return $response;
    })->add(\App\Middlewares\JwtAuthMiddleware::class);
};
