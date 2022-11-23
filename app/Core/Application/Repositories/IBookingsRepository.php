<?php

namespace App\Core\Application\Repositories;

use App\Core\Domain\Entities\EBooking;

interface IBookingsRepository {
    /**
     * @param string $eventId
     * @return EBooking[]
     */
    public function fetchByEventId(string $eventId): array;

    /**
     * @param string $eventId
     * @param string $date
     * @return array
     */
    public function fetchByDateEventId(string $eventId, string $date): array;

    /**
     * @param EBooking $booking
     * @return void
     */
    public function store(EBooking $booking): void;
}