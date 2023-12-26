<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AppsettingsConteroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenerousController;
use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\SongsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
    // return view('welcome');
});

Auth::routes();


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('admin/home', [HomeController::class, 'admin'])->name('admin.home');
  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('admin/roles', RoleController::class);
    Route::resource('admin/users', UserController::class);
    Route::get('admin/users/approved/{id}', [UserController::class, 'approved'])->name('users.approve');
    Route::get('admin/users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('admin/users/unapprove/{id}', [UserController::class, 'unapprove'])->name('users.unapprove');

    Route::resource('products', ProductController::class);
    Route::get('admin/albums', [AlbumController::class, 'index'])->name('album.list');
    Route::get('admin/album/{id}', [AlbumController::class, 'edit'])->name('album.edit');
    Route::post('admin/album-update', [AlbumController::class, 'update'])->name('album.update');
    Route::get('admin/album-delete/{id}', [AlbumController::class, 'delete'])->name('album.delete');
    Route::get('admin/generous', [GenerousController::class, 'index'])->name('generous.list');
    Route::get('admin/generous/{id}', [GenerousController::class, 'edit'])->name('generous.edit');
    Route::post('admin/generous-update', [GenerousController::class, 'update'])->name('generous.update');
    Route::get('admin/generous-delete/{id}', [GenerousController::class, 'delete'])->name('generous.delete');
    Route::get('admin/playlists', [PlaylistsController::class, 'index'])->name('playlist.list');
    Route::get('admin/playlist/{id}', [PlaylistsController::class, 'edit'])->name('playlist.edit');
    Route::post('admin/playlist-update', [PlaylistsController::class, 'update'])->name('playlist.update');
    Route::get('admin/playlist-delete/{id}', [PlaylistsController::class, 'delete'])->name('playlist.delete');
    //Songs
    Route::get('admin/songs', [SongsController::class, 'index'])->name('song.list');
    Route::get('admin/song/{id}', [SongsController::class, 'edit'])->name('song.edit');
    Route::post('admin/song-update', [SongsController::class, 'update'])->name('song.update');
    Route::get('admin/song-delete/{id}', [SongsController::class, 'delete'])->name('song.delete');
    
    //Songs
    Route::get('admin/app-settings', [AppsettingsConteroller::class, 'index'])->name('appSettings.list');
    Route::get('admin/app-settings/create', [AppsettingsConteroller::class, 'create'])->name('appSettings.create');
    Route::post('admin/app-settings/create', [AppsettingsConteroller::class, 'store'])->name('appSettings.store');
    Route::get('admin/app-settings/{id}', [AppsettingsConteroller::class, 'edit'])->name('appSettings.edit');
    Route::post('admin/app-settings', [AppsettingsConteroller::class, 'update'])->name('appSettings.update');
    Route::get('admin/app-settings/delete/{id}', [AppsettingsConteroller::class, 'delete'])->name('appSettings.delete');

});
?>