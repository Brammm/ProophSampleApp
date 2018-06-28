<?php

namespace Todo\Domain\Todo;

use Prooph\EventSourcing\AggregateChanged;

class TodoWasPlanned extends AggregateChanged
{
    public function todoId(): TodoId
    {
        return TodoId::fromString($this->aggregateId());
    }

    public function description(): string
    {
        return $this->payload['description'];
    }
}
