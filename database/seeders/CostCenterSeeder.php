<?php

namespace Database\Seeders;

use App\Models\CostCenter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $costCenters = [
            'Aurora', 'Fairview', 'Feliz', 'Manila Bay', 'Makati', 'Market',
            'Robinsons Manila', 'Alabang', 'Dasmariñas', 'Pampanga', 'Marquee',
            'Baliwag', 'La Union', 'Lipa', 'Calamba', 'Cebu', 'Davao', 'Abreeza'
        ];
        
        foreach ($costCenters as $name) {
            CostCenter::create(['name' => $name]);
        }

        

    }
}
