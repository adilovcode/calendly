<?php

namespace Tests\Core\Application\Services;

use App\Core\Application\Services\SlotsGenerator;
use App\Core\Domain\ValueObjects\Minute;
use App\Core\Domain\ValueObjects\Slot;
use Tests\TestCase;

class SlotsGeneratorsTest extends TestCase {
    /**
     * @return void
     */
    public function testGenerate(): void {
        $expectedSlots = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30'];

        $slots = resolve(SlotsGenerator::class)->generate('09:00', '12:00', Minute::from(30));

        $slots = array_map(fn(Slot $slot) => $slot->getTime(), $slots);

        $this->assertEquals($expectedSlots, $slots);
    }
}
