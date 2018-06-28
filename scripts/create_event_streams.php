<?php

declare(strict_types=1);

namespace {

    use Dotenv\Dotenv;
    use Prooph\EventStore\EventStore;
    use Prooph\EventStore\Stream;
    use Prooph\EventStore\StreamName;
    use Todo\Application\Application;

    require_once __DIR__ . '/../vendor/autoload.php';

    (new Dotenv(__DIR__ . '/..'))->load();
    $container = (new Application())->getContainer();

    $eventStore = $container->get(EventStore::class);

    $eventStore->create(new Stream(new StreamName('todo-stream'), new ArrayIterator()));
    $eventStore->create(new Stream(new StreamName('user-stream'), new ArrayIterator()));
    echo 'done.';
}
