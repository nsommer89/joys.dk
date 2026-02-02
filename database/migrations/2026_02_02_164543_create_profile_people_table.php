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
        Schema::create('profile_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_profile_id');
            $table->foreignId('gender_id');
            
            $table->string('name')->nullable();
            $table->integer('age')->nullable();
            $table->integer('height')->nullable(); // cm
            $table->integer('weight')->nullable(); // kg
            $table->timestamps();

            $table->foreign('user_profile_id')->references('id')->on('user_profiles')->cascadeOnDelete();
            $table->foreign('gender_id')->references('id')->on('genders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_people');
    }
};
