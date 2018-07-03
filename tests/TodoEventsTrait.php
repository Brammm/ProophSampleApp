<?php

declare(strict_types=1);

namespace Todo\Tests;

use Todo\Domain\Todo\Todo;
use Todo\Domain\Todo\TodoId;
use Todo\Domain\Todo\TodoWasPlanned;

trait TodoEventsTrait
{
    /**
     * @param string $aggregateRootClass
     * @param array $events
     *
     * @return Todo
     */
    abstract protected function reconstituteAggregateFromHistory(string $aggregateRootClass, array $events): object;

    protected function reconstituteTodo(array $events): Todo
    {
        return $this->reconstituteAggregateFromHistory(Todo::class, $events);
    }

    protected function todoWasPlanned(TodoId $todoId, string $description = 'Todo description'): TodoWasPlanned
    {
        return TodoWasPlanned::occur((string) $todoId, [
            'description' => $description,
        ]);
    }
}
