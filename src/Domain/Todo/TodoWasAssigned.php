<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

use Prooph\EventSourcing\AggregateChanged;
use Todo\Domain\User\UserId;

final class TodoWasAssigned extends AggregateChanged
{
    public function todoId(): TodoId
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return TodoId::fromString($this->aggregateId());
    }

    public function userId(): UserId
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return UserId::fromString($this->payload['userId']);
    }
}
