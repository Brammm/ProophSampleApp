<?php

declare(strict_types=1);

namespace Todo\Cli\Command;

use Doctrine\DBAL\Connection;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Output\OutputInterface;
use Todo\Domain\User\UserHasRegistered;

final class UpcastHashPasswords
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var PersistenceStrategy
     */
    private $persistenceStrategy;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(EventStore $eventStore, PersistenceStrategy $persistenceStrategy, Connection $connection
    ) {
        $this->eventStore = $eventStore;
        $this->persistenceStrategy = $persistenceStrategy;
        $this->connection = $connection;
    }

    public function __invoke(OutputInterface $output): void
    {
        $streamName = new StreamName('user-stream');

        $streamEvents = $this->eventStore->load($streamName);

        $statement = $this->connection->prepare(
            sprintf('UPDATE `%s` SET payload = :payload', $this->persistenceStrategy->generateTableName($streamName))
        );

        foreach ($streamEvents as $no => $event) {
            if ($event instanceof UserHasRegistered) {
                $payload = $event->payload();

                $payload['password'] = password_hash($payload['password'], PASSWORD_ARGON2I);
                $statement->bindValue('payload', json_encode($payload));
                $statement->execute();
            }
        }
    }
}
