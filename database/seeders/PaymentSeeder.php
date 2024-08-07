<?php

namespace Database\Seeders;

use App\Models\PaymentMode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insuranceTypes = [
            'CASH', 'CHECK', 'PAYMAYA', 'ONLINE', 'GCASH', 
            'PAYNAMICS','BDO CC'
        ];
        
        foreach ($insuranceTypes as $name) {
            PaymentMode::create(['name' => $name]);
        }
    }
}
