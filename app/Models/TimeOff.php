<?php

namespace App\Models;

use App\Core\Domain\Entities\ETimeOff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeOff extends Model {
    use HasFactory;

    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'event_id',
        'title',
        'start_time',
        'end_time'
    ];

    /**
     * @return ETimeOff
     */
    public function toDomainEntity(): ETimeOff {
        return (new ETimeOff(
            title: $this->title,
            eventId: $this->event_id,
            startTime: $this->start_time,
            endTime: $this->end_time
        ))->setId($this->id);
    }
}
