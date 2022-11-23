<?php

namespace Database\Seeders;

use App\Core\Application\Requests\EventCreatorRequest;
use App\Core\Application\UseCases\EventCreator;
use App\Core\Domain\ValueObjects\Minute;
use App\Core\Domain\ValueObjects\OverrideDay;
use App\Core\Domain\ValueObjects\TimeOff;
use App\Core\Domain\ValueObjects\Hours;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {

        $overrideDays = [
            [
                'day' => 6,
                'start' => '10:00',
                'end' => '14:00'
            ],
            [
                'day' => 0,
                'start' => '10:00',
                'end' => '14:00'
            ],
        ];

        $timeOffs = [
            [
                'name' => 'Launch',
                'start' => '12:00',
                'end' => '13:00'
            ],
            [
                'name' => 'Clean',
                'start' => '16:00',
                'end' => '17:00'
            ],
        ];

        $eventCreatorRequest = new EventCreatorRequest(
            name: fake()->name(),
            description: fake()->text(),
            slotDuration: Minute::from(30),
            bufferTime: Minute::from(10),
            workingDays: range(0, 6),
            workingHours: new Hours(
                startTime: '09:00',
                endTime: '18:00'
            ),
            overrideDays: array_map(fn($overrideDay) => new OverrideDay(
                day: $overrideDay['day'],
                startTime: $overrideDay['start'],
                endTime: $overrideDay['end']
            ), $overrideDays),
            timeOffs: array_map(fn($timeOff) => new TimeOff(
                name: $timeOff['name'],
                startTime: $timeOff['start'],
                endTime: $timeOff['end']
            ), $timeOffs),
            endDate: Carbon::now()->addWeek()->toDateString(),
            acceptsPerSlot: 3
        );

        resolve(EventCreator::class)->perform($eventCreatorRequest);
    }
}
