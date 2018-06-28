<?php

declare(strict_types=1);

namespace {

    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\DriverManager;

    return array_merge(

        require('prooph.php'),

        [
            'settings.displayErrorDetails' => true,

            PDO::class => function () {
                return new PDO(
                    sprintf('mysql:host=%s;port=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME')),
                    getenv('DB_USERNAME'),
                    getenv('DB_PASSWORD')
                );
            },

            Connection::class => function(PDO $pdo) {
                return DriverManager::getConnection([
                    'pdo' => $pdo,
                ]);
            },
        ]
    );
}
