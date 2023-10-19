<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeController;
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
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
    ]);
});

Route::get('/', [AnimalController::class, 'index'])->name('home');
Route::get('/animals', [AnimalController::class, 'adminIndex'])->name('admin.home')->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('animals', AnimalController::class)->except('index');
    Route::resource('types', TypeController::class);
    Route::resource('breeds', BreedController ::class);
});

require __DIR__.'/auth.php';
