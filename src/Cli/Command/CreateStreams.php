<?php

declare(strict_types=1);

namespace Todo\Cli\Command;

use ArrayIterator;
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

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function __invoke(OutputInterface $output): void
    {
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
