<?php

declare(strict_types=1);

namespace Todo\Domain\Todo\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Todo\Domain\Todo\TodoId;

final class PlanTodo extends Command
{
    use PayloadTrait;

    public function todoId(): TodoId
    {
        return TodoId::fromString($this->payload['todoId']);
    }

    public function description(): string
    {
        return $this->payload['description'];
    }
}
