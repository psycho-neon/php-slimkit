<?php

declare(strict_types=1);

use App\Controllers\InvalidRoute;

/**
 * Add RestAPI endpoints here
 */
$app->get('/example', function($request, $response, $args) {
  $response->getBody()->write('This is an example ');
  return $response;
})->setname('example');

/**
 * Any routes invalid routes will be redirected here
 */
$app->any('[/{route:.*}]', InvalidRoute::class)->setname('not_found');
