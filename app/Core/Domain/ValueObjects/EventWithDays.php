<?php

namespace App\Core\Domain\ValueObjects;

use App\Core\Domain\Entities\EEvent;

class EventWithDays {
    public function __construct(
        private readonly EEvent $event,
        private readonly array $workingDaysWithSlots
    ) {}

    /**
     * @return EEvent
     */
    public function getEvent(): EEvent {
        return $this->event;
    }

    /**
     * @return WorkingDayWithSlots[]
     */
    public function getWorkingDaysWithSlots(): array {
        return $this->workingDaysWithSlots;
    }
}