<?php

namespace Tests\Core\Application\Services;

use App\Core\Application\Repositories\IBookingsRepository;
use App\Core\Application\Repositories\ITimeOffRepository;
use App\Core\Application\Repositories\IWorkingDaysRepository;
use App\Core\Application\Services\AvailableSlotBookingChecker;
use App\Core\Application\Services\DailySlotsGenerator;
use App\Core\Application\ValueObjects\DailySlotsGeneratorDto;
use App\Core\Domain\Entities\EBooking;
use Carbon\Carbon;
use Tests\Core\Application\Helpers\BookingsCreator;
use Tests\Core\Application\Helpers\EventCreator;
use Tests\Core\Application\Helpers\TimeOffCreator;
use Tests\Core\Application\Helpers\WorkingHoursCreator;
use Tests\TestCase;

class AvailableSlotBookingCheckerTest extends TestCase {
    use EventCreator, TimeOffCreator, WorkingHoursCreator, BookingsCreator;

    public function setUp(): void {
        parent::setUp();

//        $this->mockInstance(DailySlotsGenerator::class);
        $this->mockInstance(IWorkingDaysRepository::class);
        $this->mockInstance(IBookingsRepository::class);
        $this->mockInstance(ITimeOffRepository::class);

        $this->travelTo(Carbon::parse('2022-11-23'));
    }

    public function testIsAvailable(): void {
        $event = $this->makeEvent([
            'accepts_per_slot' => 1
        ]);

        $expectedWorkingDay = $this->makeWorkingHours([
            'day' => 3,
            'start_time' => '09:00',
            'end_time' => '18:00'
        ]);

        $expectedBookings = $this->generateBooking();
        $expectedTimeOffs = [
            $this->makeTimeOff([
                'start_time' => '12:00',
                'end_time' => '13:00'
            ]),
            $this->makeTimeOff([
                'start_time' => '14:00',
                'end_time' => '17:00'
            ]),
        ];

        $this
            ->mocked(IWorkingDaysRepository::class)
            ->shouldReceive('fetchWorkingDayByWeekDayAndEventId')
            ->with($event->getId(), 3)
            ->andReturn($expectedWorkingDay)
            ->once();

        $this
            ->mocked(IBookingsRepository::class)
            ->shouldReceive('fetchByDateEventId')
            ->with($event->getId(), Carbon::now()->toDateString())
            ->andReturn($expectedBookings)
            ->once();

        $this
            ->mocked(ITimeOffRepository::class)
            ->shouldReceive('fetchByEventId')
            ->with($event->getId())
            ->andReturn($expectedTimeOffs)
            ->once();

//        $this
//            ->mocked(DailySlotsGenerator::class)
//            ->shouldReceive('generate')
//            ->withArgs(
//                fn(DailySlotsGeneratorDto $generatorDto) => $generatorDto->getStartTime() === $expectedWorkingDay->getStartTime() &&
//                    $generatorDto->getEndTime() === $expectedWorkingDay->getEndTime() &&
//                    $generatorDto->getDuration()->getValue() === $event->getTotalDuration()->getValue() &&
//                    $generatorDto->getTimeOffs() === $expectedTimeOffs &&
//                    $generatorDto->getBookings() === $expectedBookings &&
//                    $generatorDto->getAcceptsPerSlot() === $event->getAcceptPerSlot()
//            )
//            ->once();

        $response = resolve(AvailableSlotBookingChecker::class)->isAvailable('2022-11-23 15:20:00', $event);

//        dd($response);
    }

    /**
     * @return EBooking[]
     */
    private function generateBooking(): array {
        return [
            $this->makeBooking([
                'booking_date' => Carbon::now()->addHours(9) // 2022-11-23 09:00:00
            ]),
            $this->makeBooking([
                'booking_date' => Carbon::now()->addHours(10) // 2022-11-23 09:00:00
            ]),
            $this->makeBooking([
                'booking_date' => Carbon::now()->addHours(16) // 2022-11-24 10:00:00
            ]),
        ];
    }
}
