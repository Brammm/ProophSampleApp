<?php

declare(strict_types=1);

namespace Todo\Infrastructure\ValueObject;

use Ramsey\Uuid\Uuid as UuidLib;

class Uuid extends StringObject
{
    private function __construct(string $uuid)
    {
        parent::__construct($uuid);
    }

    /**
     * @return static
     */
    public static function generate(): self
    {
        return new static((string) UuidLib::uuid4());
    }

    /**
     * @param string $uuid
     *
     * @return static
     */
    public static function fromString(string $uuid): self
    {
        if (!UuidLib::isValid($uuid)) {
            throw new \InvalidArgumentException('Given UserId is not a valid UUID');
        }

        return new static($uuid);
    }
}
