<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // To add at least 10 to show full table view
        Report::create([
            'created_at' => now(),
            'updated_at' => now(),
            'cost_center' => 'aurora',
            'arpr_num' => 'PR0421842',
            'arpr_date' => '2024-06-17',
            'inception_date' => '2024-06-18',
            'assured' => 'REYNALDO A. AUTENTICO',
            'policy_num' => '1052401004321',
            'insurance_prod' => 'oona',
            'application' => 'compre',
            'terms' => 'straight',
            'gross_premium' => 16824.54,
            'payment_mode' => 'paymaya',
            'total_payment' => 16824.54,
            'plate_num' => 'LAC4681',
            'car_details' => '2018 FORD RANGER 3.2 WILDTRAK DSL 4X4 A/T PICK-UP',
            'policy_status' => 'renewal',
            'financing_bank' => 0,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        Report::create([
            'created_at' => now(),
            'updated_at' => now(),
            'cost_center' => 'aurora',
            'arpr_num' => 'PR0421842',
            'arpr_date' => '2024-06-17',
            'inception_date' => '2024-06-18',
            'assured' => 'REYNALDO A. AUTENTICO',
            'policy_num' => '1052401004321',
            'insurance_prod' => 'oona',
            'application' => 'compre',
            'terms' => 'straight',
            'gross_premium' => 16824,
            'payment_mode' => 'paymaya',
            'total_payment' => 16824,
            'plate_num' => 'LAC4681',
            'car_details' => '2018 FORD RANGER 3.2 WILDTRAK DSL 4X4 A/T PICK-UP',
            'policy_status' => 'renewal',
            'financing_bank' => 0,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        
    }

    
}
