<?php

namespace Verhuur\Domain\User;

use Ramsey\Uuid\Uuid;

final class UserId
{
    /**
     * @var string
     */
    private $uuid;

    private function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $uuid): self
    {
        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException('Given UserId is not a valid UUID');
        }

        return new self($uuid);
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
