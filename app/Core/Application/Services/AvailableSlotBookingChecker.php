<?php

namespace App\Core\Application\Services;

use App\Core\Application\Repositories\IBookingsRepository;
use App\Core\Application\Repositories\ITimeOffRepository;
use App\Core\Application\Repositories\IWorkingDaysRepository;
use App\Core\Application\ValueObjects\DailySlotsGeneratorDto;
use App\Core\Domain\Entities\EBooking;
use App\Core\Domain\Entities\EEvent;
use App\Core\Domain\ValueObjects\Slot;
use Carbon\Carbon;
use Exception;

class AvailableSlotBookingChecker {

    /**
     * @var string
     */
    private string $bookingDate;

    /**
     * @var EEvent
     */
    private EEvent $event;

    /**
     * @param DailySlotsGenerator $dailySlotsGenerator
     * @param IWorkingDaysRepository $workingDaysRepository
     * @param IBookingsRepository $bookingsRepository
     * @param ITimeOffRepository $timeOffRepository
     */
    public function __construct(
        private readonly DailySlotsGenerator $dailySlotsGenerator,
        private readonly IWorkingDaysRepository $workingDaysRepository,
        private readonly IBookingsRepository $bookingsRepository,
        private readonly ITimeOffRepository $timeOffRepository
    ) {}

    /**
     * @throws Exception
     */
    public function isAvailable(string $bookingDate, EEvent $event, int $slotsCount = 1): bool {
        $this->bookingDate = $bookingDate;
        $this->event = $event;

        $this->validateIfiIEventDateRange();

        $workingDay = $this->workingDaysRepository->fetchWorkingDayByWeekDayAndEventId(
            $event->getId(),
            Carbon::parse($bookingDate)->dayOfWeek
        );

        if (!$workingDay) {
            throw new \RuntimeException('Not working day');
        }

        $bookings = $this->bookingsRepository->fetchByDateEventId($event->getId(), Carbon::parse($bookingDate)->toDateString());
        $timeOffs = $this->timeOffRepository->fetchByEventId($event->getId());

        $generatorDto = new DailySlotsGeneratorDto(
            startTime: $workingDay->getStartTime(),
            endTime: $workingDay->getEndTime(),
            duration: $event->getTotalDuration(),
            timeOffs: $timeOffs,
            bookings: [...$bookings, ...$this->generateDummyBookings($slotsCount)],
            acceptsPerSlot: $event->getAcceptPerSlot(),
        );

        $slots = $this->dailySlotsGenerator->generate($generatorDto);

        return $this->isBookingTimeInArrayOfSlots($slots);

    }

    /**
     * @param array $slots
     * @return bool
     */
    private function isBookingTimeInArrayOfSlots(array $slots): bool {
        return !empty(array_filter($slots, fn(Slot $slot) => $slot->getTime() === Carbon::parse($this->bookingDate)->format('H:i')));
    }

    /**
     * @return void
     * @throws Exception
     */
    private function validateIfiIEventDateRange(): void {
        if (!$this->isInRangeOfHours(
            Carbon::now()->toDateString(),
            $this->event->getEndDate(),
            $this->bookingDate
        )) {
            throw new Exception("Out of event date range");
        }
    }

    /**
     * @param string $start
     * @param string $end
     * @param string $checking
     * @return bool
     */
    private function isInRangeOfHours(string $start, string $end, string $checking): bool {
        return $checking <= $end && $checking >= $start;
    }

    /**
     * @param int $slotsCount
     * @return array
     */
    private function generateDummyBookings(int $slotsCount): array {
        $bookings = [];
        for ($i = 0; $i < $slotsCount - 1; $i++) {
            $bookings[] = $this->makeBooking();
        }

        return $bookings;
    }

    /**
     * @return EBooking
     */
    private function makeBooking(): EBooking {
        return new EBooking(
            eventId: 1,
            firstName: 'Umar',
            lastName: 'Adilov',
            email: 'adilovcode@gmail.com',
            bookingDate: $this->bookingDate
        );
    }
}