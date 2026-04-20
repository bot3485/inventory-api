<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity; // Add trait for activity logging
use Spatie\Activitylog\LogOptions;

/**
 * UserDetail Model
 * Stores technical metadata and network information for each user.
 */
class UserDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'browser',
        'device',
        'os',
        'last_active_at'
    ];

    /**
     * Get the user that owns the details.
     * This is the inverse of the hasOne relationship in the User model.
     * * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_active_at' => 'datetime', // Это заставит Laravel превращать строку в объект Carbon
        ];
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'ip_address', 'user_agent']) // Track changes to these specific fields
            ->logOnlyDirty()                   // Record only when data has actually changed
            ->dontSubmitEmptyLogs()            // Prevent creation of empty log entries
            ->setDescriptionForEvent(fn(string $eventName) => "UserDetail has been {$eventName}");
    }
}
