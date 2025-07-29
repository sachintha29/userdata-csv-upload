<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/', [CsvController::class, 'index'])->name('csv.upload');
// Route::post('/upload', [CsvController::class, 'store'])->name('csv.process');
// Route::get('/users', [CsvController::class, 'show'])->name('users.show');
Route::get('/', [ExcelController::class, 'index'])->name('excel.upload');
Route::post('/upload', [ExcelController::class, 'store'])->name('excel.process');
Route::get('/users', [ExcelController::class, 'show'])->name('users.show');
