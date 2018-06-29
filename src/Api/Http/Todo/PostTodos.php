<?php

declare(strict_types=1);

namespace Todo\Api\Http\Todo;

use Prooph\ServiceBus\CommandBus;
use Slim\Http\Request;
use Slim\Http\Response;
use Todo\Domain\Todo\Command\PlanTodo;
use Todo\Domain\Todo\TodoId;

final class PostTodos
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
        $payload['todoId'] = (string) TodoId::generate();

        $this->commandBus->dispatch(new PlanTodo($payload));

        return $response->withStatus(202);
    }
}
