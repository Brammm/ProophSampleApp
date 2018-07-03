<?php

declare(strict_types=1);

namespace Todo\Api\Http\Todo;

use Todo\Domain\Todo\TodoId;
use Todo\Infrastructure\Http\CommandRequestHandler;

final class PlanTodoCommandRequestHandler extends CommandRequestHandler
{
    protected function processPayload(array $payload): array
    {
        $payload['todoId'] = (string) TodoId::generate();

        return $payload;
    }
}
