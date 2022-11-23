<?php

namespace App\Core\Application\ValidationRequests;

use App\Core\Application\Requests\BookingCreatorRequest;
use Illuminate\Foundation\Http\FormRequest;

class BookingCreatorValidationRequest extends FormRequest {
    /**
     * @return array
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

    /**
     * @return BookingCreatorRequest
     */
    public function toDto(): BookingCreatorRequest {
        return new BookingCreatorRequest(
            email: $this->get('email'),
            firstName: $this->get('first_name'),
            lastName: $this->get('last_name'),
            bookingDate: $this->get('booking_date'),
            eventId: $this->get('event_id'),
            slotsCount: $this->get('slots_count')
        );
    }
}