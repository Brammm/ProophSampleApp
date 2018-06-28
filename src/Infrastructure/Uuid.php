<?php

namespace Todo\Infrastructure;

use Ramsey\Uuid\Uuid as UuidLib;

class Uuid
{
    /**
     * @var string
     */
    private $uuid;

    private function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): self
    {
        return new static(UuidLib::uuid4());
    }

    public static function fromString(string $uuid): self
    {
        if (!UuidLib::isValid($uuid)) {
            throw new \InvalidArgumentException('Given UserId is not a valid UUID');
        }

        return new static($uuid);
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
