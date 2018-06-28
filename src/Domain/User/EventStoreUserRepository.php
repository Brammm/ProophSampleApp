<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Prooph\EventSourcing\Aggregate\AggregateRepository;

final class EventStoreUserRepository extends AggregateRepository implements UserRepository
{
    public function save(User $user): void
    {
        $this->saveAggregateRoot($user);
    }
}
