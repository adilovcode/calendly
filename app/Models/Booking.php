<?php

namespace App\Models;

use App\Core\Domain\Entities\EBooking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    use HasFactory;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'event_id',
        'first_name',
        'last_name',
        'email',
        'booking_date'
    ];

    /**
     * @return EBooking
     */
    public function toDomainEntity(): EBooking {
        return (new EBooking(
            eventId: $this->event_id,
            firstName: $this->first_name,
            lastName: $this->last_name,
            email: $this->email,
            bookingDate: $this->booking_date,
        ))->setId($this->id);
    }
}
