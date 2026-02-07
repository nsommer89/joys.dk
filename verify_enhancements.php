<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userCount = App\Models\User::count();
$parGenderId = App\Models\Gender::where('slug', 'par')->value('id');
$parUsers = App\Models\User::where('gender_id', $parGenderId)->get();

echo "Total Users: $userCount".PHP_EOL;

echo '--- Couples Verification ---'.PHP_EOL;
echo 'Found '.$parUsers->count()." 'Par' users.".PHP_EOL;
foreach ($parUsers->take(3) as $u) {
    if ($u->userProfile) {
        echo "User {$u->id} (Par) has ".$u->userProfile->profilePeople->count().' profile people. (Expected: 2)'.PHP_EOL;
    }
}

echo '--- Preferences Verification ---'.PHP_EOL;
$usersWithPrefs = App\Models\User::has('preferences')->count();
echo "Users with preferences: $usersWithPrefs".PHP_EOL;

echo '--- Events Verification ---'.PHP_EOL;
$usersWithEvents = App\Models\User::has('events')->count();
echo "Users attending events: $usersWithEvents".PHP_EOL;

echo '--- Friendships Verification ---'.PHP_EOL;
$pending = App\Models\Friendship::where('status', 'pending')->count();
echo "Pending friendships: $pending".PHP_EOL;

echo '--- Locked Albums Verification ---'.PHP_EOL;
$lockedAlbums = App\Models\UserAlbum::whereNotNull('password')->count();
echo "Locked albums: $lockedAlbums".PHP_EOL;

$albumWithHint = App\Models\UserProfile::where('description', 'LIKE', '%Psst! Min private album kode er%')->first();
if ($albumWithHint) {
    echo 'Found profile with password hint: '.substr($albumWithHint->description, -50).PHP_EOL;
} else {
    echo 'No profiles with password hints found (might be random chance, check ratio).'.PHP_EOL;
}
