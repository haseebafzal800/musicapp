<?php

use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\AlbumsController;
use App\Http\Controllers\Api\GenerousController;
use App\Http\Controllers\Api\PlaylistsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return json_response(['status' => true, 'code' => 200, 'message' => 'APIs are running', 'data' => '']);
});
Route::post('login', [UsersController::class, 'login']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('logout', [UsersController::class, 'logout']);
    //Album
    Route::get('albums', [AlbumsController::class, 'index'])->name('album.list');
    Route::post('album-create', [AlbumsController::class, 'store'])->name('album.create');
    Route::get('album/{id}', [AlbumsController::class, 'edit'])->name('album.edit');
    Route::post('album-update/', [AlbumsController::class, 'update'])->name('album.update');
    Route::get('album-delete/{id}', [AlbumsController::class, 'delete'])->name('album.delete');
    // Generous
    Route::get('generouses', [GenerousController::class, 'index'])->name('generous.list');
    Route::post('generous-create', [GenerousController::class, 'store'])->name('generous.create');
    Route::get('generous/{id}', [GenerousController::class, 'edit'])->name('generous.edit');
    Route::post('generous-update/', [GenerousController::class, 'update'])->name('generous.update');
    Route::get('generous-delete/{id}', [GenerousController::class, 'delete'])->name('generous.delete');
    // Playlist
    Route::get('playlists', [PlaylistsController::class, 'index'])->name('playlist.list');
    Route::post('playlist-create', [PlaylistsController::class, 'store'])->name('playlist.create');
    Route::get('playlist/{id}', [PlaylistsController::class, 'edit'])->name('playlist.edit');
    Route::post('playlist-update/', [PlaylistsController::class, 'update'])->name('playlist.update');
    Route::get('playlist-delete/{id}', [PlaylistsController::class, 'delete'])->name('playlist.delete');
});
