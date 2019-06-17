<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Http;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use function json_encode;
use RuntimeException;

final class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private static $responseFactory;

    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        if (static::$responseFactory === null) {
            throw new RuntimeException('Set a base response factory first.');
        }

        return static::$responseFactory->createResponse($code, $reasonPhrase);
    }

    public static function emptyResponse(): ResponseInterface
    {
        return static::$responseFactory->createResponse(204);
    }

    /**
     * @param mixed $data The data being encoded. Can be any type except a resource.
     * @param int $status
     *
     * @return ResponseInterface
     */
    public static function jsonResponse($data, $status = 200): ResponseInterface
    {
        $response = static::$responseFactory->createResponse($status)
            ->withHeader('content-type', 'application/json');

        $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));

        return $response;
    }

    public static function setResponseFactory(ResponseFactoryInterface $responseFactory): void
    {
        static::$responseFactory = $responseFactory;
    }
}
