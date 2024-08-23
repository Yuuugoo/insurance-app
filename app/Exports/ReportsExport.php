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
            'reports.policy_num',
            'insurance_providers.name as insurance_provider_name', // Join and select insurance provider name
            'insurance_types.name as insurance_type_name', // Join and select insurance type name
            'reports.terms',
            'reports.gross_premium',
            'payment_modes.name as payment_mode_name', // Join and select payment mode name
            // 'reports.report_payment_mode_id',
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
        ->join('payment_modes', 'reports.report_payment_mode_id', '=', 'payment_modes.payment_id') // Join with payment_modes table
        ->whereNotNull('reports.arpr_num')  // Ensure ARPR number is not null
        ->get();
    }

    public function headings(): array
    {
        return [
            'SALES PERSON', // Update heading for sales person name
            'COST CENTER',
            'ARPR NUMBER',
            'ARPR DATE',
            'INCEPTION DATE',
            'ASSURED',
            'POLICY NUMBER',
            'INSURANCE PROVIDER',
            'INSURANCE TYPE',
            'TERMS',
            'GROSS PREMIUM',
            'MODE OF PAYMENT',
            'TOTAL PAYMENT',
            'PLATE NO',
            'CAR DETAILS',
            'POLICY STATUS',
            'MODE OF APPLICATION',
            'MORTAGAGEE OR FINANCING',
           
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Bold and bordered header row
            1 => [
                'font' => ['bold' => true],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
    
            // Style for "SALES PERSON" column header (A1)
            'A1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFA500'], // Orange color for Sales Person header
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
    
            // Style for all other headers (B1 to Q1)
            'B1:Q1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => '008000'], // Green color for other headers
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
    
            // Apply border and alignment to all cells from A2 to Q100
            'A2:Q100' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
