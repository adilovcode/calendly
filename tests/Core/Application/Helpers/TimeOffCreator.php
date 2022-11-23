<?php

namespace Tests\Core\Application\Helpers;

use App\Core\Domain\Entities\ETimeOff;

trait TimeOffCreator {
    /**
     * @param array $attributes
     * @return ETimeOff
     */
    public function makeTimeOff(array $attributes = []): ETimeOff {
        return new ETimeOff(
            title: $attributes['title'] ?? 'Test',
            eventId: $attributes['event_id'] ?? 1,
            startTime: $attributes['start_time'] ?? '09:00',
            endTime: $attributes['end_time'] ?? '18:00'
        );
    }
}