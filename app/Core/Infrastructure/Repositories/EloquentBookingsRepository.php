<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Application\Repositories\IBookingsRepository;
use App\Core\Domain\Entities\EBooking;
use App\Models\Booking;

class EloquentBookingsRepository implements IBookingsRepository {

    /**
     * @inheritDoc
     */
    public function fetchByEventId(string $eventId): array {
        return Booking::where('event_id', $eventId)->get()->map->toDomainEntity()->toArray();
    }

    /**
     * @inheritDoc
     */
    public function fetchByDateEventId(string $eventId, string $date): array {
        return Booking::where('event_id', $eventId)
            ->whereDate('booking_date', $date)
            ->get()->map->toDomainEntity()->toArray();
    }

    /**
     * @inheritDoc
     */
    public function store(EBooking $booking): void {
        Booking::create($booking->toDBArray());
    }
}