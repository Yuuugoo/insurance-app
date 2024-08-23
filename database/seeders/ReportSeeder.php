<?php

namespace Database\Seeders;

use App\Models\CostCenter;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // #8
        $grossPremium = 16824.54;
        $totalPayment = 16824.54;
        $paymentBalance = $grossPremium - $totalPayment;
        $user = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['cashier']);
        })->first();

        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR0421842',
            'arpr_date' => '2024-06-17',
            'inception_date' => '2024-06-18',
            'assured' => 'REYNALDO A. AUTENTICO',
            'policy_num' => 'MC-AAP-DV-24-0000142-00',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LAC4681',
            'car_details' => '2018 FORD RANGER 3.2 WILDTRAK DSL 4X4 A/T PICK-UP',
            'policy_status' => 'renewal',
            'financing_bank' => null,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // #9
        $grossPremium = 19165.32;
        $totalPayment = 8582.66;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100008',
            'arpr_date' => '2024-06-25',
            'inception_date' => '2024-06-06',
            'assured' => 'GENE Q. SESCON',
            'policy_num' => 'MC-AAP-DV-24-0000102-00',
            'terms' => '2 Terms',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LAM5683',
            'car_details' => '2023 MITSUBISHI XPANDER CROSS 1 WAGON',
            'policy_status' => 'new',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // #10
        $grossPremium = 11074.48;
        $totalPayment = 4537.24;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => '2024-05-05',
            'updated_at' => '2024-05-05',
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100013',
            'arpr_date' => '2024-08-28',
            'inception_date' => '2024-06-05',
            'assured' => 'EDILBERTO Q. LLENADO',
            'policy_num' => 'MC-AAP-DV-24-0000108-00',
            'terms' => '2 Terms',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LAD5854',
            'car_details' => '2019 NISSAN NAVARA',
            'policy_status' => 'new',
            'financing_bank' => null,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // #11
        $grossPremium = 10768.54;
        $totalPayment = 10768.54;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => '2024-04-05',
            'updated_at' => '2024-04-05',
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100016',
            'arpr_date' => '2024-09-17',
            'inception_date' => '2024-06-06',
            'assured' => 'ALVIR ALEXIS C. SANCHEZ',
            'policy_num' => 'MC-AAP-DV-23-0000069-00',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LAK2490',
            'car_details' => '2022 NISSAN ALMERA 1.0 VE TURBO SEDAN',
            'policy_status' => 'renewal',
            'financing_bank' => null,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // #13
        $grossPremium = 11506.08;
        $totalPayment = 11506.08;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => '2024-07-05',
            'updated_at' => '2024-07-05',
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100020',
            'arpr_date' => '2024-02-17',
            'inception_date' => '2024-06-10',
            'assured' => 'GIRELL APRIL P. LUMUMA',
            'policy_num' => 'MC-AAP-DV-24-0000110-00',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'GAT2716',
            'car_details' => '2021 NISSAN ALMERA 1.5-L E SEDAN',
            'policy_status' => 'new',
            'financing_bank' => null,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        //30
        $grossPremium = 16824;
        $totalPayment = 16824;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => '2024-05-05',
            'updated_at' => '2024-05-05',
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100043',
            'arpr_date' => '2024-06-20',
            'inception_date' => '2024-06-20',
            'assured' => 'ERWIN C. IDEMNE',
            'policy_num' => 'MC-AAP-DV-23-0000074-01',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LAF5733',
            'car_details' => '2019 MITSUBISHI MONTERO SPT GLS SUV',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        //31
        $grossPremium = 11575.82;
        $totalPayment = 5000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => '2024-05-05',
            'updated_at' => '2024-05-05',
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100044',
            'arpr_date' => '2024-06-28',
            'inception_date' => '2024-06-20',
            'assured' => 'JAY FRANCESS T. MANUEL',
            'policy_num' => 'MC-AAP-DV-22-0000040-01',
            'terms' => '2 Terms',
            'gross_premium' => $grossPremium,
            'total_payment' =>  $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LAH7908',
            'car_details' => '2021 MITSUBISHI MIRAGE G4 GLX 1',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        //32
        $grossPremium = 17804.86;
        $totalPayment = 17804.86;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100045',
            'arpr_date' => '2024-07-10',
            'inception_date' => '2024-06-21',
            'assured' => 'JERRY L. CRISPINO',
            'policy_num' => 'MC-AAP-DV-24-0000129-00',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'NIF5819',
            'car_details' => '2023 TOYOTA HILUX 2.8L CONQUEST',
            'policy_status' => 'new',
            'financing_bank' => null,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        //33
        $grossPremium = 10459.62;
        $totalPayment = 10459.62;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => '2024-03-05',
            'updated_at' => '2024-03-05',
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100046',
            'arpr_date' => '2024-06-27',
            'inception_date' => '2024-03-06',
            'assured' => 'AGNES V. DINNEEN',
            'policy_num' => 'MC-AAP-DV-23-0000039-00',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'AAG6850',
            'car_details' => '2014 SUBARU XV 2.0I-S CVT',
            'policy_status' => 'renewal',
            'financing_bank' => null,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        //34
        $grossPremium = 8550.34;
        $totalPayment = 8550.34;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => '2024-04-05',
            'updated_at' => '2024-04-05',
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100047',
            'arpr_date' => '2024-06-17',
            'inception_date' => '2024-06-09',
            'assured' => 'MADUNA SALARDA MAASIN',
            'policy_num' => 'MC-AAP-DV-23-0000067-00',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'AAG7762',
            'car_details' => '2014 MITSUBISHI STRADAGLS SPT PICK-UP',
            'policy_status' => 'renewal',
            'financing_bank' => null,
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

            //35
        $grossPremium = 17909.20;
        $totalPayment = 17909.20;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => '2024-07-05',
            'updated_at' => '2024-07-05',
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR100050',
            'arpr_date' => '2024-06-17',
            'inception_date' => '2024-07-12',
            'assured' => 'GLADYS LYN S. LAPUT',
            'policy_num' => 'MC-AAP-DV-23-0000082-00',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LAK3365',
            'car_details' => '2023 MITSUBISHI XPANDER GLS 1.5 WAGON',
            'policy_status' => 'renewal',
            'financing_bank' => 'SECURITY BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        //Random 1
        $grossPremium = 15670;
        $totalPayment = 15000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR234567',
            'arpr_date' => '2024-03-21',
            'inception_date' => '2024-03-25',
            'assured' => 'Maria L. Santos',
            'policy_num' => 'MC-AAP-DV-23-0000189-02',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'ABC1234',
            'car_details' => '2022 TOYOTA FORTUNER 2.4 G DIESEL AT',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // Random 2
        $grossPremium = 9850;
        $totalPayment = 9800;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR876543',
            'arpr_date' => '2024-09-01',
            'inception_date' => '2024-09-08',
            'assured' => 'Juan D. Cruz',
            'policy_num' => 'MC-AAP-DV-23-0000456-03',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'XYZ9876',
            'car_details' => '2021 HONDA CIVIC RS TURBO CVT',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // Random 3
        $grossPremium = 12340;
        $totalPayment = 12000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR345678',
            'arpr_date' => '2024-09-19',
            'inception_date' => '2024-11-19',
            'assured' => 'Elena G. Reyes',
            'policy_num' => 'MC-AAP-DV-23-0000789-04',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LMN5678',
            'car_details' => '2023 NISSAN NAVARA VL 4X4 AT',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        
        // Random 4

        $grossPremium = 8760;
        $totalPayment = 8760;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR987654',
            'arpr_date' => '2024-09-03',
            'inception_date' => '2024-07-03',
            'assured' => 'Roberto Q. Lim',
            'policy_num' => 'MC-AAP-DV-23-0001234-05',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'PQR2468',
            'car_details' => '2020 MITSUBISHI XPANDER GLS SPORT AT',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 5
        $grossPremium = 10980;
        $totalPayment = 10500;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR456789',
            'arpr_date' => '2024-08-15',
            'inception_date' => '2024-04-22',
            'assured' => 'Sophia T. Gonzales',
            'policy_num' => 'MC-AAP-DV-23-0005678-06',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'STU1357',
            'car_details' => '2022 MAZDA CX-5 AWD SPORT AT',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 6
        $grossPremium = 13570;
        $totalPayment = 13000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR654321',
            'arpr_date' => '2024-08-13',
            'inception_date' => '2024-08-13',
            'assured' => 'Michael B. Tan',
            'policy_num' => 'MC-AAP-DV-23-0009876-07',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'VWX2468',
            'car_details' => '2021 FORD RANGER WILDTRAK 4X4 AT',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // Random 7

        $grossPremium = 7890;
        $totalPayment = 7890;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR135790',
            'arpr_date' => '2024-12-07',
            'inception_date' => '2024-12-07',
            'assured' => 'Patricia R. Ocampo',
            'policy_num' => 'MC-AAP-DV-23-0002468-08',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'YZA1357',
            'car_details' => '2023 ISUZU MU-X LS-E 4X2 AT',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // Random 8
        $grossPremium = 11230;
        $totalPayment = 11000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => rand(1, 18),
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR246802',
            'arpr_date' => '2024-05-31',
            'inception_date' => '2024-05-31',
            'assured' => 'Gabriel F. Mendoza',
            'policy_num' => 'MC-AAP-DV-23-0003579-09',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'BCD9876',
            'car_details' => '2022 KIA SORENTO 2.2 CRDi EX 4X2 AT',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 9
        $grossPremium = 9870;
        $totalPayment = 9500;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 8,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR357913',
            'arpr_date' => '2024-10-16',
            'inception_date' => '2024-10-16',
            'assured' => 'Isabella M. Villanueva',
            'policy_num' => 'MC-AAP-DV-23-0004680-10',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'EFG1234',
            'car_details' => '2021 SUZUKI JIMNY GLX AT',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // Random 10
        $grossPremium = 14560;
        $totalPayment = 14560;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 9,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR468024',
            'arpr_date' => '2024-02-12',
            'inception_date' => '2024-02-12',
            'assured' => 'Rafael H. Dela Cruz',
            'policy_num' => 'MC-AAP-DV-23-0007913-11',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'HIJ5678',
            'car_details' => '2023 HYUNDAI TUCSON GLS+ 2.0 CRDi 8DCT',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 11
        $grossPremium = 15678;
        $totalPayment = 14500;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 10,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR789012',
            'arpr_date' => '2024-07-09',
            'inception_date' => '2024-07-09',
            'assured' => 'MARIA L. SANTOS',
            'policy_num' => 'MC-AAP-DV-23-0000189-02',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'ABC1234',
            'car_details' => '2022 TOYOTA FORTUNER LTD SUV',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);

        // Random 12
        $grossPremium = 9876;
        $totalPayment = 9876;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 12,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR345678',
            'arpr_date' => '2024-07-22',
            'inception_date' => '2024-07-22',
            'assured' => 'JOHN D. CRUZ',
            'policy_num' => 'MC-AAP-DV-23-0000245-01',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'XYZ9876',
            'car_details' => '2021 HONDA CIVIC RS SEDAN',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 13
        $grossPremium = 22345;
        $totalPayment = 20000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 13,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR901234',
            'arpr_date' => '2024-07-14',
            'inception_date' => '2024-07-14',
            'assured' => 'ANNA M. REYES',
            'policy_num' => 'MC-AAP-DV-23-0000312-03',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'LMN5678',
            'car_details' => '2023 NISSAN NAVARA PRO-4X PICKUP',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 14
        $grossPremium = 7890;
        $totalPayment = 7000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 6,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR567893',
            'arpr_date' => '2024-07-29',
            'inception_date' => '2024-07-29',
            'assured' => 'ROBERTO G. TAN',
            'policy_num' => 'MC-AAP-DV-23-0000423-01',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'PQR2468',
            'car_details' => '2020 MAZDA CX-5 AWD SUV',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 15
        $grossPremium = 13579;
        $totalPayment = 13579;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 1,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR123456',
            'arpr_date' => '2024-07-07',
            'inception_date' => '2024-07-07',
            'assured' => 'ELENA F. GARCIA',
            'policy_num' => 'MC-AAP-DV-23-0000567-02',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'STU1357',
            'car_details' => '2022 FORD RANGER WILDTRAK PICKUP',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 16
        $grossPremium = 18765;
        $totalPayment = 17000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 1,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR789012',
            'arpr_date' => '2024-07-18',
            'inception_date' => '2024-07-18',
            'assured' => 'MICHAEL B. LIM',
            'policy_num' => 'MC-AAP-DV-23-0000678-01',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'VWX8642',
            'car_details' => '2021 SUBARU FORESTER GT EDITION SUV',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 17
        $grossPremium = 10987;
        $totalPayment = 10000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 2,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR345699',
            'arpr_date' => '2024-07-24',
            'inception_date' => '2024-07-24',
            'assured' => 'SOPHIA R. SANTOS',
            'policy_num' => 'MC-AAP-DV-23-0000789-03',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'YZA2468',
            'car_details' => '2023 KIA SORENTO HYBRID SUV',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 18
        $grossPremium = 25432;
        $totalPayment = 25000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 2,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR901234',
            'arpr_date' => '2024-07-12',
            'inception_date' => '2024-07-12',
            'assured' => 'DANIEL P. GONZALES',
            'policy_num' => 'MC-AAP-DV-23-0000890-02',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'BCD1357',
            'car_details' => '2022 ISUZU D-MAX LS-E PICKUP',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 19
        $grossPremium = 8765;
        $totalPayment = 8500;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 4,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR567899',
            'arpr_date' => '2024-07-31',
            'inception_date' => '2024-07-31',
            'assured' => 'OLIVIA T. FERNANDEZ',
            'policy_num' => 'MC-AAP-DV-23-0000901-01',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'EFG9753',
            'car_details' => '2021 HYUNDAI TUCSON GLS SUV',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 19
        $grossPremium = 8765;
        $totalPayment = 8500;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 1,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR567896',
            'arpr_date' => '2024-07-31',
            'inception_date' => '2024-07-31',
            'assured' => 'OLIVIA T. FERNANDEZ',
            'policy_num' => 'MC-AAP-DV-23-0000901-01',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'EFG9753',
            'car_details' => '2021 HYUNDAI TUCSON GLS SUV',
            'policy_status' => 'new',
            'financing_bank' => 'LANDBANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
        // Random 20
        $grossPremium = 16543;
        $totalPayment = 15000;
        $paymentBalance = $grossPremium - $totalPayment;
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'report_cost_center_id' => 18,
            'report_insurance_prod_id' => rand(1, 5),
            'report_insurance_type_id' => rand(1, 9),
            'report_payment_mode_id' => rand(1, 7),
            'sales_person_id'=> rand(22, 45),
            'arpr_num' => 'PR123456',
            'arpr_date' => '2024-07-26',
            'inception_date' => '2024-07-26',
            'assured' => 'BENJAMIN C. CRUZ',
            'policy_num' => 'MC-AAP-DV-23-0001012-03',
            'terms' => 'STRAIGHT',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'plate_num' => 'HIJ8642',
            'car_details' => '2023 MITSUBISHI XPANDER CROSS MPV',
            'policy_status' => 'renewal',
            'financing_bank' => 'EASTWEST BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
    }   

    
    
}
