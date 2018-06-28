<?php

namespace Todo\Tests\Unit\Infrastructure\ValueObject;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UuidTest extends TestCase
{
    public function testTwoDifferentInstancesAreNotEqual(): void
    {
        $uuid = Uuid::uuid4();
        $fooId = FooId::fromString($uuid);
        $barId = BarId::fromString($uuid);

        $this->assertFalse($fooId->equals($barId));
    }

    public function testIsAValidUuid(): void
    {
        $fooId = FooId::generate();

        $this->assertTrue(Uuid::isValid((string) $fooId));
    }
}
