<?php

namespace Todo\Tests\Unit\Infrastructure\ValueObject;

use PHPUnit\Framework\TestCase;
use Todo\Infrastructure\ValueObject\StringObject;

class StringObjectTest extends TestCase
{
    public function testCanTellIfObjectsAreEqual(): void
    {
        $one = new Foo('foo');
        $other = new Foo('foo');

        $this->assertTrue($one->equals($other));
    }

    /**
     * @dataProvider equalsProvider
     */
    public function testCanTellIfObjectsAreNotEqual(StringObject $one, StringObject $other): void
    {
        $this->assertFalse($one->equals($other));
    }

    public function equalsProvider(): array
    {
        return [
            [new Foo('foo'), new Foo('bar')],
            [new Foo('foo'), new Bar('foo')],
        ];
    }
}
