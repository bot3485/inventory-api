<?php

namespace App\Models\Api; // Рекомендую стандартный путь App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DeviceType extends Model
{
    /** @use HasFactory<\Database\Factories\DeviceTypeFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description'
    ];

    /**
     * Настройка логов активности.
     * Будем фиксировать, если кто-то изменит название типа или его иконку.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'icon', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Device Type has been {$eventName}");
    }

    /**
     * Связь: Один тип (например, "Switch") может иметь много моделей (Catalyst 2960, Nexus 9000 и т.д.)
     */
    public function models(): HasMany
    {
        return $this->hasMany(DeviceModel::class);
    }
}
