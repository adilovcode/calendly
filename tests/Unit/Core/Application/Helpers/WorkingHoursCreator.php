<?php

namespace Tests\Unit\Core\Application\Helpers;

use App\Core\Domain\Entities\EWorkingDay;

trait WorkingHoursCreator {
    /**
     * @param array $attributes
     * @return EWorkingDay
     */
    public function makeWorkingHours(array $attributes = []): EWorkingDay {
        return new EWorkingDay(
            eventId: $attributes['event_id'] ?? 1,
            day: $attributes['day'] ?? 1,
            startTime: $attributes['start_time'] ?? '09:00',
            endTime: $attributes['end_time'] ?? '18:00'
        );
    }
}