<?php

namespace Database\Seeders;

use App\Models\InsuranceProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsuranceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insuranceProviders = [
            'MCT', 'OONA', 'FPG', 'OAC', 'CIBELES',
        ];
        
        foreach ($insuranceProviders as $name) {
            InsuranceProvider::create(['name' => $name]);
        }
    }
}
