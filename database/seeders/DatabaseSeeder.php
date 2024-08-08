<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(CostCenterSeeder::class);
        $this->call(InsuranceTypeSeeder::class);
        $this->call(InsuranceProviderSeeder::class);
        $this->call(PaymentSeeder::class);
        $this->call(RolesAndPermissionSeeder::class);
        $this->call(ReportSeeder::class);
        // \App\Models\User::factory(1)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test Admin',
        //     'email' => 'admin@test.com',
        // ]);
    }
}
