<?php

declare(strict_types=1);

namespace Todo\Cli\Command;

use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\EventStore\Projection\Projector;
use Symfony\Component\Console\Output\OutputInterface;
use Todo\Application\Projection\Todo\TodoReadModel;
use Todo\Domain\Todo\Event\TodoWasAssigned;
use Todo\Domain\Todo\Event\TodoWasPlanned;

final class ProjectTodos
{
    /**
     * @var ProjectionManager
     */
    private $projectionManager;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(ProjectionManager $projectionManager, Connection $connection)
    {
        $this->projectionManager = $projectionManager;
        $this->connection = $connection;
    }

    public function __invoke(OutputInterface $output): void
    {
        $readModel = new TodoReadModel($this->connection);

        $projection = $this->projectionManager->createReadModelProjection('todo', $readModel, [
            Projector::OPTION_PCNTL_DISPATCH => true,
        ]);

        pcntl_signal(SIGINT, function () use ($projection, $output) {
            $output->writeln('Stopping...');
            $projection->stop();
        });

        $projection
            ->fromStream('todo-stream')
            ->when([
                TodoWasPlanned::class => function ($state, TodoWasPlanned $event) {
                    $this->readModel()->stack('insert', [
                        'id' => (string) $event->todoId(),
                        'description' => $event->description(),
                        'createdAt' => $event->createdAt()->format('Y-m-d H:i:s'),
                    ]);
                },
                TodoWasAssigned::class => function ($state, TodoWasAssigned $event) {
                    $this->readModel()->stack(
                        'update',
                        [
                            'assignedTo' => (string) $event->userId(),
                        ],
                        [
                            'id' => (string) $event->todoId(),
                        ]
                    );
                },
            ])
            ->run();
    }
}
