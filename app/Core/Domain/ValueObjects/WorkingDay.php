<?php

namespace App\Core\Domain\ValueObjects;

class WorkingDay {
    public function __construct(
        private readonly string $date,
        private readonly string $startTime,
        private readonly string $endTime
    ) {}

    /**
     * @return string
     */
    public function getDate(): string {
        return $this->date;
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