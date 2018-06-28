<?php

declare(strict_types=1);

namespace {

    use Prooph\Common\Messaging\FQCNMessageFactory;
    use Prooph\EventSourcing\Aggregate\AggregateType;
    use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
    use Prooph\EventStore\EventStore;
    use Prooph\EventStore\Pdo\MySqlEventStore;
    use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlSingleStreamStrategy;
    use Prooph\ServiceBus\CommandBus;
    use Prooph\ServiceBus\Plugin\Router\CommandRouter;
    use Prooph\ServiceBus\Plugin\ServiceLocatorPlugin;
    use Psr\Container\ContainerInterface;
    use Todo\Domain\User\EventStoreUserRepository;
    use Todo\Domain\User\RegisterUser;
    use Todo\Domain\User\RegisterUserCommandHandler;
    use Todo\Domain\User\User;
    use Todo\Domain\User\UserRepository;

    return [
        CommandBus::class => function (ContainerInterface $container) {
            $router = new CommandRouter([
                RegisterUser::class => RegisterUserCommandHandler::class,
            ]);

            $commandBus = new CommandBus();
            (new ServiceLocatorPlugin($container))->attachToMessageBus($commandBus);
            $router->attachToMessageBus($commandBus);

            return $commandBus;
        },

        PDO::class => function () {
            return new PDO(
                sprintf('mysql:host=%s;port=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME')),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );
        },

        EventStore::class => function (PDO $pdo) {
            return new MySqlEventStore(
                new FQCNMessageFactory(),
                $pdo,
                new MySqlSingleStreamStrategy()
            );
        },

        UserRepository::class => function (EventStore $eventStore) {
            return new EventStoreUserRepository(
                $eventStore,
                AggregateType::fromAggregateRootClass(User::class),
                new AggregateTranslator()
            );
        },
    ];
}
