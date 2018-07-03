<?php

declare(strict_types=1);

namespace {

    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\DriverManager;
    use Zend\Mail\Transport\Smtp;
    use Zend\Mail\Transport\SmtpOptions;
    use Zend\Mail\Transport\TransportInterface;

    return array_merge(

        require('prooph.php'),

        [
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

            TransportInterface::class => function() {
                return new Smtp(new SmtpOptions([
                    'host' => getenv('SMTP_HOST'),
                    'port' => getenv('SMTP_PORT'),
                ]));
            }
        ]
    );
}
