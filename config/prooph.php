<?php

declare(strict_types=1);

namespace {

    use Prooph\Common\Messaging\FQCNMessageFactory;
    use Prooph\EventSourcing\Aggregate\AggregateType;
    use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
    use Prooph\EventStore\EventStore;
    use Prooph\EventStore\Pdo\MySqlEventStore;
    use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlSingleStreamStrategy;
    use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
    use Prooph\EventStore\Projection\ProjectionManager;
    use Prooph\EventStore\StreamName;
    use Prooph\ServiceBus\CommandBus;
    use Prooph\ServiceBus\Plugin\Router\CommandRouter;
    use Prooph\ServiceBus\Plugin\ServiceLocatorPlugin;
    use Psr\Container\ContainerInterface;
    use Todo\Domain\Todo\Command\AssignTodo;
    use Todo\Domain\Todo\Command\AssignTodoCommandHandler;
    use Todo\Domain\Todo\Command\PlanTodo;
    use Todo\Domain\Todo\Command\PlanTodoCommandHandler;
    use Todo\Domain\Todo\EventStoreTodoRepository;
    use Todo\Domain\Todo\Todo;
    use Todo\Domain\Todo\TodoRepository;
    use Todo\Domain\User\Command\RegisterUser;
    use Todo\Domain\User\Command\RegisterUserCommandHandler;
    use Todo\Domain\User\EventStoreUserRepository;
    use Todo\Domain\User\User;
    use Todo\Domain\User\UserRepository;

    return [
        CommandBus::class => function (ContainerInterface $container) {
            $router = new CommandRouter([
                RegisterUser::class => RegisterUserCommandHandler::class,
                PlanTodo::class => PlanTodoCommandHandler::class,
                AssignTodo::class => AssignTodoCommandHandler::class,
            ]);

            $commandBus = new CommandBus();
            (new ServiceLocatorPlugin($container))->attachToMessageBus($commandBus);
            $router->attachToMessageBus($commandBus);

            return $commandBus;
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
                new AggregateTranslator(),
                null,
                new StreamName('user-stream')
            );
        },

        TodoRepository::class => function (EventStore $eventStore) {
            return new EventStoreTodoRepository(
                $eventStore,
                AggregateType::fromAggregateRootClass(Todo::class),
                new AggregateTranslator(),
                null,
                new StreamName('todo-stream')
            );
        },

        ProjectionManager::class => function(EventStore $eventStore, PDO $pdo) {
            return new MySqlProjectionManager(
                $eventStore,
                $pdo
            );
        },
    ];
}
