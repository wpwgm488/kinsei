<?php

use App\Http\Controllers\AttendancePdfController;
use App\Http\Controllers\InvoicePdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/mypage');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::post('/logout', function () {
    auth()->logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->middleware('auth')->name('logout');

Route::get('/admin/attendances/pdf', AttendancePdfController::class)
    ->middleware('auth')
    ->name('admin.attendances.pdf');

Route::get('/mypage/attendances/pdf', AttendancePdfController::class)
    ->middleware('auth')
    ->name('user.attendances.pdf');
Route::get('/mypage/invoice/pdf', InvoicePdfController::class)
    ->middleware('auth')
    ->name('user.invoice.pdf');
