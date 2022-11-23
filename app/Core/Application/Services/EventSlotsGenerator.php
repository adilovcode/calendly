<?php

namespace App\Core\Application\Services;

use App\Core\Application\Repositories\IBookingsRepository;
use App\Core\Application\Repositories\ITimeOffRepository;
use App\Core\Application\ValueObjects\DailySlotsGeneratorDto;
use App\Core\Domain\Entities\EEvent;
use App\Core\Domain\ValueObjects\WorkingDay;
use App\Core\Domain\ValueObjects\WorkingDayWithSlots;
use Carbon\Carbon;

class EventSlotsGenerator {

    /**
     * @var array
     */
    private array $bookings;

    /**
     * @param WorkingDaysGenerator $workingDaysGenerator
     * @param IBookingsRepository $bookingsRepository
     * @param DailySlotsGenerator $dailySlotsGenerator
     * @param ITimeOffRepository $timeOffRepository
     */
    public function __construct(
        private readonly WorkingDaysGenerator $workingDaysGenerator,
        private readonly IBookingsRepository $bookingsRepository,
        private readonly DailySlotsGenerator $dailySlotsGenerator,
        private readonly ITimeOffRepository $timeOffRepository
    ) {}

    /**
     * @param EEvent $event
     * @return array
     */
    public function generate(EEvent $event): array {
        $availableWorkingDays = $this->workingDaysGenerator->generate($event);

        $this->bookings = $this->bookingsRepository->fetchByEventId($event->getId());

        $timeOffs = $this->timeOffRepository->fetchByEventId($event->getId());

        $response = [];

        foreach ($availableWorkingDays as $availableDay) {
            $bookings = $this->filterBookingsForSpecificDate($availableDay);

            $generatorDto = new DailySlotsGeneratorDto(
                startTime: $availableDay->getStartTime(),
                endTime: $availableDay->getEndTime(),
                duration: $event->getTotalDuration(),
                timeOffs: $timeOffs,
                bookings: $bookings,
                acceptsPerSlot: $event->getAcceptPerSlot()
            );

            $slots = $this->dailySlotsGenerator->generate($generatorDto);

            $response[] = new WorkingDayWithSlots(
                workingDay: $availableDay,
                slots: $slots
            );
        }

        return $response;
    }

    /**
     * @param WorkingDay $workingDay
     * @return array
     */
    private function filterBookingsForSpecificDate(WorkingDay $workingDay): array {
        $bookings = [];

        foreach ($this->bookings as $key => $booking) {
            if (Carbon::parse($booking->getBookingDate())->toDateString() === $workingDay->getDate()) {
                $bookings[] = $booking;
                unset($this->bookings[$key]);
            }
        }

        return $bookings;
    }
}