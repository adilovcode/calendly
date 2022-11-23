<?php

namespace App\Core\Application\Requests;

class BookingCreatorRequest implements IRequest {

    public function __construct(
        private readonly string $email,
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly string $bookingDate,
        private readonly string $eventId,
        private readonly int $slotsCount,
    ) {}

    /**
     * @return string
     */
    public function getEventId(): string {
        return $this->eventId;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getBookingDate(): string {
        return $this->bookingDate;
    }

    /**
     * @return int
     */
    public function getSlotsCount(): int {
        return $this->slotsCount;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return [
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'booking_date' => $this->getBookingDate(),
            'slots_count' => $this->getSlotsCount(),
            'event_id' => $this->getEventId()
        ];
    }

    /**
     * @return string[]
     */
    public function rules(): array {
        return [
            'email' => 'required|email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'booking_date' => 'required|date',
            'slots_count' => 'required|integer|min:1',
            'event_id' => 'required|exists:events,id'
        ];
    }
}