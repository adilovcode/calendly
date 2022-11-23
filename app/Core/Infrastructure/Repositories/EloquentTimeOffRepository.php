<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Application\Repositories\ITimeOffRepository;
use App\Core\Domain\Entities\ETimeOff;
use App\Models\TimeOff;

class EloquentTimeOffRepository implements ITimeOffRepository {

    /**
     * @inheritDoc
     */
    public function insert(array $timeOffs): void {
        TimeOff::insert(array_map(fn(ETimeOff $timeOff) => $timeOff->toDBArray(), $timeOffs));
    }

    /**
     * @inheritDoc
     */
    public function fetchByEventId(string $eventId): array {
        return TimeOff::where('event_id', $eventId)->get()->map->toDomainEntity()->toArray();
    }
}