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
        $this->projectionManager->resetProjection('todo');
        $output->writeln('Resetting user');
        $this->projectionManager->resetProjection('user');
        $output->writeln('Done');
    }
}
