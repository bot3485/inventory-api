<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('device_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_type_id')->constrained()->onDelete('cascade');

            $table->string('name'); // Например: Catalyst 2960-X
            $table->string('slug')->unique();

            // JSONB для шаблона характеристик
            // Например: {"ports": 24, "speed": "1Gbps", "poe": true}
            $table->jsonb('specs_template')->nullable();

            $table->string('image_url')->nullable(); // Фото самой железки
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Индексы для быстрого поиска по вендору и типу
            $table->index(['vendor_id', 'device_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_models');
    }
};
