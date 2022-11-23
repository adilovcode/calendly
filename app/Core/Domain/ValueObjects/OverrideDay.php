<?php

namespace App\Core\Domain\ValueObjects;

class OverrideDay {
    public function __construct(
        private readonly int $day,
        private readonly string $startTime,
        private readonly string $endTime
    ) {}

    /**
     * @return int
     */
    public function getDay(): int {
        return $this->day;
    }

    /**
     * @return string
     */
    public function getStartTime(): string {
        return $this->startTime;
    }

    /**
     * @return string
     */
    public function getEndTime(): string {
        return $this->endTime;
    }
}