<?php

namespace App\Core\Domain\ValueObjects;

class TimeOff {
    public function __construct(
        private readonly string $name,
        private readonly string $startTime,
        private readonly string $endTime
    ) {}

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
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