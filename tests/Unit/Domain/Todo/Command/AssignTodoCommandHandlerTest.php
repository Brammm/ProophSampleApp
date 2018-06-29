<?php

namespace Todo\Tests\Unit\Domain\Todo\Command;

use PHPUnit\Framework\MockObject\MockObject;
use Todo\Domain\Todo\Command\AssignTodo;
use Todo\Domain\Todo\Command\AssignTodoCommandHandler;
use Todo\Domain\Todo\TodoId;
use Todo\Domain\Todo\TodoRepository;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;
use Todo\Tests\TestCase;
use Todo\Tests\TodoEventsTrait;
use Todo\Tests\UserEventsTrait;

class AssignTodoCommandHandlerTest extends TestCase
{
    use TodoEventsTrait;
    use UserEventsTrait;

    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var TodoRepository|MockObject
     */
    private $todoRepo;

    /**
     * @var UserRepository|MockObject
     */
    private $userRepo;

    public function setUp(): void
    {
        $this->todoId = TodoId::generate();
        $this->userId = UserId::generate();
        $this->todoRepo = $this->createMock(TodoRepository::class);
        $this->userRepo = $this->createMock(UserRepository::class);
    }

    public function testAssignsUserToTodo(): void
    {
        $todo = $this->reconstituteTodo([$this->todoWasPlanned($this->todoId)]);
        $this->todoRepo->expects($this->once())
            ->method('findOneByTodoId')
            ->with($this->todoId)
            ->willReturn($todo);

        $user = $this->reconstituteUser([$this->userHasRegistered($this->userId)]);
        $this->userRepo->expects($this->once())
            ->method('findOneByUserId')
            ->with($this->userId)
            ->willReturn($user);

        $this->todoRepo->expects($this->once())
            ->method('save');

        $handler = new AssignTodoCommandHandler($this->todoRepo, $this->userRepo);
        $handler(new AssignTodo([
            'todoId' => (string) $this->todoId,
            'userId' => (string) $this->userId,
        ]));

        $this->assertTrue($todo->assignedTo()->equals($this->userId));
    }
}
