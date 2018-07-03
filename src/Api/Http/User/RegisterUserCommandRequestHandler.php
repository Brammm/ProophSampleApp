<?php

declare(strict_types=1);

namespace Todo\Api\Http\User;

use Todo\Domain\User\UserId;
use Todo\Infrastructure\Http\CommandRequestHandler;

final class RegisterUserCommandRequestHandler extends CommandRequestHandler
{
    protected function processPayload(array $payload): array
    {
        $payload['userId'] = (string) UserId::generate();

        return $payload;
    }
}
