<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function in_array;
use function json_decode;

final class JsonRequestParameterParser implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array('application/json', $request->getHeader('content-type'))) {
            $request = $request->withParsedBody(json_decode(
                (string) $request->getBody(),
                true,
                512,
                JSON_THROW_ON_ERROR
            ));
        }

        return $handler->handle($request);
    }
}
