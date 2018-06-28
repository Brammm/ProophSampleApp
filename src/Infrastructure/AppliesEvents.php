<?php

declare(strict_types=1);

namespace Todo\Infrastructure;

use Prooph\EventSourcing\AggregateChanged;
use RuntimeException;

trait AppliesEvents
{
    /**
     * Apply given event
     */
    protected function apply(AggregateChanged $event): void
    {
        $handler = $this->determineEventHandlerMethodFor($event);

        if (! method_exists($this, $handler)) {
            throw new RuntimeException(sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                get_class($this)
            ));
        }

        $this->{$handler}($event);
    }

    private function determineEventHandlerMethodFor(AggregateChanged $e): string
    {
        return 'when' . implode(array_slice(explode('\\', get_class($e)), -1));
    }
}
