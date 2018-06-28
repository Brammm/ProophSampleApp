<?php

declare(strict_types=1);

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

    public static function registerUser(UserId $userId, Email $email, Password $password): self
    {
        $user = new self();
        $user->recordThat(UserHasRegistered::occur((string) $userId, [
            'email' => (string) $email,
            'password' => (string) $password,
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
                $this->whenUserHasRegistered($event);
                break;
        }
    }

    private function whenUserHasRegistered(UserHasRegistered $event): void
    {
        $this->userId = $event->userId();
        $this->email = $event->email();
        $this->password = $event->password();
    }
}
