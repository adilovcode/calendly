<?php

namespace App\Core\Application\Repositories;

use App\Core\Domain\Entities\EEvent;

interface IEventRepository {
    /**
     * @param EEvent $event
     * @return void
     */
    public function store(EEvent $event): void;

    /**
     * @param string $id
     * @return EEvent
     */
    public function fetchById(string $id): EEvent;
}