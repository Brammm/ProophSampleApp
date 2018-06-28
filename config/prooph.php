<?php

declare(strict_types=1);

namespace {

    use Prooph\ServiceBus\CommandBus;
    use Prooph\ServiceBus\Plugin\Router\CommandRouter;
    use Prooph\ServiceBus\Plugin\ServiceLocatorPlugin;
    use Psr\Container\ContainerInterface;
    use Todo\Domain\User\RegisterUser;
    use Todo\Domain\User\RegisterUserCommandHandler;

    return [
        CommandBus::class => function(ContainerInterface $container) {
            $router = new CommandRouter([
                RegisterUser::class => RegisterUserCommandHandler::class,
            ]);

            $commandBus = new CommandBus();
            (new ServiceLocatorPlugin($container))->attachToMessageBus($commandBus);
            $router->attachToMessageBus($commandBus);

            return $commandBus;
        },
    ];
}
