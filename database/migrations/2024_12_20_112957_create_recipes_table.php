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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('instructions');
            $table->json('ingredients');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');
            $table->string('tags')->nullable();
            $table->string('thumbnail');
            $table->string('youtube')->nullable();
            $table->string('source')->nullable();
            $table->string('image_source')->nullable();
            $table->boolean('creative_commons_confirmed')->default(false);
            $table->timestamp('date_modified')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
