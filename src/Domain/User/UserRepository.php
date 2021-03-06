<?php

declare(strict_types=1);

namespace Todo\Domain\User;

interface UserRepository
{
    public function save(User $user): void;

    public function findOneByUserId(UserId $userId): User;
}
