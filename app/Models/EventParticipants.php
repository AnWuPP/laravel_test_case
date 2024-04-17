<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventParticipants
 * @package App\Models
 */
class EventParticipants extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
    ];

    public $timestamps = false;

    /**
     * Get the event that owns the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the user that owns the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the query builder for a given event ID.
     *
     * @param  int  $eventId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEventId($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Get the query builder for a given user ID.
     *
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
