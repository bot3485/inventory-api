<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Device extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = [
        'device_model_id',
        'location_id',
        'serial_number',
        'inventory_number',
        'status',
        'ip_address',
        'mac_address',
        'specs',
        'purchase_date',
        'warranty_expire',
        'description'
    ];

    protected $casts = [
        'specs' => 'array',
        'purchase_date' => 'date',
        'warranty_expire' => 'date',
    ];

    // Настройка логов
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['location_id', 'status', 'ip_address', 'specs']) // Самое важное
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Device was {$eventName}");
    }

    // Связи
    public function model()
    {
        return $this->belongsTo(DeviceModel::class, 'device_model_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
