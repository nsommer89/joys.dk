<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Pages\Web\Home;
use App\Livewire\Pages\Web\Events;
use App\Livewire\Pages\Web\Gallery;
use App\Livewire\Pages\Web\Info;
use App\Livewire\Pages\Web\Contact;
// use App\Livewire\Pages\MemberArea\Dashboard;
use App\Livewire\Pages\MemberArea\Messages;
use App\Livewire\Pages\MemberArea\Profile;
use App\Livewire\Pages\MemberArea\Explore;
use App\Livewire\Pages\MemberArea\Friends;
use App\Livewire\Pages\MemberArea\Chat;
use App\Livewire\Pages\MemberArea\Settings;
use App\Livewire\Pages\Web\WhoIsComing;

// Public routes
Route::get('/', Home::class)->name('home');
Route::get('/hvem-kommer-i-dag', WhoIsComing::class)->name('who-is-coming');
Route::get('/events', Events::class)->name('events');
Route::get('/events/{event:slug}', \App\Livewire\Pages\Web\Event::class)->name('event.show');
Route::get('/nyheder', \App\Livewire\Pages\Web\NewsArchive::class)->name('news.index');
Route::get('/nyheder/{news:slug}', \App\Livewire\Pages\Web\NewsPost::class)->name('news.show');
Route::get('/galleri', Gallery::class)->name('gallery');
Route::get('/info/{section?}', Info::class)->name('info');
Route::get('/kontakt', Contact::class)->name('contact');

// Authentication routes
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// Member area (protected)
// Member area (protected)
Route::middleware('auth')->prefix('medlem')->group(function () {
    Route::get('/beskeder', Messages::class)->name('member.messages');
    
    // Edit Profile (Min profil)
    Route::get('/profil', \App\Livewire\MemberArea\EditProfile::class)->name('member.profile.edit');
    
    // Public Profile View
    Route::get('/profil/{username}', \App\Livewire\MemberArea\PublicProfile::class)->name('member.profile.view');
    
    Route::get('/udforsk', Explore::class)->name('member.explore');
    Route::get('/venner', Friends::class)->name('member.friends');
    Route::get('/chat', Chat::class)->name('member.chat');
    Route::get('/indstillinger', Settings::class)->name('member.settings');
});
