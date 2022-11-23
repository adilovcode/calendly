<?php

namespace App\Core\Infrastructure\Repositories;

use App\Core\Application\Repositories\IEventRepository;
use App\Core\Domain\Entities\EEvent;
use App\Models\Event;

class EloquentEventRepository implements IEventRepository {

    /**
     * @inheritDoc
     */
    public function store(EEvent $event): void {
        Event::create($event->toDBArray());
    }

    /**
     * @inheritDoc
     */
    public function fetchById(string $id): EEvent {
        return Event::findOrFail($id)->toDomainEntity();
    }
}