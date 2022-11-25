<?php

namespace App\Core\Application\UseCases;

use App\Core\Application\Repositories\IBookingsRepository;
use App\Core\Application\Repositories\IEventRepository;
use App\Core\Application\Requests\BookingCreatorRequest;
use App\Core\Application\Services\AvailableSlotBookingChecker;
use App\Core\Application\ValueObjects\PersonalInformation;
use App\Core\Domain\Entities\EBooking;
use Exception;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

class BookingCreator {

    private BookingCreatorRequest $request;

    public function __construct(
        private readonly Validator $validator,
        private readonly AvailableSlotBookingChecker $availableSlotBookingChecker,
        private readonly IEventRepository $eventRepository,
        private readonly IBookingsRepository $bookingsRepository
    ) {}

    /**
     * @param BookingCreatorRequest $request
     * @return void
     * @throws Exception
     */
    public function perform(BookingCreatorRequest $request): void {
        $this->request = $request;

        $this->validate();

        $event = $this->eventRepository->fetchById($request->getEventId());

        $isAvailable = $this->availableSlotBookingChecker->isAvailable(
            $request->getBookingDate(),
            $event,
            count($request->getPersonalInformation())
        );

        if (!$isAvailable) {
            throw new \RuntimeException("Slots are not available");
        }

        $this->createBookings();
    }

    /**
     * @return array
     */
    private function generateBookings(): array {
        return array_map(
            fn(PersonalInformation $personalInfo) =>  new EBooking(
                eventId: $this->request->getEventId(),
                firstName: $personalInfo->getFirstName(),
                lastName: $personalInfo->getLastName(),
                email: $personalInfo->getEmail(),
                bookingDate: $this->request->getBookingDate()
            ),
            $this->request->getPersonalInformation()
        );
    }

    /**
     * @return void
     * @throws ValidationException
     */
    private function validate(): void {
        $this->validator->make($this->request->toArray(), $this->request->rules())->validate();
    }

    /**
     * @return void
     */
    private function createBookings(): void {
        foreach ($this->generateBookings() as $generateBooking) {
            $this->bookingsRepository->store($generateBooking);
        }
    }
}