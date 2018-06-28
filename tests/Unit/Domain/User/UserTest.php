<?php

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
        $this->assertEquals($this->userId, $event->userId());
        $this->assertEquals($this->email, $event->email());
        $this->assertEquals($this->password, $event->password());
    }
}
