<?php

namespace App\Core\Application\UseCases;

use App\Core\Application\Repositories\IEventRepository;
use App\Core\Application\Repositories\ITimeOffRepository;
use App\Core\Application\Repositories\IWorkingDaysRepository;
use App\Core\Application\Requests\EventCreatorRequest;
use App\Core\Domain\Entities\EEvent;
use App\Core\Domain\Entities\ETimeOff;
use App\Core\Domain\Entities\EWorkingDay;
use App\Core\Domain\Exceptions\TimeRangeException;
use App\Core\Domain\ValueObjects\TimeOff;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory as Validator;

class EventCreator {

    /**
     * @var EventCreatorRequest
     */
    private EventCreatorRequest $creatorRequest;

    /**
     * @param Validator $validator
     * @param IEventRepository $eventRepository
     * @param ITimeOffRepository $timeOffRepository
     * @param IWorkingDaysRepository $workingHoursRepository
     */
    public function __construct(
        private readonly Validator              $validator,
        private readonly IEventRepository       $eventRepository,
        private readonly ITimeOffRepository     $timeOffRepository,
        private readonly IWorkingDaysRepository $workingHoursRepository,
    ) {}

    /**
     * @param EventCreatorRequest $requestDto
     * @return void
     * @throws ValidationException|TimeRangeException
     */
    public function perform(EventCreatorRequest $requestDto): void {
        $this->creatorRequest = $requestDto;

        $this->validate();

        $event = $this->generateEventEntity();
        $this->eventRepository->store($event);

        $this->workingHoursRepository->insert($this->generateWorkingHoursEntities($event));

        $this->timeOffRepository->insert($this->generateTimeOffEntities($event));
    }

    /**
     * @return EEvent
     */
    private function generateEventEntity(): EEvent {
        return new EEvent(
            name: $this->creatorRequest->getName(),
            description: $this->creatorRequest->getDescription(),
            duration: $this->creatorRequest->getSlotDuration(),
            bufferTime: $this->creatorRequest->getBufferTime(),
            bookableInAdvance: $this->creatorRequest->getBookableInAdvance(),
            acceptPerSlot:  $this->creatorRequest->getAcceptsPerSlot()
        );
    }

    /**
     * @param EEvent $event
     * @return array
     */
    private function generateWorkingHoursEntities(EEvent $event): array {
        $workingHours = [];

        foreach ($this->creatorRequest->getWorkingDays() as $workingDay) {

            $workingHours[$workingDay] = new EWorkingDay(
                eventId: $event->getId(),
                day: $workingDay,
                startTime: $this->creatorRequest->getWorkingHours()->getStartTime(),
                endTime: $this->creatorRequest->getWorkingHours()->getEndTime()
            );

            foreach ($this->creatorRequest->getOverrideDays() as $overrideDay) {
                if ($workingDay === $overrideDay->getDay()) {
                    $workingHours[$workingDay] = new EWorkingDay(
                        eventId: $event->getId(),
                        day: $overrideDay->getDay(),
                        startTime: $overrideDay->getStartTime(),
                        endTime: $overrideDay->getEndTime()
                    );
                }
            }
        }

        return $workingHours;
    }

    public function generateTimeOffEntities(EEvent $event): array {
        return array_map(
            fn(TimeOff $timeOff) => new ETimeOff(
                title: $timeOff->getName(),
                eventId: $event->getId(),
                startTime: $timeOff->getStartTime(),
                endTime: $timeOff->getEndTime()
            ),
            $this->creatorRequest->getTimeOffs()
        );
    }

    /**
     * @return void
     * @throws ValidationException|TimeRangeException
     */
    private function validate(): void {
        $this->validator->make($this->creatorRequest->toArray(), $this->creatorRequest->rules())->validate();

        $this->validateTimeOffs($this->creatorRequest->getTimeOffs());
    }

    /**
     * @param TimeOff[] $timeOffs
     * @return void
     * @throws TimeRangeException
     */
    private function validateTimeOffs(array $timeOffs): void {
        foreach ($timeOffs as $cKey => $checkingTimeOff) {
            foreach ($timeOffs as $key => $timeOff) {
                if ($cKey !== $key && $this->doesTimeOffsHaveConflict($checkingTimeOff, $timeOff)) {
                    throw new TimeRangeException("Time-off has conflicts");
                }
            }
        }
    }

    /**
     * @param TimeOff $first
     * @param TimeOff $second
     * @return bool
     */
    private function doesTimeOffsHaveConflict(TimeOff $first, TimeOff $second): bool {
        return ($first->getStartTime() <= $second->getStartTime() && $first->getEndTime() >= $second->getStartTime()) ||
            ($first->getStartTime() <= $second->getEndTime() && $first->getEndTime() >= $second->getEndTime());
    }
}