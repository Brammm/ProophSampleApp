<?php

declare(strict_types=1);

namespace Todo\Cli\Command;

use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\EventStore\Projection\Projector;
use Symfony\Component\Console\Output\OutputInterface;
use Todo\Api\Projection\User\UserReadModel;
use Todo\Domain\User\UserHasRegistered;

final class ProjectUsers
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
        $readModel = new UserReadModel($this->connection);

        $projection = $this->projectionManager->createReadModelProjection('user', $readModel, [
            Projector::OPTION_PCNTL_DISPATCH => true,
        ]);

        pcntl_signal(SIGTERM, function () use ($projection, $output) {
            $output->writeln('Stopping...');
            $projection->stop();
        });

        $projection
            ->fromStream('user-stream')
            ->when([
                UserHasRegistered::class => function ($state, UserHasRegistered $event) use ($readModel) {
                    $readModel->stack('insert', [
                        'id' => (string) $event->userId(),
                        'email' => $event->email(),
                        'password' => password_hash((string) $event->password(), PASSWORD_ARGON2I),
                        'createdAt' => $event->createdAt()->format('Y-m-d H:i:s'),
                        'insertedAt' => date('Y-m-d H:i:s'),
                    ]);

                    return $state;
                },
            ])
            ->run();
    }
}
