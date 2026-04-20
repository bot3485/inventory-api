<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DeviceModel extends Model
{
    use LogsActivity;

    protected $fillable = ['vendor_id', 'device_type_id', 'name', 'slug', 'specs_template', 'image_url', 'description', 'is_active'];

    protected $casts = [
        'specs_template' => 'array',
        'is_active' => 'boolean'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    // Связи
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function type()
    {
        return $this->belongsTo(DeviceType::class, 'device_type_id');
    }
}
