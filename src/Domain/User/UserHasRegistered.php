<?php

namespace Todo\Domain\User;

use Prooph\EventSourcing\AggregateChanged;

final class UserHasRegistered extends AggregateChanged
{
    public function userId(): UserId
    {
        return UserId::fromString($this->aggregateId());
    }

    public function email(): Email
    {
        return new Email($this->payload['email']);
    }

    public function password(): Password
    {
        return new Password($this->payload['password']);
    }
}
