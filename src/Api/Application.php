<?php

declare(strict_types=1);

namespace Todo\Api;

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;
use Todo\Api\Http\Todo\PlanTodoCommandRequestHandler;
use Todo\Api\Http\User\RegisterUserCommandRequestHandler;
use Todo\Domain\Todo\Command\AssignTodo;
use Todo\Domain\Todo\Command\PlanTodo;
use Todo\Domain\User\Command\RegisterUser;
use Todo\Infrastructure\Http\CommandRequestHandler;

final class Application extends App
{
    private const COMMAND_NAME_ARG = 'commandName';

    public function __construct()
    {
        parent::__construct();

        $this->loadRoutes();
    }

    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions(__DIR__ . '/../../config/api.php');
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
