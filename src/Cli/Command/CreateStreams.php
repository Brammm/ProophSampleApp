<?php

declare(strict_types=1);

namespace Todo\Cli\Command;

use ArrayIterator;
use PDO;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Exception\StreamExistsAlready;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateStreams
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var array
     */
    private $streams = [
        'todo-stream',
        'user-stream',
    ];

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo, EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
        $this->pdo = $pdo;
    }

    public function __invoke(OutputInterface $output): void
    {
        $this->pdo->exec(file_get_contents(__DIR__ . '/../../../vendor/prooph/pdo-event-store/scripts/mysql/01_event_streams_table.sql'));
        $this->pdo->exec(file_get_contents(__DIR__ . '/../../../vendor/prooph/pdo-event-store/scripts/mysql/02_projections_table.sql'));

        foreach ($this->streams as $stream) {
            try {
                $this->eventStore->create(new Stream(new StreamName($stream), new ArrayIterator()));
                $output->writeln(sprintf('Created stream %s', $stream));
            } catch (StreamExistsAlready $e) {
                $output->writeln(sprintf('Stream %s already exists.', $stream));
                continue;
            }
        }
        $output->writeln('Done.');
    }
}
