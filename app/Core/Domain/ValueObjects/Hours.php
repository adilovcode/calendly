<?php

namespace App\Core\Domain\ValueObjects;

class Hours {
    public function __construct(
        private readonly string $startTime,
        private readonly string $endTime
    ) {}

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