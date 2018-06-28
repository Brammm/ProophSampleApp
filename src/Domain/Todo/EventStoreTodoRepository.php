<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

use Prooph\EventSourcing\Aggregate\AggregateRepository;

final class EventStoreTodoRepository extends AggregateRepository implements TodoRepository
{
    public function save(Todo $todo): void
    {
        $this->saveAggregateRoot($todo);
    }
}
