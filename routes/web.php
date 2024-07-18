<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\DownloadReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ViewPDFController;

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