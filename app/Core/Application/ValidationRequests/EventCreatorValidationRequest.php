<?php

namespace App\Core\Application\ValidationRequests;

use App\Core\Application\Requests\EventCreatorRequest;
use App\Core\Application\Requests\Rules\WorkingHoursRules;
use App\Core\Domain\ValueObjects\OverrideDay;
use App\Core\Domain\ValueObjects\TimeOff;
use App\Core\Domain\ValueObjects\Hours;
use Illuminate\Foundation\Http\FormRequest;

class EventCreatorValidationRequest extends FormRequest {
    /**
     * @return array
     */
    public function rules(): array {
        return [
            'working_days' => 'required|array',
            'working_days.*' => 'required|min:0|max:6',
            'working_hours.start' => ['required', new WorkingHoursRules()],
            'working_hours.end' => ['required', new WorkingHoursRules(), 'gt:working_hours.start'],

            'override_days' => 'required|array',
            'override_days.*.day' => 'required|distinct',
            'override_days.*.start' => ['required', new WorkingHoursRules()],
            'override_days.*.end' => ['required', new WorkingHoursRules(), 'gt:override_days.*.start'],

            'name' => 'required|min:3',
            'description' => 'nullable',
            'slot_duration' => 'required|min:5|max:720|numeric',
            'buffer_time' => 'nullable|numeric',
            'end_date' => 'required|date',

            'time_offs' => 'nullable|array',
            'time_offs.*.name' => 'required|string|min:3',
            'time_offs.*.start' => ['required', new WorkingHoursRules()],
            'time_offs.*.end' => ['required', new WorkingHoursRules(), 'gt:time_offs.*.start'],
        ];
    }

    /**
     * @return EventCreatorRequest
     */
    public function toDto(): EventCreatorRequest {
        return new EventCreatorRequest(
            name: $this->get('name'),
            description: $this->get('description'),
            slotDuration: $this->get('slot_duration'),
            bufferTime: $this->get('buffer_time'),
            workingDays: $this->get('workingDays'),
            workingHours: new Hours(
                startTime: $this->get('working_hours.start'),
                endTime: $this->get('working_hours.end')
            ),
            overrideDays: $this->generateOverrideDays(),
            timeOffs: $this->generateTimeOffs(),
            endDate: $this->get('end_date')
        );
    }

    /**
     * @return array
     */
    private function generateOverrideDays(): array {
        return $this->get('override_days')
            ? array_map(
                fn($overrideDay) => new OverrideDay(
                    day: $overrideDay['day'],
                    startTime: $overrideDay['start'],
                    endTime: $overrideDay['end']
                ),
                $this->get('override_days')
            )
            : [];
    }

    /**
     * @return array
     */
    private function generateTimeOffs(): array {
        return $this->get('time_offs')
            ? array_map(
                fn($overrideDay) => new TimeOff(
                    name: $overrideDay['name'],
                    startTime: $overrideDay['start'],
                    endTime: $overrideDay['end']
                ),
                $this->get('time_offs')
            )
            : [];
    }
}