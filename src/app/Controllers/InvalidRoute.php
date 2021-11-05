<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InvalidRoute
{
    /**
     * default constractor with container in it
     */
    public function __construct(ContainerInterface $container, DatabaseInterface $database)
    {
        $this->container = $container;
        $this->database = $database;
    }

    /**
     * Default Error Handler
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function __invoke(Request $req, Response $res, array $args)
    {
        return $res->withStatus(301)->withHeader('Location', 'example.com');
    }
}
