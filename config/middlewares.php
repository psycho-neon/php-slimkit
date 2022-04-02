<?php

declare(strict_types=1);

use App\Middlewares\JwtClaimMiddleware;

return function (Slim\App $app) {
    // json body parsing
    $app->addBodyParsingMiddleware();

    // Routing middlewares
    $app->addRoutingMiddleware();

    // error handling directive
    // set addErrorMiddleware to true to show any errors.
    $app->addErrorMiddleware(true, true, true);

    // custom middleware to be added here
    $app->add(JwtClaimMiddleware::class);
};
