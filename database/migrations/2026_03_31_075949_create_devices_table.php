<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            // Связи
            $table->foreignId('device_model_id')->constrained()->onDelete('restrict');
            $table->foreignId('location_id')->constrained()->onDelete('restrict');

            // Идентификаторы
            $table->string('serial_number')->nullable()->unique()->index();
            $table->string('inventory_number')->nullable()->unique()->index();

            // Статус
            $table->string('status')->default('stock');

            // Используем string для совместимости, валидацию вынесем в логику приложения
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();

            // JSONB — тут Laravel отрабатывает идеально
            $table->jsonb('specs')->nullable();

            $table->date('purchase_date')->nullable();
            $table->date('warranty_expire')->nullable();

            $table->text('description')->nullable();
            $table->timestamps();

            // Индексы
            $table->index(['location_id', 'status']);
            $table->index('device_model_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
