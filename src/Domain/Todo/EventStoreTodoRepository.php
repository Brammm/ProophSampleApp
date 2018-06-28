<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Todo\Domain\EntityNotFound;

final class EventStoreTodoRepository extends AggregateRepository implements TodoRepository
{
    public function save(Todo $todo): void
    {
        $this->saveAggregateRoot($todo);
    }

    public function findOneByTodoId(TodoId $todoId): Todo
    {
        $todo = $this->getAggregateRoot((string) $todoId);

        if ($todo instanceof Todo) {
            return $todo;
        }

        throw new EntityNotFound();
    }
}
