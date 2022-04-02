<?php

declare(strict_types=1);

namespace App\Action\User;

use App\Domain\User\Service\UserCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserCreateAction
{
    private UserCreator $service;

    public function __construct(UserCreator $service)
    {
        $this->service = $service;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $dataForm = (array)$request->getParsedBody();

        $id = $this->service->createUser($dataForm);

        $response->getBody()->write($id);

        return $response;
    }
}
