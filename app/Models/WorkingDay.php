<?php

namespace App\Models;

use App\Core\Domain\Entities\EWorkingDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingDay extends Model {
    use HasFactory;

    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'event_id',
        'day',
        'start_time',
        'end_time',
    ];

    /**
     * @return EWorkingDay
     */
    public function toDomainEntity(): EWorkingDay {
        return (new EWorkingDay(
            eventId: $this->event_id,
            day:  $this->day,
            startTime: $this->start_time,
            endTime:  $this->end_time
        ))->setId($this->id);
    }
}
