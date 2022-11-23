<?php

namespace App\Core\Domain\ValueObjects;

class WorkingDayWithSlots {
    public function __construct(
        private readonly WorkingDay $workingDay,
        private readonly array $slots = []
    ) {}

    /**
     * @return array
     */
    public function getSlots(): array {
        return $this->slots;
    }

    /**
     * @return WorkingDay
     */
    public function getWorkingDay(): WorkingDay {
        return $this->workingDay;
    }
}