<?php

declare(strict_types=1);

namespace Todo\Api\Http\Todo;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

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
            return new JsonResponse(['error' => 'Failed to get todos'], 500);
        }
        $stmt->execute();

        return new JsonResponse($stmt->fetchAll());
    }
}
