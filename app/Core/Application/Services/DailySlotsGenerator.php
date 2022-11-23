<?php

namespace App\Core\Application\Services;

use App\Core\Application\ValueObjects\DailySlotsGeneratorDto;
use App\Core\Domain\Entities\EBooking;
use App\Core\Domain\Entities\ETimeOff;
use App\Core\Domain\ValueObjects\Hours;
use App\Core\Domain\ValueObjects\Minute;
use Carbon\Carbon;

class DailySlotsGenerator {
    /**
     * @param SlotsGenerator $slotsGenerator
     */
    public function __construct(
        private readonly SlotsGenerator $slotsGenerator
    ) {}

    /**
     * @param DailySlotsGeneratorDto $generatorDto
     * @return array
     */
    public function generate(DailySlotsGeneratorDto $generatorDto): array {
        $slots = [];

        $start = $generatorDto->getStartTime();

        $busyHours = [
            ...$this->makeHoursFromTimeOffs($generatorDto->getTimeOffs()),
            ...$this->filterBookingHoursWithAcceptsPerSlot(
                $generatorDto->getBookings(),
                $generatorDto->getAcceptsPerSlot(),
                $generatorDto->getDuration()
            )
        ];

        $busyHours = $this->orderHours(
            $this->filterHoursByEndTime($busyHours, $generatorDto->getEndTime())
        );

        foreach ($busyHours as $busyHour) {

            $slots = [
                ...$slots,
                ...$this->slotsGenerator->generate($start, $busyHour->getStartTime(), $generatorDto->getDuration())
            ];

            $start = $busyHour->getEndTime();
        }

        return [
            ...$slots,
            ...$this->slotsGenerator->generate($start, $generatorDto->getEndTime(), $generatorDto->getDuration())
        ];
    }

    /**
     * @param array $busyHours
     * @param int $acceptsPerSlot
     * @param Minute $duration
     * @return array
     */
    private function filterBookingHoursWithAcceptsPerSlot(array $busyHours, int $acceptsPerSlot, Minute $duration): array {

        $separator = '-';

        $mutatedData = array_map(function (EBooking $booking) use ($duration, $separator) {

            $startTime = Carbon::parse($booking->getBookingDate())->format('H:i');
            $endTime = Carbon::parse($booking->getBookingDate())->addMinutes($duration->getValue())->format('H:i');

            return $startTime . $separator . $endTime;
        }, $busyHours);

        $filteredData = array_filter(
            array_count_values($mutatedData),
            fn($value) => $value >= $acceptsPerSlot
        );

        return array_map(
            static function($hours) use ($separator) {
                [$start, $end] = explode($separator, $hours);
                return new Hours(
                    startTime: $start,
                    endTime: $end
                );
            },
            array_keys($filteredData)
        );
    }

    /**
     * @param Hours[] $hours
     * @param string $endTime
     * @return Hours[]
     */
    private function filterHoursByEndTime(array $hours, string $endTime): array {

        foreach ($hours as $key => $hour) {
            $isInMiddle = $hour->getStartTime() < $endTime && $hour->getEndTime() > $endTime;
            $outOfTheRange = $hour->getEndTime() > $endTime;

            if ($isInMiddle || $outOfTheRange) {
                unset($hours[$key]);
            }
        }

        return $hours;
    }

    /**
     * @param Hours[] $hours
     * @return array
     */
    private function orderHours(array $hours): array {
        usort($hours, function($first,$second){
            return $first->getStartTime() > $second->getStartTime();
        });

        return $hours;
    }

    /**
     * @param array $timeOffs
     * @return array
     */
    private function makeHoursFromTimeOffs(array $timeOffs): array {
        return array_map(fn (ETimeOff $timeOff) => new Hours(
            startTime: $timeOff->getStartTime(),
            endTime: $timeOff->getEndTime()
        ), $timeOffs);
    }
}