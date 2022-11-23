<?php

namespace App\Models;

use App\Core\Domain\Entities\EEvent;
use App\Core\Domain\ValueObjects\Minute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    use HasFactory;

    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
      'id',
      'name',
      'description',
      'duration',
      'buffer_time',
      'end_date',
      'accept_per_slot'
    ];

    /**
     * @return EEvent
     */
    public function toDomainEntity(): EEvent {
        return (new EEvent(
            name: $this->name,
            description: $this->description,
            duration: Minute::from($this->duration),
            bufferTime: Minute::from($this->buffer_time),
            endDate: $this->end_date,
            acceptPerSlot: $this->accept_per_slot
        ))->setId($this->id);
    }
}
