<?php

use App\Filament\Pages\PerBranchPage;
use App\Filament\Pages\PerMonthPage;
use App\Filament\Pages\PerSalespersonPage;
use App\Http\Controllers\BarChartController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\DownloadReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ViewPDFController;
use Illuminate\Support\Facades\Auth;
use App\Filament\Pages\SummaryReports;


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


Route::fallback(function () {
    return redirect()->route('filament.admin.auth.login');
});
Route::middleware(['auth'])->group(function () {
    // Route::get('pdf/report/{record}', ViewPDFController::class)->name('pdfview');
    // Route::get('pdf/report/{record}/download', DownloadPdfController::class)->name('pdfdownload');
    Route::get('/export', [PerBranchPage::class, 'export'])->name('exportData');
    Route::get('/export-salesperson', [PerSalespersonPage::class, 'export'])->name('exportPerSalesperson');
    Route::get('/export-per-month', [PerMonthPage::class, 'export'])->name('exportPerMonthData');
});

