<?php

namespace Todo\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;

class TestCase extends PHPUnitTestCase
{
    /**
     * @var AggregateTranslator
     */
    private $aggregateTranslator;

    protected function popRecordedEvents(AggregateRoot $aggregateRoot): array
    {
        return $this->getAggregateTranslator()->extractPendingStreamEvents($aggregateRoot);
    }

    protected function reconstituteAggregateFromHistory(string $aggregateRootClass, array $events): object
    {
        return $this->getAggregateTranslator()->reconstituteAggregateFromHistory(
            AggregateType::fromAggregateRootClass($aggregateRootClass),
            new \ArrayIterator($events)
        );
    }

    private function getAggregateTranslator(): AggregateTranslator
    {
        if (null === $this->aggregateTranslator) {
            $this->aggregateTranslator = new AggregateTranslator();
        }

        return $this->aggregateTranslator;
    }
}
