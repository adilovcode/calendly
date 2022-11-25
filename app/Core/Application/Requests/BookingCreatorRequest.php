<?php

namespace App\Core\Application\Requests;

use App\Core\Application\ValueObjects\PersonalInformation;

class BookingCreatorRequest implements IRequest {

    public function __construct(
        private readonly array $personalInformation,
        private readonly string $bookingDate,
        private readonly string $eventId,
    ) {}

    /**
     * @return string
     */
    public function getEventId(): string {
        return $this->eventId;
    }

    /**
     * @return array
     */
    public function getPersonalInformation(): array {
        return $this->personalInformation;
    }

    /**
     * @return string
     */
    public function getBookingDate(): string {
        return $this->bookingDate;
    }
    /**
     * @return array
     */
    public function toArray(): array {
        return [
            "data" => array_map(
                fn(PersonalInformation $information) => $information->toArray(),
                $this->getPersonalInformation()
            ),
            'booking_date' => $this->getBookingDate(),
            'event_id' => $this->getEventId()
        ];
    }

    /**
     * @return string[]
     */
    public function rules(): array {
        return [
            'data' => 'required|array',
            'data.*.email' => 'required|email',
            'data.*.first_name' => 'required|string',
            'data.*.last_name' => 'required|string',
            'booking_date' => 'required|date',
            'event_id' => 'required|exists:events,id'
        ];
    }
}