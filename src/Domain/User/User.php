<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Prooph\EventSourcing\AggregateRoot;
use Todo\Domain\User\Event\UserHasRegistered;
use Todo\Infrastructure\Prooph\Messaging\AppliesEvents;

final class User extends AggregateRoot
{
    use AppliesEvents;

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

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    protected function whenUserHasRegistered(UserHasRegistered $event): void
    {
        $this->userId = $event->userId();
        $this->email = $event->email();
        $this->password = $event->password();
    }

    protected function aggregateId(): string
    {
        return (string) $this->userId;
    }
}
