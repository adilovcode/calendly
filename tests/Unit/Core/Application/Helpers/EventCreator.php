<?php

namespace Tests\Unit\Core\Application\Helpers;

use App\Core\Domain\Entities\EEvent;
use App\Core\Domain\ValueObjects\Minute;
use Carbon\Carbon;

trait EventCreator {
    /**
     * @param array $attributes
     * @return EEvent
     */
    public function makeEvent(array $attributes = []): EEvent {
        return new EEvent(
            name: $attributes['name'] ?? 'Test',
            description: $attributes['description'] ?? 'description',
            duration: Minute::from($attributes['duration'] ?? 30),
            bufferTime: Minute::from($attributes['duration'] ?? 10),
            bookableInAdvance: $attributes['end_date'] ?? 7,
            acceptPerSlot: $attributes['accepts_per_slot'] ?? 3
        );
    }
}