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
        Schema::table('recipes', function (Blueprint $table) {
            $table->string('meal_id')->nullable()->after('id'); // Najpierw dodaj pole jako nullable
        });

        // Dodaj unikalny indeks w osobnym kroku
        \DB::statement("UPDATE recipes SET meal_id = NULL WHERE meal_id IS NULL"); // Zapewnia brak duplikatów
        Schema::table('recipes', function (Blueprint $table) {
            $table->unique('meal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropUnique(['meal_id']);
            $table->dropColumn('meal_id');
        });
    }
};
