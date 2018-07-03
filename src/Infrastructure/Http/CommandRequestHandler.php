<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Http;

use Prooph\ServiceBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

class CommandRequestHandler
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $commandName = null
    ): ResponseInterface {
        if ($commandName === null) {
            throw new RuntimeException('Command name not configured');
        }

        $payload = $this->processPayload($request->getParsedBody());

        $this->commandBus->dispatch(new $commandName($payload));

        return $response->withStatus(204);
    }

    protected function processPayload(array $payload): array
    {
        return $payload;
    }
}
