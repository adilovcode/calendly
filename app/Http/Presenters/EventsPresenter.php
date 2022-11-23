<?php

namespace App\Http\Presenters;

use App\Core\Domain\ValueObjects\EventWithDays;
use App\Core\Domain\ValueObjects\Slot;
use App\Core\Domain\ValueObjects\WorkingDayWithSlots;

class EventsPresenter extends AnonymousPresenter {
    /**
     * @param EventWithDays $eventWithDays
     * @return array
     */
    public function body(EventWithDays $eventWithDays): array {
        return [
            'event' => [
                'name' => $eventWithDays->getEvent()->getName(),
                'duration' => $eventWithDays->getEvent()->getDuration()->getValue(),
                'description' => $eventWithDays->getEvent()->getDescription()
            ],
            'days' => array_map(
                fn(WorkingDayWithSlots $dayWithSlots) => [
                    'date' => $dayWithSlots->getWorkingDay()->getDate(),
                    'start_time' => $dayWithSlots->getWorkingDay()->getStartTime(),
                    'end_time' => $dayWithSlots->getWorkingDay()->getEndTime(),
                    'slots' => array_map(fn(Slot $slot) => $slot->getTime(), $dayWithSlots->getSlots())
                ],
                $eventWithDays->getWorkingDaysWithSlots()
            )
        ];
    }
}