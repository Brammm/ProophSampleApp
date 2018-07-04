<?php

declare(strict_types=1);

namespace Todo\Cli\Command;

use Prooph\EventStore\Projection\ProjectionManager;
use Symfony\Component\Console\Output\OutputInterface;

final class ResetProjections
{
    /**
     * @var ProjectionManager
     */
    private $projectionManager;

    public function __construct(ProjectionManager $projectionManager)
    {
        $this->projectionManager = $projectionManager;
    }

    public function __invoke(OutputInterface $output): void
    {
        $output->writeln('Resetting todo');
        $this->projectionManager->deleteProjection('todo', true);
        $output->writeln('Resetting user');
        $this->projectionManager->deleteProjection('user', true);
        $output->writeln('Done');
    }
}
