<?php

declare(strict_types=1);

namespace {

    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\DriverManager;

    return array_merge(

        require('prooph.php'),

        [
            PDO::class => function () {
                return new PDO(
                    sprintf('pgsql:host=%s;port=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME')),
                    getenv('DB_USERNAME'),
                    getenv('DB_PASSWORD')
                );
            },

            Connection::class => function(PDO $pdo) {
                return DriverManager::getConnection([
                    'pdo' => $pdo,
                ]);
            },

            Swift_Mailer::class => function() {
                return new Swift_Mailer(new Swift_SmtpTransport(
                    getenv('SMTP_HOST'),
                    getenv('SMTP_PORT')
                ));
            }
        ]
    );
}
