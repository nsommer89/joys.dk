<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure any existing nulls are populated
        DB::table('users')->whereNull('username')->orderBy('id')->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'username' => 'user_' . $user->id . '_' . bin2hex(random_bytes(2))
            ]);
        });

        $defaultGenderId = DB::table('genders')->where('slug', 'mand')->value('id') ?? 1;
        DB::table('users')->whereNull('gender_id')->update([
            'gender_id' => $defaultGenderId
        ]);

        // 2. Apply constraints
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
            $table->unsignedBigInteger('gender_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->change();
            $table->unsignedBigInteger('gender_id')->nullable()->change();
        });
    }
};
