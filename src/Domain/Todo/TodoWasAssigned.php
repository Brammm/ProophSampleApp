<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

use Prooph\EventSourcing\AggregateChanged;
use Todo\Domain\User\UserId;

final class TodoWasAssigned extends AggregateChanged
{
    public function todoId(): TodoId
    {
        return TodoId::fromString($this->aggregateId());
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['userId']);
    }
}
