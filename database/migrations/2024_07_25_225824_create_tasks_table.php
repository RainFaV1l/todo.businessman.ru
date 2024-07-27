<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public \Carbon\Carbon $date;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $date = \Carbon\Carbon::now();
            $date = $date->addDay();
            $table->id();
            $table->string('name', 2000);
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('expired_at')->default($date);
            $table->enum('status', ['Просрочено', 'Выполнено', 'Активно'])->default('Активно');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
