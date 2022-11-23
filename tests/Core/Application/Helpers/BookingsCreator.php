<?php

namespace Tests\Core\Application\Helpers;

use App\Core\Domain\Entities\EBooking;
use Carbon\Carbon;

trait BookingsCreator {
    /**
     * @param array $attributes
     * @return EBooking
     */
    public function makeBooking(array $attributes = []): EBooking {
        return new EBooking(
            eventId: $attributes['event_id'] ?? 1,
            firstName: $attributes['first_name'] ?? 'Umar',
            lastName: $attributes['last_name'] ?? 'Adilov',
            email: $attributes['email'] ?? 'adilovcode@gmail.com',
            bookingDate: $attributes['booking_date'] ?? Carbon::now()
        );
    }
}