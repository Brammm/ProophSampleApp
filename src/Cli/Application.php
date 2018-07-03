<?php

declare(strict_types=1);

namespace Todo\Cli;

use DI\ContainerBuilder;
use Silly\Edition\PhpDi\Application as CliApplication;
use Todo\Cli\Command\CreateDatabase;
use Todo\Cli\Command\CreateStreams;
use Todo\Cli\Command\ProjectTodos;

final class Application extends CliApplication
{
    public function __construct()
    {
        parent::__construct();

        $this->command('database:create', CreateDatabase::class);
        $this->command('streams:create', CreateStreams::class);
        $this->command('project:todos', ProjectTodos::class);
    }

    protected function createContainer()
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../../config/shared.php');

        return $builder->build();
    }
}
