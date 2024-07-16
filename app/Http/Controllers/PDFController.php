<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class PDFController extends Controller
{
    public function __invoke(Report $record)
    {   

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('pdf', ['record' => $record]);
        $pdf->setPaper('legal', 'portrait');
        return $pdf->stream();
    }
    

}
