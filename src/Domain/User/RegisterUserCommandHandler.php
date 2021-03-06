<?php

declare(strict_types=1);

namespace Todo\Domain\User;

final class RegisterUserCommandHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(RegisterUser $command): void
    {
        $user = User::registerUser(
            $command->userId(),
            $command->email(),
            $command->password()
        );

        $this->userRepository->save($user);
    }
}
