<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Location extends Model
{
    use LogsActivity;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($location) {
            if (empty($location->slug)) {
                $location->slug = \Illuminate\Support\Str::slug($location->name);
            }
        });
    }
    protected $fillable = [
        'name',
        'slug',
        'prefix',
        'parent_id',
        'type',
        'address',
        'metadata',
        'sort_order',
        'description',
        'is_active'
    ];

    protected $casts = [
        'metadata' => 'array', // Это критично для работы с JSONB
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Связь: кто "папа" этой локации
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    // Связь: кто "дети" этой локации (комнаты в здании, шкафы в комнате)
    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id')->orderBy('sort_order');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'prefix', 'parent_id', 'type', 'is_active', 'metadata'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Location has been {$eventName}");
    }
}
