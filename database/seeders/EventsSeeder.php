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

        $data = [
            [
                'override_days' => [
                    [
                        'day' => 6,
                        'start' => '10:00',
                        'end' => '22:00'
                    ]
                ],
                'time_offs' => [
                    [
                        'name' => 'Lunch break',
                        'start' => '12:00',
                        'end' => '13:00'
                    ],
                    [
                        'name' => 'Cleaning break',
                        'start' => '15:00',
                        'end' => '16:00'
                    ],
                ],
                'name' => 'Men Haircut',
                'description' => fake()->text(),
                'slot_duration' => Minute::from(10),
                'buffer_time' => Minute::from(5),
                'working_days' => range(1, 6),
                'working_hours' => new Hours(
                    startTime: '08:00',
                    endTime: '20:00'
                ),
                'end_date' => Carbon::now()->addWeek()->toDateString(),
                'accepts_per_slot' => 3
            ],
            [
                'override_days' => [
                    [
                        'day' => 6,
                        'start' => '10:00',
                        'end' => '22:00'
                    ]
                ],
                'time_offs' => [
                    [
                        'name' => 'Lunch break',
                        'start' => '12:00',
                        'end' => '13:00'
                    ],
                    [
                        'name' => 'Cleaning break',
                        'start' => '15:00',
                        'end' => '16:00'
                    ],
                ],
                'name' => 'Woman Haircut',
                'description' => fake()->text(),
                'slot_duration' => Minute::from(60),
                'buffer_time' => Minute::from(10),
                'working_days' => range(1, 6),
                'working_hours' => new Hours(
                    startTime: '08:00',
                    endTime: '20:00'
                ),
                'end_date' => Carbon::now()->addWeek()->toDateString(),
                'accepts_per_slot' => 3
            ],
        ];

        foreach ($data as $item) {
            $eventCreatorRequest = new EventCreatorRequest(
                name: $item['name'],
                description: $item['description'],
                slotDuration: $item['slot_duration'],
                bufferTime: $item['buffer_time'],
                workingDays: $item['working_days'],
                workingHours: $item['working_hours'],
                overrideDays: array_map(fn($overrideDay) => new OverrideDay(
                    day: $overrideDay['day'],
                    startTime: $overrideDay['start'],
                    endTime: $overrideDay['end']
                ), $item['override_days']),
                timeOffs: array_map(fn($timeOff) => new TimeOff(
                    name: $timeOff['name'],
                    startTime: $timeOff['start'],
                    endTime: $timeOff['end']
                ), $item['time_offs']),
                endDate: $item['end_date'],
                acceptsPerSlot: $item['accepts_per_slot']
            );

            resolve(EventCreator::class)->perform($eventCreatorRequest);
        }
    }
}
