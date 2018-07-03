<?php

declare(strict_types=1);

namespace {

    use Prooph\Common\Event\ProophActionEventEmitter;
    use Prooph\Common\Messaging\FQCNMessageFactory;
    use Prooph\EventSourcing\Aggregate\AggregateType;
    use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
    use Prooph\EventStore\ActionEventEmitterEventStore;
    use Prooph\EventStore\EventStore;
    use Prooph\EventStore\Pdo\MySqlEventStore;
    use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlSingleStreamStrategy;
    use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
    use Prooph\EventStore\Projection\ProjectionManager;
    use Prooph\EventStore\StreamName;
    use Prooph\EventStoreBusBridge\EventPublisher;
    use Prooph\ServiceBus\CommandBus;
    use Prooph\ServiceBus\EventBus;
    use Prooph\ServiceBus\Plugin\Router\CommandRouter;
    use Prooph\ServiceBus\Plugin\Router\EventRouter;
    use Prooph\ServiceBus\Plugin\ServiceLocatorPlugin;
    use Psr\Container\ContainerInterface;
    use Todo\Domain\Todo\AssignTodo;
    use Todo\Domain\Todo\AssignTodoCommandHandler;
    use Todo\Domain\Todo\EventStoreTodoRepository;
    use Todo\Domain\Todo\PlanTodo;
    use Todo\Domain\Todo\PlanTodoCommandHandler;
    use Todo\Domain\Todo\Todo;
    use Todo\Domain\Todo\TodoRepository;
    use Todo\Domain\Todo\TodoWasAssigned;
    use Todo\Domain\User\EventStoreUserRepository;
    use Todo\Domain\User\NotifyUserOfAssignment;
    use Todo\Domain\User\NotifyUserOfAssignmentCommandHandler;
    use Todo\Domain\User\NotifyUserOfAssignmentProcessManager;
    use Todo\Domain\User\RegisterUser;
    use Todo\Domain\User\RegisterUserCommandHandler;
    use Todo\Domain\User\User;
    use Todo\Domain\User\UserRepository;

    return [
        CommandBus::class => function (ContainerInterface $container) {
            $router = new CommandRouter([
                RegisterUser::class => RegisterUserCommandHandler::class,
                PlanTodo::class => PlanTodoCommandHandler::class,
                AssignTodo::class => AssignTodoCommandHandler::class,
                NotifyUserOfAssignment::class => NotifyUserOfAssignmentCommandHandler::class,
            ]);

            $commandBus = new CommandBus();
            (new ServiceLocatorPlugin($container))->attachToMessageBus($commandBus);
            $router->attachToMessageBus($commandBus);

            return $commandBus;
        },

        EventBus::class => function (ContainerInterface $container) {
            $router = new EventRouter([
                TodoWasAssigned::class => [
                    NotifyUserOfAssignmentProcessManager::class,
                ],
            ]);

            $eventBus = new EventBus();
            (new ServiceLocatorPlugin($container))->attachToMessageBus($eventBus);
            $router->attachToMessageBus($eventBus);

            return $eventBus;
        },

        EventStore::class => function (PDO $pdo, EventBus $eventBus) {
            $eventStore = new MySqlEventStore(
                new FQCNMessageFactory(),
                $pdo,
                new MySqlSingleStreamStrategy()
            );

            $eventStore = new ActionEventEmitterEventStore(
                $eventStore,
                new ProophActionEventEmitter(ActionEventEmitterEventStore::ALL_EVENTS)
            );

            $eventPublisher = new EventPublisher($eventBus);
            $eventPublisher->attachToEventStore($eventStore);

            return $eventStore;
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

        ProjectionManager::class => function (EventStore $eventStore, PDO $pdo) {
            return new MySqlProjectionManager(
                $eventStore,
                $pdo
            );
        },
    ];
}
