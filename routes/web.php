<?php

use App\Http\Controllers\BarChartController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\DownloadReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ViewPDFController;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('pdf/report/{record}', ViewPDFController::class)->name('pdfview');
Route::get('pdf/report/{record}/download', DownloadPdfController::class)->name('pdfdownload');
Route::fallback(function () {
    return redirect()->route('filament.admin.auth.login');
});


// Route::get('/login',
//     fn() => redirect(route('filament.admin.auth.login'))
// )->name('login');