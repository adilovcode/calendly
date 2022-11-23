<?php

namespace App\Core\Application\Repositories;

use App\Core\Domain\Entities\ETimeOff;

interface ITimeOffRepository {
    /**
     * @param ETimeOff[] $timeOffs
     * @return void
     */
    public function insert(array $timeOffs): void;

    /**
     * @param string $eventId
     * @return ETimeOff[]
     */
    public function fetchByEventId(string $eventId): array;
}