<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class Todo extends AggregateRoot
{
    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var string
     */
    private $description;

    public static function plan(TodoId $todoId, string $description): self
    {
        $todo = new self();
        $todo->recordThat(TodoWasPlanned::occur((string) $todoId, [
            'description' => $description,
        ]));

        return $todo;
    }

    protected function aggregateId(): string
    {
        return (string) $this->todoId;
    }

    /**
     * Apply given event
     */
    protected function apply(AggregateChanged $event): void
    {
        switch (true) {
            case $event instanceof TodoWasPlanned:
                $this->whenTodoWasPlanned($event);
                break;
        }
    }

    private function whenTodoWasPlanned(TodoWasPlanned $event)
    {
        $this->todoId = $event->todoId();
        $this->description = $event->description();
    }
}
