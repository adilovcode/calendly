<?php

namespace Tests\Unit\Core\Application\Services;

use App\Core\Application\Services\DailySlotsGenerator;
use App\Core\Application\ValueObjects\DailySlotsGeneratorDto;
use App\Core\Domain\ValueObjects\Minute;
use App\Core\Domain\ValueObjects\Slot;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\Unit\Core\Application\Helpers\BookingsCreator;
use Tests\Unit\Core\Application\Helpers\TimeOffCreator;

class DailySlotsGeneratorsTest extends TestCase {
    use BookingsCreator, TimeOffCreator;

    /**
     * @return void
     */
    public function testGenerateWithoutBooking(): void {

        $expectedSlots = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30', '17:00', '17:30'];

        $timeOffs = [
            $this->makeTimeOff([
                'start_time' => '12:00',
                'end_time' => '13:00'
            ]),
            $this->makeTimeOff([
                'start_time' => '15:00',
                'end_time' => '17:00'
            ])
        ];

        $generatorDto = new DailySlotsGeneratorDto(
            startTime: '09:00',
            endTime: '18:00',
            duration: Minute::from(30),
            timeOffs: $timeOffs,
            bookings: [],
            acceptsPerSlot: 3
        );

        $slots = resolve(DailySlotsGenerator::class)->generate($generatorDto);

        $slots = array_map(fn(Slot $slot) => $slot->getTime(), $slots);

        $this->assertEquals($expectedSlots, $slots);
    }

    /**
     * @return void
     */
    public function testGenerateWithBooking(): void {

        $expectedSlots = ['09:00', '09:30', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30', '17:00', '17:30'];

        $timeOffs = [
            $this->makeTimeOff([
                'start_time' => '12:00',
                'end_time' => '13:00'
            ]),
            $this->makeTimeOff([
                'start_time' => '15:00',
                'end_time' => '17:00'
            ])
        ];

        $bookings = [
            $this->makeBooking([
                'booking_date' => Carbon::now()->startOfDay()->addHours(10)
            ]),
            $this->makeBooking([
                'booking_date' => Carbon::now()->startOfDay()->addHours(10)
            ]),
            $this->makeBooking([
                'booking_date' => Carbon::now()->startOfDay()->addHours(10)
            ]),
            $this->makeBooking([
                'booking_date' => Carbon::now()->startOfDay()->addHours(14)
            ]),
        ];

        $generatorDto = new DailySlotsGeneratorDto(
            startTime: '09:00',
            endTime: '18:00',
            duration: Minute::from(30),
            timeOffs: $timeOffs,
            bookings: $bookings,
            acceptsPerSlot: 3
        );

        $slots = resolve(DailySlotsGenerator::class)->generate($generatorDto);

        $slots = array_map(fn(Slot $slot) => $slot->getTime(), $slots);

        $this->assertEquals($expectedSlots, $slots);
    }
}
