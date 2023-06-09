<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users-and-friend-requests', [UserController::class, 'usersAndFriendRequests'])
        ->name('users-and-friend-requests');

    Route::post('/send-friend-request/{recipient}', [
        UserController::class, 'sendFriendRequest',
    ])->name('send-friend-request');

    Route::delete('/cancel-sent-friend-request/{friendRequest}', [
        UserController::class, 'cancelSentFriendRequest',
    ])->name('cancel-sent-friend-request');

    Route::post('/accept-friend-request/{friendRequest}', [
        UserController::class, 'acceptFriendRequest',
    ])->name('accept-friend-request');

    Route::post('/refuse-friend-request/{friendRequest}', [
        UserController::class, 'refuseFriendRequest',
    ])->name('refuse-friend-request');

    Route::delete('/remove-friend/{friend}', [UserController::class, 'removeFriend'])
        ->name('remove-friend');
});

require __DIR__ . '/auth.php';
