<?php

declare(strict_types=1);

namespace Todo\Cli\Command;

use PDO;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateDatabase
{
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function __invoke(OutputInterface $output): void
    {
        $this->pdo->exec(file_get_contents(__DIR__ . '/../../../vendor/prooph/pdo-event-store/scripts/postgres/01_event_streams_table.sql'));
        $this->pdo->exec(file_get_contents(__DIR__ . '/../../../vendor/prooph/pdo-event-store/scripts/postgres/02_projections_table.sql'));
        $output->writeln('Done.');
    }
}
