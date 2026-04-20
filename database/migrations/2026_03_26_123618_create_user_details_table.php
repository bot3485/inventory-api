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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Сетевые данные
            $table->string('ip_address')->nullable();     // IP сотрудника
            $table->text('user_agent')->nullable();      // Полная строка браузера
            $table->string('browser')->nullable();       // Чисто название (Chrome, Firefox)
            $table->string('device')->nullable();        // Desktop, Mobile, и т.д.
            $table->string('os')->nullable();            // Windows, Linux, iOS

            $table->timestamp('last_active_at')->nullable(); // Когда последний раз "стучался" к нам
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
