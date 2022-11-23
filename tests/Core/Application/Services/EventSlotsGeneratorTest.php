<?php

namespace Tests\Core\Application\Services;

use App\Core\Application\Repositories\IBookingsRepository;
use App\Core\Application\Repositories\ITimeOffRepository;
use App\Core\Application\Services\DailySlotsGenerator;
use App\Core\Application\Services\EventSlotsGenerator;
use App\Core\Application\Services\WorkingDaysGenerator;
use App\Core\Application\ValueObjects\DailySlotsGeneratorDto;
use App\Core\Domain\Entities\EBooking;
use App\Core\Domain\ValueObjects\WorkingDay;
use Carbon\Carbon;
use Tests\Core\Application\Helpers\BookingsCreator;
use Tests\Core\Application\Helpers\EventCreator;
use Tests\Core\Application\Helpers\TimeOffCreator;
use Tests\Core\Application\Helpers\WorkingHoursCreator;
use Tests\TestCase;

class EventSlotsGeneratorTest extends TestCase {
    use EventCreator, WorkingHoursCreator, BookingsCreator, TimeOffCreator;

    public function setUp(): void {
        parent::setUp();

        $this->mockInstance(WorkingDaysGenerator::class);
        $this->mockInstance(IBookingsRepository::class);
        $this->mockInstance(ITimeOffRepository::class);
        $this->mockInstance(DailySlotsGenerator::class);

        $this->travelTo(Carbon::parse('2022-11-23'));
    }

    public function testGenerate(): void {
        $event = $this->makeEvent([
            'accepts_per_slot' => 3
        ]);

        $expectedWorkingDates = $this->generateWorkingDates();
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
            ->mocked(WorkingDaysGenerator::class)
            ->shouldReceive('generate')
            ->with($event)
            ->andReturn($expectedWorkingDates)
            ->once();

        $this
            ->mocked(IBookingsRepository::class)
            ->shouldReceive('fetchByEventId')
            ->with($event->getId())
            ->andReturn($expectedBookings)
            ->once();

        $this
            ->mocked(ITimeOffRepository::class)
            ->shouldReceive('fetchByEventId')
            ->with($event->getId())
            ->andReturn($expectedTimeOffs)
            ->once();

        foreach ($expectedWorkingDates as $expectedWorkingDate) {
            $this
                ->mocked(DailySlotsGenerator::class)
                ->shouldReceive('generate')
                ->withArgs(
                    fn(DailySlotsGeneratorDto $generatorDto) => $generatorDto->getStartTime() === $expectedWorkingDate->getStartTime() &&
                        $generatorDto->getEndTime() === $expectedWorkingDate->getEndTime() &&
                        $generatorDto->getDuration()->getValue() === $event->getTotalDuration()->getValue() &&
                        $generatorDto->getTimeOffs() === $expectedTimeOffs &&
                        $generatorDto->getAcceptsPerSlot() === $event->getAcceptPerSlot()
                )
                ->andReturn([])
                ->once();
        }

        resolve(EventSlotsGenerator::class)->generate($event);
    }

    /**
     * @return WorkingDay[]
     */
    private function generateWorkingDates(): array {
        return array_map(
            fn($date) => new WorkingDay(
                date: $date,
                startTime: '09:00',
                endTime: '18:00'
            ), [
                '2022-11-23',
                '2022-11-24',
                '2022-11-25',
                '2022-11-28',
                '2022-11-29',
            ]
        );
    }

    /**
     * @return EBooking[]
     */
    private function generateBooking(): array {
        return [
            $this->makeBooking([
                'booking_date' => Carbon::now()->addDay()->addHours(9) // 2022-11-23 09:00:00
            ]),
            $this->makeBooking([
                'booking_date' => Carbon::now()->addDay()->addHours(9) // 2022-11-23 09:00:00
            ]),
            $this->makeBooking([
                'booking_date' => Carbon::now()->addDay()->addHours(9) // 2022-11-23 09:00:00
            ]),
            $this->makeBooking([
                'booking_date' => Carbon::now()->addDays(2)->addHours(10) // 2022-11-24 10:00:00
            ]),
        ];
    }
}
