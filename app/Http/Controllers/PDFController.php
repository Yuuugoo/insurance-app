<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function __invoke(Report $report)
    {
        return view('pdf', ['record' => $report]);
    }




}
