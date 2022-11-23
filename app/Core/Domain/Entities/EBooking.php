<?php

namespace App\Core\Domain\Entities;

use App\Core\Domain\ValueObjects\Hours;
use Illuminate\Support\Str;

class EBooking implements IEntity {

    private string $id;

    public function __construct(
        private string $eventId,
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $bookingDate,
    ) {
        $this->id = Str::uuid();
    }

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventId(): string {
        return $this->eventId;
    }

    /**
     * @param string $eventId
     * @return self
     */
    public function setEventId(string $eventId): self {
        $this->eventId = $eventId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return self
     */
    public function setFirstName(string $firstName): self {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return self
     */
    public function setLastName(string $lastName): self {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getBookingDate(): string {
        return $this->bookingDate;
    }

    /**
     * @param string $bookingDate
     * @return self
     */
    public function setBookingDate(string $bookingDate): self {
        $this->bookingDate = $bookingDate;
        return $this;
    }

    /**
     * @return array
     */
    public function toDBArray(): array {
        return [
            'id' => $this->getId(),
            'event_id' => $this->getEventId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'email' => $this->getEmail(),
            'booking_date' => $this->getBookingDate()
        ];
    }
}