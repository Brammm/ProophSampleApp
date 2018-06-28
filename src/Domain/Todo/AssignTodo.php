<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Todo\Domain\User\UserId;

final class AssignTodo extends Command
{
    use PayloadTrait;

    public function todoId(): TodoId
    {
        return TodoId::fromString($this->payload['todoId']);
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['userId']);
    }
}
