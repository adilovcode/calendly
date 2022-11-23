<?php

namespace App\Core\Domain\ValueObjects;

class Minute {
    public function __construct(
        private readonly int $value
    ) {}

    /**
     * @return int
     */
    public function getValue(): int {
        return $this->value;
    }

    public static function from(int $minutes): self {
        return new self($minutes);
    }
}