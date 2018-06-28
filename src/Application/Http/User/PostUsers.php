<?php

declare(strict_types=1);

namespace Todo\Application\Http\User;

use Prooph\ServiceBus\CommandBus;
use Slim\Http\Request;
use Slim\Http\Response;
use Todo\Domain\User\Command\RegisterUser;
use Todo\Domain\User\UserId;

final class PostUsers
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $payload = $request->getParsedBody();
        $payload['userId'] = (string) UserId::generate();

        $this->commandBus->dispatch(new RegisterUser($payload));

        return $response->withStatus(202);
    }
}
