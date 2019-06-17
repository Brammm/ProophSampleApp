<?php

declare(strict_types=1);

namespace Todo\Api;

use DI\ContainerBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use Slim\App;
use Slim\Handlers\Strategies\RequestHandler;
use Todo\Api\Http\Todo\PlanTodoCommandRequestHandler;
use Todo\Api\Http\Todo\TodoOverviewRequestHandler;
use Todo\Api\Http\User\RegisterUserCommandRequestHandler;
use Todo\Domain\Todo\AssignTodo;
use Todo\Domain\Todo\PlanTodo;
use Todo\Domain\User\RegisterUser;
use Todo\Infrastructure\Http\CommandRequestHandler;
use Todo\Infrastructure\Http\ResponseFactory;
use Todo\Infrastructure\Middleware\JsonRequestParameterParser;

final class Application extends App
{
    private const COMMAND_NAME_ARG = 'commandName';

    public function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions(__DIR__ . '/../../config/api.php');

        ResponseFactory::setResponseFactory(new Psr17Factory());

        parent::__construct(
            new ResponseFactory(),
            $containerBuilder->build()
        );

        $this->getRouteCollector()->setDefaultInvocationStrategy(new RequestHandler());

        $this->loadRoutes();
        $this->add(JsonRequestParameterParser::class);
    }

    private function loadRoutes()
    {
        $this->post('/users', RegisterUserCommandRequestHandler::class)
            ->setArgument(self::COMMAND_NAME_ARG, RegisterUser::class);
        $this->post('/todos', PlanTodoCommandRequestHandler::class)
            ->setArgument(self::COMMAND_NAME_ARG, PlanTodo::class);
        $this->put('/todos', CommandRequestHandler::class)
            ->setArgument(self::COMMAND_NAME_ARG, AssignTodo::class);

        $this->get('/todos', TodoOverviewRequestHandler::class);
    }
}
