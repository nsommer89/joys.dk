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
        // Don't use Eloquent as it might have constraints/events. Use DB facade.
        $users = \Illuminate\Support\Facades\DB::table('users')->whereNull('username')->orWhere('username', '')->get();

        foreach ($users as $user) {
            $baseUsername = \Illuminate\Support\Str::slug(explode('@', $user->email)[0]);
            $username = $baseUsername;
            $counter = 1;

            // Ensure uniqueness
            while (\Illuminate\Support\Facades\DB::table('users')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed as we don't want to clear usernames
    }
};
