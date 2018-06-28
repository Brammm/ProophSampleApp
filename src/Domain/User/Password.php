<?php

declare(strict_types=1);

namespace Todo\Domain\User;

final class Password
{
    /**
     * @var string
     */
    private $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function __toString(): string
    {
        return $this->password;
    }

    public function equals(Password $other): bool
    {
        return $this->password === $other->password;
    }
}
