<?php

namespace Todo\Tests\Unit\Domain\Todo;

use Todo\Domain\Todo\Todo;
use Todo\Domain\Todo\TodoId;
use Todo\Domain\Todo\TodoWasPlanned;
use Todo\Tests\TestCase;

class TodoTest extends TestCase
{
    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var string
     */
    private $description;

    public function setUp()
    {
        $this->todoId = TodoId::create();
        $this->description = 'Todo description';
    }

    public function testItPlansATodo(): void
    {
        $user = Todo::plan($this->todoId, $this->description);

        $events = $this->popRecordedEvents($user);

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertTrue(assert($event instanceof TodoWasPlanned));
        $this->assertSame(TodoWasPlanned::class, $event->messageName());
        $this->assertEquals($this->todoId, $event->todoId());
        $this->assertEquals($this->description, $event->description());
    }
}
