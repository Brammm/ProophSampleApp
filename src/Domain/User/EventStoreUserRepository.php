<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Todo\Domain\EntityNotFound;

final class EventStoreUserRepository extends AggregateRepository implements UserRepository
{
    public function save(User $user): void
    {
        $this->saveAggregateRoot($user);
    }

    public function findOneByUserId(UserId $userId): User
    {
        $user = $this->getAggregateRoot((string) $userId);

        if ($user instanceof User) {
            return $user;
        }

        throw new EntityNotFound();
    }
}
