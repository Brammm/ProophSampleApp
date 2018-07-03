<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

use Todo\Domain\User\UserRepository;

final class AssignTodoCommandHandler
{
    /**
     * @var TodoRepository
     */
    private $todoRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(TodoRepository $todoRepository, UserRepository $userRepository)
    {
        $this->todoRepository = $todoRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(AssignTodo $command): void
    {
        $todo = $this->todoRepository->findOneByTodoId($command->todoId());
        $user = $this->userRepository->findOneByUserId($command->userId());

        $todo->assignTo($user);

        $this->todoRepository->save($todo);
    }
}
