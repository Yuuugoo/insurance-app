<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class DownloadPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Report $record)
    {
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('pdf', ['record' => $record]);
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download();
    }
}
