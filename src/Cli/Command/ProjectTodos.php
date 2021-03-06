<?php

declare(strict_types=1);

namespace Todo\Cli\Command;

use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\EventStore\Projection\Projector;
use Symfony\Component\Console\Output\OutputInterface;
use Todo\Api\Projection\Todo\TodoReadModel;
use Todo\Domain\Todo\TodoWasAssigned;
use Todo\Domain\Todo\TodoWasPlanned;

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

        pcntl_signal(SIGTERM, function () use ($projection, $output) {
            $output->writeln('Stopping...');
            $projection->stop();
        });

        $projection
            ->fromStream('todo-stream')
            ->when([
                TodoWasPlanned::class => function ($state, TodoWasPlanned $event) use ($readModel) {
                    $readModel->stack('insert', [
                        'id' => (string) $event->todoId(),
                        'description' => $event->description(),
                        'createdAt' => $event->createdAt()->format('Y-m-d H:i:s'),
                        'insertedAt' => date('Y-m-d H:i:s'),
                    ]);

                    return $state;
                },
                TodoWasAssigned::class => function ($state, TodoWasAssigned $event) use ($readModel) {
                    $readModel->stack(
                        'update',
                        [
                            'assignedTo' => (string) $event->userId(),
                        ],
                        [
                            'id' => (string) $event->todoId(),
                        ]
                    );

                    return $state;
                },
            ])
            ->run();
    }
}
