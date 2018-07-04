<?php

declare(strict_types=1);

namespace Todo\Api\Projection\Todo;

use Doctrine\DBAL\Connection;
use stdClass;
use Todo\Api\Projection\Table;

final class TodoFinder
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(string $todoId): ?stdClass
    {
        $statement = $this->connection->prepare(sprintf("SELECT * FROM `%s` WHERE id = :id", Table::TODOS));
        $statement->bindValue('id', $todoId);
        $statement->execute();

        $result = $statement->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }
}
