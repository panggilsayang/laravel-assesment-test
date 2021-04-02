<?php

use App\Http\Controllers\Task\TaskController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
    // return User::query()->first();
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('tasks.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->prefix('tasks')->name('tasks.')->group(function() {
    Route::get('/',[TaskController::class,'index'])->name('index');
    Route::get('/create',[TaskController::class,'create'])->name('create');
    Route::post('/store',[TaskController::class,'store'])->name('store');
    Route::get('/edit/{task}',[TaskController::class,'edit'])->name('edit');
    Route::post('/edit/{task}',[TaskController::class,'update'])->name('update');
    Route::post('/edit/{task}',[TaskController::class,'update'])->name('update');
    Route::post('/delete/{task}',[TaskController::class,'destroy'])->name('delete')->middleware('can:task-delete,task');
});

// require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
