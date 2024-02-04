<?php

use App\Http\Controllers\ChatRoomController;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Chat routes
    Route::get('/chats', [ChatRoomController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatRoomController::class, 'store'])->name('chat.store');
    Route::post('/chat/{chat}', [ChatRoomController::class, 'update'])->name('chat.update');
    Route::delete('/chat/image/{chat}', [ChatRoomController::class, 'delete_image'])->name('chat.image.delete');
    Route::post('/chat/image/{chat}', [ChatRoomController::class, 'store_image'])->name('chat.image.store');
    Route::get('/messages/{chat}', [ChatRoomController::class, 'messages'])->name('chat.messages.index');
    Route::post('/message', [ChatRoomController::class, 'store_message'])->name('chat.message.store');
});
