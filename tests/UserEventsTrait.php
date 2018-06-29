<?php

declare(strict_types=1);

namespace Todo\Tests;

use Todo\Domain\User\Event\UserHasRegistered;
use Todo\Domain\User\User;
use Todo\Domain\User\UserId;

trait UserEventsTrait
{
    /**
     * @param string $aggregateRootClass
     * @param array $events
     *
     * @return User
     */
    abstract protected function reconstituteAggregateFromHistory(string $aggregateRootClass, array $events): object;

    protected function reconstituteUser(array $events): User
    {
        return $this->reconstituteAggregateFromHistory(User::class, $events);
    }

    protected function userHasRegistered(UserId $userId): UserHasRegistered
    {
        return UserHasRegistered::occur((string) $userId, [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);
    }
}
