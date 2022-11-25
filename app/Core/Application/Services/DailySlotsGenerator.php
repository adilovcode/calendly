<?php

namespace App\Core\Application\Services;

use App\Core\Application\ValueObjects\DailySlotsGeneratorDto;
use App\Core\Domain\Entities\EBooking;
use App\Core\Domain\Entities\ETimeOff;
use App\Core\Domain\ValueObjects\Hours;
use App\Core\Domain\ValueObjects\Minute;
use Carbon\Carbon;

class DailySlotsGenerator {

    private DailySlotsGeneratorDto $generatorDto;

    /**
     * @param SlotsGenerator $slotsGenerator
     */
    public function __construct(
        private readonly SlotsGenerator $slotsGenerator
    ) {}

    /**
     *
     * 08:00     12:00 - 13:00   15:00 -- 15:30            20:00
     *
     * @param DailySlotsGeneratorDto $generatorDto
     * @return array
     */
    public function generate(DailySlotsGeneratorDto $generatorDto): array {
        $this->generatorDto = $generatorDto;

        $slots = [];

        $start = $generatorDto->getStartTime();

        $busyHours = [
            ...$this->makeHoursFromTimeOffs($generatorDto->getTimeOffs()),
            ...$this->filterBookingHoursWithAcceptsPerSlot()
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
     * @return array
     */
    private function filterBookingHoursWithAcceptsPerSlot(): array {

        $separator = '-';

        $mutatedData = array_map(
            function (EBooking $booking) use ($separator) {
                return $booking->getStartTime() . $separator . $booking->getEndTime($this->generatorDto->getDuration());
            },
            $this->generatorDto->getBookings()
        );

        $filteredData = array_filter(
            array_count_values($mutatedData),
            fn($value) => $value >= $this->generatorDto->getAcceptsPerSlot()
        );

        return array_map(
            static function ($hours) use ($separator) {
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
        usort($hours, function ($first, $second) {
            return $first->getStartTime() > $second->getStartTime();
        });

        return $hours;
    }

    /**
     * @param array $timeOffs
     * @return array
     */
    private function makeHoursFromTimeOffs(array $timeOffs): array {
        return array_map(fn(ETimeOff $timeOff) => new Hours(
            startTime: $timeOff->getStartTime(),
            endTime: $timeOff->getEndTime()
        ), $timeOffs);
    }
}