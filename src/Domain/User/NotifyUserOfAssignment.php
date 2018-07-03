<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Todo\Domain\Todo\TodoId;

final class NotifyUserOfAssignment extends Command
{
    use PayloadTrait;

    public static function with(TodoId $todoId, UserId $userId): self
    {
        return new self([
            'todoId' => (string) $todoId,
            'userId' => (string) $userId,
        ]);
    }

    public function todoId(): TodoId
    {
        return TodoId::fromString($this->payload['todoId']);
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->payload['userId']);
    }
}
