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
        Schema::create('user_album_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_album_id')->constrained()->cascadeOnDelete();
            $table->string('path');           // Full-size image path
            $table->string('thumbnail_path'); // 400x400 thumbnail path
            $table->boolean('is_processing')->default(true);
            $table->timestamps();

            $table->index('user_album_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_album_images');
    }
};
