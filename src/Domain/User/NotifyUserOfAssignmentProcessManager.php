<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Prooph\ServiceBus\CommandBus;
use Todo\Domain\Todo\TodoWasAssigned;

final class NotifyUserOfAssignmentProcessManager
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(TodoWasAssigned $event): void
    {
        $this->commandBus->dispatch(NotifyUserOfAssignment::with($event->todoId(), $event->userId()));
    }
}
