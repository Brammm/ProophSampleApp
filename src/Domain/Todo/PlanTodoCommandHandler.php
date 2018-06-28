<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

final class PlanTodoCommandHandler
{
    /**
     * @var TodoRepository
     */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function __invoke(PlanTodo $planTodo): void
    {
        $todo = Todo::plan($planTodo->todoId(), $planTodo->description());

        $this->todoRepository->save($todo);
    }
}
