<?php

namespace Database\Seeders;

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
        $user = User::where('name', 'Cashier')->first();
        
        Report::create([
            'submitted_by_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
            'cost_center' => 'aurora',
            'arpr_num' => 'PR0421842',
            'arpr_date' => '2024-06-17',
            'inception_date' => '2024-06-18',
            'assured' => 'REYNALDO A. AUTENTICO',
            'policy_num' => 'MC-AAP-DV-24-0000142-00',
            'insurance_prod' => 'oona',
            'insurance_type' => 'compre',
            'terms' => 'straight',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'paymaya',
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
            'cost_center' => 'davao',
            'arpr_num' => 'PR100008',
            'arpr_date' => '2024-06-03',
            'inception_date' => '2024-06-06',
            'assured' => 'GENE Q. SESCON',
            'policy_num' => 'MC-AAP-DV-24-0000102-00',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => '1/2',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'paymaya',
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
            'cost_center' => 'fairview',
            'arpr_num' => 'PR100013',
            'arpr_date' => '2024-06-05',
            'inception_date' => '2024-06-05',
            'assured' => 'EDILBERTO Q. LLENADO',
            'policy_num' => 'MC-AAP-DV-24-0000108-00',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => '1/2',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'paymaya',
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
            'cost_center' => 'fairview',
            'arpr_num' => 'PR100016',
            'arpr_date' => '2024-06-06',
            'inception_date' => '2024-06-06',
            'assured' => 'ALVIR ALEXIS C. SANCHEZ',
            'policy_num' => 'MC-AAP-DV-23-0000069-00',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => 'straight',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'cash',
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
            'cost_center' => 'fairview',
            'arpr_num' => 'PR100020',
            'arpr_date' => '2024-06-10',
            'inception_date' => '2024-06-10',
            'assured' => 'GIRELL APRIL P. LUMUMA',
            'policy_num' => 'MC-AAP-DV-24-0000110-00',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => 'straight',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'cash',
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
            'cost_center' => 'davao',
            'arpr_num' => 'PR100043',
            'arpr_date' => '2024-06-20',
            'inception_date' => '2024-06-20',
            'assured' => 'ERWIN C. IDEMNE',
            'policy_num' => 'MC-AAP-DV-23-0000074-01',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => 'straight',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'cash',
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
            'cost_center' => 'davao',
            'arpr_num' => 'PR100044',
            'arpr_date' => '2024-06-21',
            'inception_date' => '2024-06-20',
            'assured' => 'JAY FRANCESS T. MANUEL',
            'policy_num' => 'MC-AAP-DV-22-0000040-01',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => '1/3',
            'gross_premium' => $grossPremium,
            'total_payment' =>  $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'cash',
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
            'cost_center' => 'davao',
            'arpr_num' => 'PR100045',
            'arpr_date' => '2024-03-05',
            'inception_date' => '2024-06-21',
            'assured' => 'JERRY L. CRISPINO',
            'policy_num' => 'MC-AAP-DV-24-0000129-00',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => 'straight',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'cash',
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
            'cost_center' => 'davao',
            'arpr_num' => 'PR100046',
            'arpr_date' => '2024-06-22',
            'inception_date' => '2024-03-06',
            'assured' => 'AGNES V. DINNEEN',
            'policy_num' => 'MC-AAP-DV-23-0000039-00',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => 'straight',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'cash',
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
            'cost_center' => 'davao',
            'arpr_num' => 'PR100047',
            'arpr_date' => '2024-06-22',
            'inception_date' => '2024-06-09',
            'assured' => 'MADUNA SALARDA MAASIN',
            'policy_num' => 'MC-AAP-DV-23-0000067-00',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => 'straight',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'cash',
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
            'cost_center' => 'davao',
            'arpr_num' => 'PR100050',
            'arpr_date' => '2024-06-24',
            'inception_date' => '2024-07-12',
            'assured' => 'GLADYS LYN S. LAPUT',
            'policy_num' => 'MC-AAP-DV-23-0000082-00',
            'insurance_prod' => 'mct',
            'insurance_type' => 'compre',
            'terms' => 'straight',
            'gross_premium' => $grossPremium,
            'total_payment' => $totalPayment,
            'payment_balance' => $paymentBalance,
            'payment_mode' => 'cash',
            'plate_num' => 'LAK3365',
            'car_details' => '2023 MITSUBISHI XPANDER GLS 1.5 WAGON',
            'policy_status' => 'renewal',
            'financing_bank' => 'SECURITY BANK',
            'application' => 'dropby',
            'payment_status' => 'pending',
        ]);
    }   

    
}
