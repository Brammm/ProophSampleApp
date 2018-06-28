<?php

namespace Todo\Domain\User;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class User extends AggregateRoot
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var Password
     */
    private $password;

    public static function registerUser(UserId $userId, Email $email, Password $password): User
    {
        $user = new self();
        $user->recordThat(UserHasRegistered::occur((string) $userId, [
            'email' => $email,
            'password' => $password,
        ]));

        return $user;
    }

    protected function aggregateId(): string
    {
        return (string) $this->userId;
    }

    /**
     * Apply given event
     */
    protected function apply(AggregateChanged $event): void
    {
        switch (true) {
            case $event instanceof UserHasRegistered:
                $this->applyUserHasRegistered($event);
                break;
        }
    }

    private function applyUserHasRegistered(UserHasRegistered $event)
    {
        $this->userId = $event->userId();
        $this->email = $event->email();
        $this->password = $event->password();
    }
}
