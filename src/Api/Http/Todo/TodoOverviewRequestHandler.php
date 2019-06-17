<?php

declare(strict_types=1);

namespace Todo\Api\Http\Todo;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Todo\Infrastructure\Http\ResponseFactory;

final class TodoOverviewRequestHandler implements RequestHandlerInterface
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $sql = <<<SQL
            SELECT 
                   t.description, 
                   u.email AS assigned_to 
            FROM r_todos t 
            LEFT JOIN r_users u ON t.assignedTo = u.id
        SQL;

        try {
            $stmt = $this->connection->prepare($sql);
        } catch (DBALException $e) {
            return ResponseFactory::jsonResponse(['error' => 'Failed to get todos'], 500);
        }
        $stmt->execute();

        return ResponseFactory::jsonResponse($stmt->fetchAll());
    }
}
