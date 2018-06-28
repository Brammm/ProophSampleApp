<?php

namespace Todo\Tests\Unit\Infrastructure\ValueObject;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UuidTest extends TestCase
{
    public function testIsAValidUuid(): void
    {
        $fooId = FooId::generate();

        $this->assertTrue(Uuid::isValid((string) $fooId));
    }
}
