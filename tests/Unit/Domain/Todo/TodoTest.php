<?php

declare(strict_types=1);

namespace Todo\Tests\Unit\Domain\Todo;

use Todo\Domain\Todo\Event\TodoWasAssigned;
use Todo\Domain\Todo\Event\TodoWasPlanned;
use Todo\Domain\Todo\Todo;
use Todo\Domain\Todo\TodoId;
use Todo\Domain\User\UserId;
use Todo\Tests\TestCase;
use Todo\Tests\TodoEventsTrait;
use Todo\Tests\UserEventsTrait;

class TodoTest extends TestCase
{
    use TodoEventsTrait;
    use UserEventsTrait;

    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var UserId
     */
    private $userId;

    public function setUp(): void
    {
        $this->todoId = TodoId::generate();
        $this->description = 'Todo description';
        $this->userId = UserId::generate();
    }

    public function testItPlansATodo(): void
    {
        $todo = Todo::plan($this->todoId, $this->description);

        $events = $this->popRecordedEvents($todo);

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertTrue(assert($event instanceof TodoWasPlanned));
        $this->assertSame(TodoWasPlanned::class, $event->messageName());
        $this->assertTrue($this->todoId->equals($event->todoId()));
        $this->assertSame($this->description, $event->description());
    }

    public function testItCanAssignAUser(): void
    {
        $todo = $this->reconstituteTodo([$this->todoWasPlanned($this->todoId, $this->description)]);
        $user = $this->reconstituteUser([$this->userHasRegistered($this->userId)]);

        $todo->assignTo($user);

        $events = $this->popRecordedEvents($todo);
        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertTrue(assert($event instanceof TodoWasAssigned));
        $this->assertSame(TodoWasAssigned::class, $event->messageName());
        $this->assertTrue($this->userId->equals($event->userId()));
    }
}
