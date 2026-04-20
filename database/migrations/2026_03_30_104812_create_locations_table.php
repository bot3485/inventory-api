<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('prefix', 10)->nullable()->unique(); // Например: BAK, MSK, SRV

            // Иерархия (Self-referencing)
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('locations')
                ->onDelete('cascade');

            $table->string('type')->default('room'); // building, floor, room, rack, shelf
            $table->string('address')->nullable();

            // Гибкие данные: здесь будут фото (массив), ссылки на карты, координаты
            // Postgres JSONB позволяет искать внутри этих данных
            $table->jsonb('metadata')->nullable()->comment('Stores photos, map links, and extra info');

            $table->integer('sort_order')->default(0)->index();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
