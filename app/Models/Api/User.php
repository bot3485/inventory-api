<?php

namespace App\Models\Api;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity; // Add trait for activity logging
use Spatie\Activitylog\LogOptions;

#[Fillable(['name', 'email', 'password', 'role'])] // Added 'role' to fillable attributes
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, LogsActivity;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: Each account has one record for technical details.
     */
    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    /**
     * Configure the activity logging options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role']) // Track changes to these specific fields
            ->logOnlyDirty()                   // Record only when data has actually changed
            ->dontSubmitEmptyLogs()            // Prevent creation of empty log entries
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}");
    }
}
