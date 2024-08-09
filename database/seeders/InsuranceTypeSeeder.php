<?php

namespace Database\Seeders;

use App\Models\InsuranceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsuranceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insuranceTypes = [
            'COMPRE', 'TPL', 'FIRE', 'TRAVEL', 'PA', 'HOME',
            'CASUALTY', 'MARINE', 'CGL',
        ];
        
        foreach ($insuranceTypes as $name) {
            InsuranceType::create(['name' => $name]);
        }
    }
}
