<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add gender_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('gender_id')->nullable()->after('username')->constrained();
        });

        // 2. Migrate existing data
        // We only update users that have a profile with a gender.
        $profiles = DB::table('user_profiles')->whereNotNull('gender_id')->get();
        foreach ($profiles as $profile) {
            DB::table('users')
                ->where('id', $profile->user_id)
                ->update(['gender_id' => $profile->gender_id]);
        }

        // 3. Drop gender_id from user_profiles
        Schema::table('user_profiles', function (Blueprint $table) {
            // Drop foreign key first. Convention: table_column_foreign
            $table->dropForeign(['gender_id']);
            $table->dropColumn('gender_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add gender_id back to user_profiles
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->foreignId('gender_id')->nullable()->constrained();
        });

        // 2. Migrate data back
        $users = DB::table('users')->whereNotNull('gender_id')->get();
        foreach ($users as $user) {
            DB::table('user_profiles')
                ->where('user_id', $user->id)
                ->update(['gender_id' => $user->gender_id]);
        }

        // 3. Drop gender_id from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['gender_id']);
            $table->dropColumn('gender_id');
        });
    }
};
