<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

use Prooph\EventSourcing\AggregateRoot;
use Todo\Domain\User\User;
use Todo\Domain\User\UserId;
use Todo\Infrastructure\Prooph\Messaging\AppliesEvents;

final class Todo extends AggregateRoot
{
    use AppliesEvents;

    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var UserId|null
     */
    private $assignedTo;

    public static function plan(TodoId $todoId, string $description): self
    {
        $todo = new self();
        $todo->recordThat(TodoWasPlanned::occur((string) $todoId, [
            'description' => $description,
        ]));

        return $todo;
    }

    public function assignTo(User $user): void
    {
        $this->recordThat(TodoWasAssigned::occur((string) $this->todoId, [
            'userId' => (string) $user->userId(),
        ]));
    }

    protected function whenTodoWasPlanned(TodoWasPlanned $event): void
    {
        $this->todoId = $event->todoId();
        $this->description = $event->description();
    }

    protected function whenTodoWasAssigned(TodoWasAssigned $event): void
    {
        $this->assignedTo = $event->userId();
    }

    protected function aggregateId(): string
    {
        return (string) $this->todoId;
    }
}
