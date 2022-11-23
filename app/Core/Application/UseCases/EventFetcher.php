<?php

namespace App\Core\Application\UseCases;

use App\Core\Application\Repositories\IEventRepository;
use App\Core\Application\Services\EventSlotsGenerator;
use App\Core\Domain\ValueObjects\EventWithDays;

class EventFetcher {
    /**
     * @param IEventRepository $eventRepository
     * @param EventSlotsGenerator $eventSlotsGenerator
     */
    public function __construct(
        private readonly IEventRepository $eventRepository,
        private readonly EventSlotsGenerator $eventSlotsGenerator
    ) {}

    /**
     * @param string $eventId
     * @return EventWithDays
     */
    public function perform(string $eventId): EventWithDays {
        $event = $this->eventRepository->fetchById($eventId);

        $days = $this->eventSlotsGenerator->generate($event);

        return new EventWithDays(
            event: $event,
            workingDaysWithSlots: $days
        );
    }
}