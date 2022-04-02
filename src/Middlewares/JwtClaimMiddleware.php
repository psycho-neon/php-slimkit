<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Support\JwtAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class JwtClaimMiddleware implements MiddlewareInterface
{
    private JwtAuth $jwtAuth;

    public function __construct(JwtAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
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
        if (!$token) {
            return $handler->handle($request);
        }

        $tokenized = $this->jwtAuth->validateToken($token);
        if ($tokenized) {
            // example of claims
            $request = $request->withAttribute('uid', $tokenized->claims()->get('uid'));
        }

        return $handler->handle($request);
    }
}
