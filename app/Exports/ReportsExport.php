<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Report;
use App\Models\User;

class ReportsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Report::select(
            'users.name as sales_person_name', // Select sales person's name
            'cost_centers.name as cost_center_name', // Join and select cost center name
            'reports.arpr_num',
            'reports.arpr_date',
            'reports.inception_date',
            'reports.assured',
            'insurance_providers.name as insurance_provider_name', // Join and select insurance provider name
            'insurance_types.name as insurance_type_name', // Join and select insurance type name
            'reports.terms',
            'reports.gross_premium',
            'reports.report_payment_mode_id',
            'reports.total_payment',
            'reports.plate_num',
            'reports.car_details',
            'reports.policy_status',
            'reports.application',
            'reports.financing_bank'
            
        )
        ->join('users', 'reports.sales_person_id', '=', 'users.id') // Join with users table
        ->join('cost_centers', 'reports.report_cost_center_id', '=', 'cost_centers.cost_center_id') // Join with cost_centers table
        ->join('insurance_providers', 'reports.report_insurance_prod_id', '=', 'insurance_providers.insurance_provider_id') // Join with insurance_providers table
        ->join('insurance_types', 'reports.report_insurance_type_id', '=', 'insurance_types.insurance_type_id') // Join with insurance_types table
        ->get();
    }

    public function headings(): array
    {
        return [
            'SALES PERSON', // Update heading for sales person name
            'COST CENTER ID',
            'ARPR NUMBER',
            'ARPR DATE',
            'INCEPTION DATE',
            'ASSURED',
            'INSURANCE PROVIDER ID',
            'INSURANCE TYPE ID',
            'TERMS',
            'GROSS PREMIUM',
            'PAYMENT MODE ID',
            'TOTAL PAYMENT',
            'PLATE NUMBER',
            'CAR DETAILS',
            'POLICY STATUS',
            'APPLICATION',
            'FINANCING BANK',
           
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFA500'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            'B1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => '008000'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            'C1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'ADD8E6'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            'D1:N1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'F0F0F0'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            'A:N' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'A2:N100' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // COST CENTER ID
            'B' => 20, // ARPR NUMBER
            'C' => 20, // ARPR DATE
            'D' => 20, // INCEPTION DATE
            'E' => 20, // ASSURED
            'F' => 20, // INSURANCE PROVIDER ID
            'G' => 20, // INSURANCE TYPE ID
            'H' => 15, // TERMS
            'I' => 20, // GROSS PREMIUM
            'J' => 20, // PAYMENT MODE ID
            'K' => 20, // TOTAL PAYMENT
            'L' => 20, // PLATE NUMBER
            'M' => 30, // CAR DETAILS
            'N' => 20, // POLICY STATUS
            'O' => 20, // APPLICATION
            'P' => 30, // FINANCING BANK
            'Q' => 30, // SALES PERSON NAME
        ];
    }
}
