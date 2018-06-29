<?php

namespace Todo\Tests\Unit\Domain\User\Command;

use PHPUnit\Framework\MockObject\MockObject;
use Todo\Domain\User\Command\RegisterUser;
use Todo\Domain\User\Command\RegisterUserCommandHandler;
use Todo\Domain\User\Email;
use Todo\Domain\User\Password;
use Todo\Domain\User\User;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;
use Todo\Tests\TestCase;

class RegisterUserCommandHandlerTest extends TestCase
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var Password
     */
    private $password;

    /**
     * @var UserRepository|MockObject
     */
    private $userRepo;

    public function setUp(): void
    {
        $this->userId = UserId::generate();
        $this->email = new Email('john@example.com');
        $this->password = new Password('password');
        $this->userRepo = $this->createMock(UserRepository::class);
    }

    public function testRegistersUser(): void
    {
        $this->userRepo->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) {
                if (!$this->userId->equals($user->userId())) {
                    return false;
                }

                if (!$this->email->equals($user->email())) {
                    return false;
                }

                if (!$this->password->equals($user->password())) {
                    return false;
                }

                return true;
            }));

        $handler = new RegisterUserCommandHandler($this->userRepo);
        $handler(new RegisterUser([
            'userId' => (string) $this->userId,
            'email' => (string) $this->email,
            'password' => (string) $this->password,
        ]));
    }
}
