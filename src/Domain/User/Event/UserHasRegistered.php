<?php

declare(strict_types=1);

namespace Todo\Domain\User\Event;

use Prooph\EventSourcing\AggregateChanged;
use Todo\Domain\User\Email;
use Todo\Domain\User\Password;
use Todo\Domain\User\UserId;

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
