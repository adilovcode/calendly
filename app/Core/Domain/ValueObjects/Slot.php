<?php

namespace App\Core\Domain\ValueObjects;

class Slot {
    public function __construct(
        private readonly string $time
    ) {}

    /**
     * @return string
     */
    public function getTime(): string {
        return $this->time;
    }
}