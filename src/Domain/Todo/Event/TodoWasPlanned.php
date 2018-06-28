<?php

declare(strict_types=1);

namespace Todo\Domain\Todo\Event;

use Prooph\EventSourcing\AggregateChanged;
use Todo\Domain\Todo\TodoId;

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
