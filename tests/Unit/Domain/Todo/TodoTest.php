<?php

declare(strict_types=1);

namespace Todo\Tests\Unit\Domain\Todo;

use Todo\Domain\Todo\Todo;
use Todo\Domain\Todo\TodoId;
use Todo\Domain\Todo\TodoWasAssigned;
use Todo\Domain\Todo\TodoWasPlanned;
use Todo\Domain\User\User;
use Todo\Domain\User\UserHasRegistered;
use Todo\Domain\User\UserId;
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
        /** @var Todo $todo */
        $todo = $this->reconstituteAggregateFromHistory(Todo::class, [$this->todoWasPlanned()]);
        /** @var User $user */
        $user = $this->reconstituteAggregateFromHistory(User::class, [$this->userHasRegistered()]);

        $todo->assignTo($user);

        $events = $this->popRecordedEvents($todo);
        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertTrue(assert($event instanceof TodoWasAssigned));
        $this->assertSame(TodoWasAssigned::class, $event->messageName());
        $this->assertTrue($this->userId->equals($event->userId()));
    }

    private function todoWasPlanned(): TodoWasPlanned
    {
        return TodoWasPlanned::occur((string) $this->todoId, [
            'description' => $this->description,
        ]);
    }

    private function userHasRegistered(): UserHasRegistered
    {
        return UserHasRegistered::occur((string) $this->userId, [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);
    }
}
