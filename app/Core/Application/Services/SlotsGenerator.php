<?php

namespace App\Core\Application\Services;

use App\Core\Domain\ValueObjects\Minute;
use App\Core\Domain\ValueObjects\Slot;
use Carbon\Carbon;

class SlotsGenerator {
    /**
     * @param string $start
     * @param string $end
     * @param Minute $duration
     * @return array
     */
    public function generate(string $start, string $end, Minute $duration): array {
        $slots = [];

        $startInMinutes = Carbon::now()->startOfDay()->diffInMinutes($start);
        $endInMinutes = Carbon::now()->startOfDay()->diffInMinutes($end);

        for ($i = $startInMinutes; $i <= $endInMinutes; $i += $duration->getValue()) {

            $slots[] = new Slot(
                time: Carbon::now()->startOfDay()->addMinutes($i)->format('H:i')
            );
        }

        array_pop($slots);

        return $slots;
    }
}