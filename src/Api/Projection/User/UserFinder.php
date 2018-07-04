<?php

declare(strict_types=1);

namespace Todo\Api\Projection\User;

use Doctrine\DBAL\Connection;
use stdClass;
use Todo\Api\Projection\Table;

final class UserFinder
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(string $userId): ?stdClass
    {
        $statement = $this->connection->prepare(sprintf("SELECT * FROM `%s` WHERE id = :id", Table::USERS));
        $statement->bindValue('id', $userId);
        $statement->execute();

        $result = $statement->fetch();

        if ($result === false) {
            return null;
        }

        return $result;
    }
}
