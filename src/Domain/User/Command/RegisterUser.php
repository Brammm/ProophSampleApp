<?php

declare(strict_types=1);

namespace Todo\Domain\User\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Todo\Domain\User\Email;
use Todo\Domain\User\Password;
use Todo\Domain\User\UserId;

final class RegisterUser extends Command
{
    use PayloadTrait;

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['userId']);
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
