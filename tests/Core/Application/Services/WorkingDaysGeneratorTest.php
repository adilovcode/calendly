<?php

namespace Tests\Core\Application\Services;

use App\Core\Application\Repositories\IWorkingDaysRepository;
use App\Core\Application\Services\WorkingDaysGenerator;
use Carbon\Carbon;
use Exception;
use Tests\Core\Application\Helpers\EventCreator;
use Tests\Core\Application\Helpers\WorkingHoursCreator;
use Tests\TestCase;

class WorkingDaysGeneratorTest extends TestCase {
    use EventCreator, WorkingHoursCreator;

    /**
     * @return void
     */
    public function setUp(): void {
        parent::setUp();

        $this->mockInstance(IWorkingDaysRepository::class);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGenerate(): void {

        $this->travelTo(Carbon::parse('2022-11-23'));

        $expectedDates = [
            '2022-11-23',
            '2022-11-24',
            '2022-11-25',
            '2022-11-28',
            '2022-11-29',
        ];

        $event = $this->makeEvent();
        $workingHours = [
            $this->makeWorkingHours(['day' => 1]),
            $this->makeWorkingHours(['day' => 2]),
            $this->makeWorkingHours(['day' => 3]),
            $this->makeWorkingHours(['day' => 4]),
            $this->makeWorkingHours(['day' => 5]),
        ];

        $this
            ->mocked(IWorkingDaysRepository::class)
            ->shouldReceive('fetchByEventId')
            ->with($event->getId())
            ->andReturn($workingHours)
            ->once();

        $response = resolve(WorkingDaysGenerator::class)->generate($event);

        $this->assertCount(5, $response);

        foreach ($response as $item) {

            if (!in_array(Carbon::parse($item->getDate())->toDateString(), $expectedDates, true)) {
                throw new Exception('Working days are not right');
            }
        }
    }
}
