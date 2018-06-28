<?php

declare(strict_types=1);

namespace Todo\Tests\Unit\Domain\Todo;

use Prooph\EventSourcing\AggregateChanged;
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
        $this->assertEquals($this->todoId, $event->todoId());
        $this->assertEquals($this->description, $event->description());
    }

    public function testItCanAssignAUser(): void
    {
        $todo = $this->reconstituteTodo($this->todoWasPlanned());
        $user = $this->reconstituteUser($this->userHasRegistered());

        $todo->assignTo($user);

        $events = $this->popRecordedEvents($todo);
        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertTrue(assert($event instanceof TodoWasAssigned));
        $this->assertSame(TodoWasAssigned::class, $event->messageName());
        $this->assertEquals($this->userId, $event->userId());
    }

    private function reconstituteTodo(AggregateChanged ...$events): Todo
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->reconstituteAggregateFromHistory(Todo::class, $events);
    }

    private function reconstituteUser(AggregateChanged ...$events): User
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->reconstituteAggregateFromHistory(User::class, $events);
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
