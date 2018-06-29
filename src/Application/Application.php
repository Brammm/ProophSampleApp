<?php

declare(strict_types=1);

namespace Todo\Application;

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;
use Todo\Application\Http\Todo\PostTodos;
use Todo\Application\Http\Todo\PutTodoAssign;
use Todo\Application\Http\User\PostUsers;

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
