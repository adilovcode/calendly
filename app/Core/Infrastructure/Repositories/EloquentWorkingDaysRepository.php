<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Application\Repositories\IWorkingDaysRepository;
use App\Core\Domain\Entities\EWorkingDay;
use App\Models\WorkingDay;

class EloquentWorkingDaysRepository implements IWorkingDaysRepository {

    /**
     * @inheritDoc
     */
    public function insert(array $workingHours): void {
        WorkingDay::insert(array_map(fn(EWorkingDay $workingHour) => $workingHour->toDBArray(), $workingHours));
    }

    /**
     * @inheritDoc
     */
    public function fetchByEventId(string $eventId): array {
        return WorkingDay::where('event_id', $eventId)->get()->map->toDomainEntity()->toArray();
    }

    /**
     * @inheritDoc
     */
    public function fetchWorkingDayByWeekDayAndEventId(string $eventId, string $day): ?EWorkingDay {
        return WorkingDay::where('event_id', $eventId)->where('day', $day)->first()?->toDomainEntity();
    }
}