<?php

namespace App\Core\Application\Repositories;

use App\Core\Domain\Entities\EWorkingDay;

interface IWorkingDaysRepository {
    /**
     * @param array $workingHours
     * @return void
     */
    public function insert(array $workingHours): void;


    /**
     * @param string $eventId
     * @return EWorkingDay[]
     */
    public function fetchByEventId(string $eventId): array;

    /**
     * @param string $eventId
     * @param string $day
     * @return EWorkingDay|null
     */
    public function fetchWorkingDayByWeekDayAndEventId(string $eventId, string $day): ?EWorkingDay;
}