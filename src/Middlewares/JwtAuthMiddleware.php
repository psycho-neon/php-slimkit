<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Support\JwtAuth;
use JsonException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class JwtAuthMiddleware implements MiddlewareInterface
{
    private JwtAuth $jwtAuth;

    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        JwtAuth $jwtAuth,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->jwtAuth = $jwtAuth;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Invoke middleware.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // get token via cookie
        $token = $_COOKIE['jwt-token'] ?? null;

        if (!$token || !$this->jwtAuth->validateToken($token)) {
            throw new JsonException('Unauthorized Access.', 401);
        }

        return $handler->handle($request);
    }
}
