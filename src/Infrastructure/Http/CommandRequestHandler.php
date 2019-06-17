<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Http;

use Prooph\ServiceBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Slim\Routing\Route;

class CommandRequestHandler implements RequestHandlerInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Route $route */
        $route = $request->getAttribute('route');
        $commandName = $route->getArgument('commandName');

        if ($commandName === null) {
            throw new RuntimeException('Command name not configured');
        }

        $payload = $this->processPayload($request->getParsedBody());

        $this->commandBus->dispatch(new $commandName($payload));

        return ResponseFactory::emptyResponse();
    }

    protected function processPayload(array $payload): array
    {
        return $payload;
    }
}
