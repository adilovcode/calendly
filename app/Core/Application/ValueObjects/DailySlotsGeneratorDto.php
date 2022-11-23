<?php

namespace App\Core\Application\ValueObjects;

use App\Core\Domain\Entities\EBooking;
use App\Core\Domain\Entities\ETimeOff;
use App\Core\Domain\ValueObjects\Minute;

class DailySlotsGeneratorDto {
    public function __construct(
        private readonly string $startTime,
        private readonly string $endTime,
        private readonly Minute $duration,
        private readonly array $timeOffs,
        private readonly array $bookings,
        private readonly int $acceptsPerSlot
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

    /**
     * @return Minute
     */
    public function getDuration(): Minute {
        return $this->duration;
    }

    /**
     * @return ETimeOff[]
     */
    public function getTimeOffs(): array {
        return $this->timeOffs;
    }

    /**
     * @return EBooking[]
     */
    public function getBookings(): array {
        return $this->bookings;
    }

    /**
     * @return int
     */
    public function getAcceptsPerSlot(): int {
        return $this->acceptsPerSlot;
    }
}