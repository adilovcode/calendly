<?php

namespace App\Core\Application\Requests;

use App\Core\Application\Requests\Rules\GreaterThenRule;
use App\Core\Application\Requests\Rules\WorkingHoursRules;
use App\Core\Domain\ValueObjects\Minute;
use App\Core\Domain\ValueObjects\OverrideDay;
use App\Core\Domain\ValueObjects\TimeOff;
use App\Core\Domain\ValueObjects\Hours;
use Illuminate\Validation\Rule;

class EventCreatorRequest implements IRequest {
    public function __construct(
        private readonly string $name,
        private readonly string $description,
        private readonly Minute $slotDuration,
        private readonly Minute $bufferTime,
        private readonly array  $workingDays,
        private readonly Hours  $workingHours,
        private readonly array  $overrideDays,
        private readonly array  $timeOffs,
        private readonly string $endDate,
        private readonly int $acceptsPerSlot
    ) {}

    /**
     * @return int
     */
    public function getAcceptsPerSlot(): int {
        return $this->acceptsPerSlot;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return Minute
     */
    public function getSlotDuration(): Minute {
        return $this->slotDuration;
    }

    /**
     * @return Minute
     */
    public function getBufferTime(): Minute {
        return $this->bufferTime;
    }

    /**
     * @return array
     */
    public function getWorkingDays(): array {
        return $this->workingDays;
    }

    /**
     * @return Hours
     */
    public function getWorkingHours(): Hours {
        return $this->workingHours;
    }

    /**
     * @return OverrideDay[]
     */
    public function getOverrideDays(): array {
        return $this->overrideDays;
    }

    /**
     * @return TimeOff[]
     */
    public function getTimeOffs(): array {
        return $this->timeOffs;
    }

    /**
     * @return string
     */
    public function getEndDate(): string {
        return $this->endDate;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'slot_duration' => $this->getSlotDuration()->getValue(),
            'buffer_time' => $this->getBufferTime()->getValue(),
            'end_date' => $this->getEndDate(),

            'time_offs' => array_map(
                fn(TimeOff $timeOff) => [
                    'name' => $timeOff->getName(),
                    'start' => $timeOff->getStartTime(),
                    'end' => $timeOff->getEndTime()
                ],
                $this->getTimeOffs()
            ),

            'override_days' => array_map(
                fn(OverrideDay $overrideDay) => [
                    'day' => $overrideDay->getDay(),
                    'start' => $overrideDay->getStartTime(),
                    'end' => $overrideDay->getEndTime()
                ],
                $this->getOverrideDays()
            ),

            'working_hours' => [
                'start' => $this->getWorkingHours()->getStartTime(),
                'end' => $this->getWorkingHours()->getEndTime()
            ],

            'working_days' => $this->getWorkingDays(),
            'accepts_per_slot' => $this->getAcceptsPerSlot()
        ];
    }

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'working_days' => 'required|array',
            'working_days.*' => 'required|min:0|max:6',
            'working_hours.start' => ['required', new WorkingHoursRules()],
            'working_hours.end' => ['required', new WorkingHoursRules(), 'after:working_hours.start'],

            'override_days' => 'required|array',
            'override_days.*.day' => 'required|distinct|' . Rule::in($this->getWorkingDays()),
            'override_days.*.start' => ['required', new WorkingHoursRules()],
            'override_days.*.end' => ['required', new WorkingHoursRules(), 'after:override_days.*.start'],

            'name' => 'required|min:3',
            'description' => 'nullable',
            'slot_duration' => 'required|min:5|max:720|numeric',
            'buffer_time' => 'nullable|numeric',
            'end_date' => 'required|date',

            'time_offs' => 'nullable|array',
            'time_offs.*.name' => 'required|string|min:3',
            'time_offs.*.start' => ['required', new WorkingHoursRules()],
            'time_offs.*.end' => ['required', new WorkingHoursRules(), 'after:time_offs.*.start'],

            'accepts_per_slot' => 'required|integer'
        ];
    }
}