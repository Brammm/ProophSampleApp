<?php

declare(strict_types=1);

namespace Todo\Cli;

use DI\ContainerBuilder;
use Silly\Edition\PhpDi\Application as CliApplication;
use Todo\Cli\Command\CreateStreams;
use Todo\Cli\Command\ProjectTodos;
use Todo\Cli\Command\ProjectUsers;
use Todo\Cli\Command\ResetProjections;
use Todo\Cli\Command\UpcastHashPasswords;

final class Application extends CliApplication
{
    public function __construct()
    {
        parent::__construct();

        $this->command('streams:create', CreateStreams::class);
        $this->command('projections:project:todos', ProjectTodos::class);
        $this->command('projections:project:users', ProjectUsers::class);
        $this->command('projections:reset', ResetProjections::class);
        $this->command('upcast:hash-passwords', UpcastHashPasswords::class);
    }

    protected function createContainer()
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../../config/shared.php');

        return $builder->build();
    }
}
