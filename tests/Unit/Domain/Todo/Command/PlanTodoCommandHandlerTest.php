<?php

declare(strict_types=1);

namespace Todo\Tests\Unit\Domain\Todo\Command;

use PHPUnit\Framework\MockObject\MockObject;
use Todo\Domain\Todo\Command\PlanTodo;
use Todo\Domain\Todo\Command\PlanTodoCommandHandler;
use Todo\Domain\Todo\Todo;
use Todo\Domain\Todo\TodoId;
use Todo\Domain\Todo\TodoRepository;
use Todo\Tests\TestCase;

class PlanTodoCommandHandlerTest extends TestCase
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
     * @var TodoRepository|MockObject
     */
    private $todoRepo;

    public function setUp(): void
    {
        $this->todoId = TodoId::generate();
        $this->description = 'Todo description';
        $this->todoRepo = $this->createMock(TodoRepository::class);
    }

    public function testPlansTodo(): void
    {
        $this->todoRepo->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Todo $todo) {
                if (! $this->todoId->equals($todo->todoId())) {
                    return false;
                }

                if ($this->description !== $todo->description()) {
                    return false;
                }

                return true;
            }));

        $handler = new PlanTodoCommandHandler($this->todoRepo);
        $handler(new PlanTodo([
            'todoId' => (string) $this->todoId,
            'description' => $this->description,
        ]));
    }
}
