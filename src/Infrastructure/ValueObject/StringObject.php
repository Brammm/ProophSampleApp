<?php

declare(strict_types=1);

namespace Todo\Infrastructure\ValueObject;

abstract class StringObject
{
    /**
     * @var string
     */
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString(): string
    {
        return $this->string;
    }

    public function equals(StringObject $other): bool
    {
        return $this->string === $other->string && static::class === \get_class($other);
    }
}
