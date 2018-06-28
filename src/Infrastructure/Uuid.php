<?php

declare(strict_types=1);

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

    public function equals(Uuid $other): bool
    {
        return $this->uuid === $other->uuid && static::class === \get_class($other);
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
