<?php

namespace App\Core\Application\ValidationRequests;

use App\Core\Application\Requests\BookingCreatorRequest;
use App\Core\Application\ValueObjects\PersonalInformation;
use Illuminate\Foundation\Http\FormRequest;

class BookingCreatorValidationRequest extends FormRequest {
    /**
     * @return array
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

    /**
     * @return BookingCreatorRequest
     */
    public function toDto(): BookingCreatorRequest {
        return new BookingCreatorRequest(
            personalInformation: $this->generatePersonalInfoObjects(),
            bookingDate: $this->get('booking_date'),
            eventId: $this->get('event_id'),
        );
    }

    /**
     * @return array
     */
    private function generatePersonalInfoObjects(): array {
        return array_map(fn($personalInfo) => new PersonalInformation(
            email: $personalInfo['email'],
            firstName: $personalInfo['first_name'],
            lastName: $personalInfo['last_name']
        ), $this->get('data'));
    }
}