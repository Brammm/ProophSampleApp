<?php

declare(strict_types=1);

namespace Todo\Application\Http\Todo;

use Prooph\ServiceBus\CommandBus;
use Slim\Http\Request;
use Slim\Http\Response;
use Todo\Domain\Todo\Command\AssignTodo;

final class PutTodoAssign
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
        $this->commandBus->dispatch(new AssignTodo($request->getParsedBody()));

        return $response->withStatus(202);
    }
}
