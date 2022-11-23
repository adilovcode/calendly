<?php

namespace App\Core\Application\Services;

use App\Core\Application\Repositories\IWorkingDaysRepository;
use App\Core\Domain\Entities\EEvent;
use App\Core\Domain\Entities\EWorkingDay;
use App\Core\Domain\ValueObjects\WorkingDay;
use Carbon\Carbon;

class WorkingDaysGenerator {

    public function __construct(
        private readonly IWorkingDaysRepository $workingHoursRepository
    ) {}

    /**
     * @param EEvent $event
     * @return WorkingDay[]
     */
    public function generate(EEvent $event): array {
        $dateRange = $this->makeDateRange(Carbon::now(), Carbon::parse($event->getEndDate()));

        $workingDays = $this->workingHoursRepository->fetchByEventId($event->getId());

        return $this->filterDateRangeByAvailableWorkingDays($dateRange, $workingDays);
    }

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @return array
     */
    private function makeDateRange(Carbon $start, Carbon $end): array {
        return collect(new \DatePeriod($start, new \DateInterval('P1D'), $end))
            ->map->format(DATE_RFC3339)->toArray();
    }

    /**
     * @param array $dateRange
     * @param EWorkingDay[] $workingDays
     * @return array
     */
    private function filterDateRangeByAvailableWorkingDays(array $dateRange, array $workingDays): array {
        $availableWorkingDays = [];

        foreach ($dateRange as $item) {

            foreach ($workingDays as $workingDay) {
                if (Carbon::parse($item)->dayOfWeek == $workingDay->getDay()) {
                    $availableWorkingDays[] = new WorkingDay(
                        date: Carbon::parse($item)->toDateString(),
                        startTime: $workingDay->getStartTime(),
                        endTime: $workingDay->getEndTime()
                    );
                }
            }
        }

        return $availableWorkingDays;
    }
}