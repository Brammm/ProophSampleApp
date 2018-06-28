<?php

declare(strict_types=1);

namespace Todo\Domain\Todo;

interface TodoRepository
{
    public function save(Todo $todo): void;

    public function findOneByTodoId(TodoId $todoId): Todo;
}
