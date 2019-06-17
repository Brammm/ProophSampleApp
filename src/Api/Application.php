<?php

declare(strict_types=1);

namespace Todo\Api;

use DI\ContainerBuilder;
use Exception;
use Slim\App;
use Todo\Api\Http\Todo\PlanTodoCommandRequestHandler;
use Todo\Api\Http\User\RegisterUserCommandRequestHandler;
use Todo\Domain\Todo\AssignTodo;
use Todo\Domain\Todo\PlanTodo;
use Todo\Domain\User\RegisterUser;
use Todo\Infrastructure\Http\CommandRequestHandler;
use Todo\Infrastructure\Middleware\JsonRequestParameterParser;
use Zend\Diactoros\ResponseFactory;

final class Application extends App
{
    private const COMMAND_NAME_ARG = 'commandName';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions(__DIR__ . '/../../config/api.php');

        parent::__construct(
            new ResponseFactory(),
            $containerBuilder->build()
        );

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
    }
}
