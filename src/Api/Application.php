<?php

declare(strict_types=1);

namespace Todo\Api;

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;
use Todo\Api\Http\Todo\PostTodos;
use Todo\Api\Http\Todo\PutTodoAssign;
use Todo\Api\Http\User\PostUsers;

final class Application extends App
{
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
        $this->post('/users', PostUsers::class);
        $this->post('/todos', PostTodos::class);
        $this->put('/todos', PutTodoAssign::class);
    }
}
