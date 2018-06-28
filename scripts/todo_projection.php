<?php

declare(strict_types=1);

namespace {

    use Doctrine\DBAL\Connection;
    use Dotenv\Dotenv;
    use Prooph\EventStore\Projection\ProjectionManager;
    use Todo\Application\Application;
    use Todo\Application\Projection\Todo\TodoReadModel;
    use Todo\Domain\Todo\Event\TodoWasAssigned;
    use Todo\Domain\Todo\Event\TodoWasPlanned;

    require_once __DIR__ . '/../vendor/autoload.php';

    (new Dotenv(__DIR__ . '/..'))->load();
    putenv('DB_HOST=127.0.0.1');
    $container = (new Application())->getContainer();

    $projectionManager = $container->get(ProjectionManager::class);

    $readModel = new TodoReadModel($container->get(Connection::class));

    $projection = $projectionManager->createReadModelProjection('todo', $readModel);

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
