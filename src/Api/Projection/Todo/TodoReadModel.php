<?php

declare(strict_types=1);

namespace Todo\Api\Projection\Todo;

use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\AbstractReadModel;
use Todo\Api\Projection\Table;

final class TodoReadModel extends AbstractReadModel
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function init(): void
    {
        $table = Table::TODOS;

        $sql = <<<EOT
CREATE TABLE `$table` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `assignedTo` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `insertedAt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
EOT;
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    public function isInitialized(): bool
    {
        $sql = sprintf('SHOW TABLES LIKE \'%s\';', Table::TODOS);

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetch();

        return $result !== false;
    }

    public function reset(): void
    {
        $sql = sprintf('TRUNCATE TABLE `%s`;', Table::TODOS);

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    public function delete(): void
    {
        $sql = sprintf('DROP TABLE `%s`;', Table::TODOS);

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    protected function insert(array $data): void
    {
        $this->connection->insert(Table::TODOS, $data);
    }

    protected function update(array $data, array $identifier): void
    {
        $this->connection->update(
            Table::TODOS,
            $data,
            $identifier
        );
    }
}
