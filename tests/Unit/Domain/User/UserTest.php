<?php

declare(strict_types=1);

namespace Todo\Tests\Unit\Domain\User;

use Todo\Domain\User\Email;
use Todo\Domain\User\Password;
use Todo\Domain\User\User;
use Todo\Domain\User\UserHasRegistered;
use Todo\Domain\User\UserId;
use Todo\Tests\TestCase;

class UserTest extends TestCase
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

    public function setUp(): void
    {
        $this->userId = UserId::generate();
        $this->email = new Email('john@example.com');
        $this->password = new Password('password');
    }

    public function testItRegistersAUser(): void
    {
        $user = User::registerUser($this->userId, $this->email, $this->password);

        $events = $this->popRecordedEvents($user);

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertTrue(assert($event instanceof UserHasRegistered));
        $this->assertSame(UserHasRegistered::class, $event->messageName());
        $this->assertTrue($this->userId->equals($event->userId()));
        $this->assertTrue($this->email->equals($event->email()));
        $this->assertTrue($this->password->equals($event->password()));
    }
}
