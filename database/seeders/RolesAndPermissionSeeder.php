<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions(); // reset cached roles and permissions
    
        // generate permissions
        $permission1 = Permission::create(['name' => 'create: report']);
        $permission2 = Permission::create(['name' => 'update: report']);
        $permission3 = Permission::create(['name' => 'delete: report']);

        $permission4 = Permission::create(['name' => 'create: policy']);
        $permission5 = Permission::create(['name' => 'update: policy']);
        $permission6 = Permission::create(['name' => 'delete: policy']);

        $permission7 = Permission::create(['name' => 'create: deposit']);
        $permission8 = Permission::create(['name' => 'update: deposit']);
        $permission9 = Permission::create(['name' => 'delete: deposit']);
        $permission10 = Permission::create(['name' => 'read: report']);

        $permissions = [
            ['name' => 'create: cashier'],
            ['name' => 'read: cashier'],
            ['name' => 'update: cashier'],
            ['name' => 'delete: cashier'],
            
            ['name' => 'create: acct-staff'],
            ['name' => 'read: acct-staff'],
            ['name' => 'update: acct-staff'],
            ['name' => 'delete: acct-staff'],
            
            ['name' => 'create: acct-manager'],
            ['name' => 'read: acct-manager'],
            ['name' => 'update: acct-manager'],
            ['name' => 'delete: acct-manager'],
        ];
        
        // Then you can create all permissions at once:
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $cashierRole = Role::create(['name' => 'cashier'])->syncPermissions([
            $permission1, $permission2, $permission3, $permission4, $permission5, $permission6,
        ]);

        $acctStaffRole = Role::create(['name' => 'acct-staff'])->syncPermissions([
            $permission7, $permission8, $permission9
        ]);

        $acctManagerRole = Role::create(['name' => 'acct-manager'])->syncPermissions([
            $permission10
        ]);

        $superAdminRole = Role::create(['name' => 'super-admin'])->syncPermissions([
            $permissions
        ]);

        $agentRole = Role::create(['name' => 'agent']);
        $cfoRole = Role::create(['name' => 'cfo']);

        $createAccountPermission = Permission::create(['name' => 'create: account']);

        $superAdminRole->givePermissionTo($createAccountPermission);
        ////////////// CASHIER ROLES ////////////////////
        User::create([
            'name' => 'Ethan Chen',
            'branch_id' => 1,
            'username' => 'CSH_EC',
            'email' => 'ethan@cshaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);

        User::create([
            'name' => 'Christhia Anne  Arceo',
            'branch_id' => 1,
            'username' => 'CSH_CAA',
            'email' => 'chrarceo@cshaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Emma Thompson',
            'branch_id' => 2,
            'username' => 'CSH_ET',
            'email' => 'emma@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'James Harrison',
            'branch_id' => 3,
            'username' => 'CSH_JH',
            'email' => 'james@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Sophia Patel',
            'branch_id' => 4,
            'username' => 'CSH_SP',
            'email' => 'sophia@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Liam Roberts',
            'branch_id' => 5,
            'username' => 'CSH_LR',
            'email' => 'liam@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Olivia Martinez',
            'branch_id' => 6,
            'username' => 'CSH_OM',
            'email' => 'olivia@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Noah King',
            'branch_id' => 7,
            'username' => 'CSH_NK',
            'email' => 'noah@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Ava Davis',
            'branch_id' => 8,
            'username' => 'CSH_AD',
            'email' => 'ava@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'William Scott',
            'branch_id' => 9,
            'username' => 'CSH_WS',
            'email' => 'william@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Isabella Nguyen',
            'branch_id' => 10,
            'username' => 'CSH_IN',
            'email' => 'isabella@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Benjamin Hall',
            'branch_id' => 11,
            'username' => 'CSH_BH',
            'email' => 'benjamin@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Mia Lee',
            'branch_id' => 12,
            'username' => 'CSH_ML',
            'email' => 'mia@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Lucas Green',
            'branch_id' => 13,
            'username' => 'CSH_LG',
            'email' => 'lucas@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Amelia Adams',
            'branch_id' => 14,
            'username' => 'CSH_AA',
            'email' => 'amelia@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Henry Perez',
            'branch_id' => 15,
            'username' => 'CSH_HP',
            'email' => 'henry@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Evelyn Johnson',
            'branch_id' => 16,
            'username' => 'CSH_EJ',
            'email' => 'evelyn@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Alexander Ramirez',
            'branch_id' => 17,
            'username' => 'CSH_AR',
            'email' => 'alexander@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);
        
        User::create([
            'name' => 'Charlotte Campbell',
            'branch_id' => 18,
            'username' => 'CSH_CC',
            'email' => 'charlotte@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);

        User::create([
            'name' => 'Charlotte BellCamp',
            'branch_id' => 19,
            'username' => 'CSH_CB',
            'email' => 'charlottebel@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);

        User::create([
            'name' => 'Johhny Depp',
            'branch_id' => 20,
            'username' => 'CSH_JDP',
            'email' => 'johnnydepp@cshapp.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($cashierRole);

        ////////////// AGENT ROLES ////////////////////
        User::create([
            'name' => 'Sharon Montojo',
            'branch_id' => 1,
            'username' => 'AGN_SM',
            'email' => 'shamontojo@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);

        User::create([
            'name' => 'Mercedita Forlanda',
            'branch_id' => 1,
            'username' => 'AGN_MF',
            'email' => 'merforlanda@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);

        User::create([
            'name' => 'Joan Zapata',
            'branch_id' => 1,
            'username' => 'AGN_JZ',
            'email' => 'joazapata@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);

        User::create([
            'name' => 'Mary Jane Sanchez',
            'branch_id' => 1,
            'username' => 'AGN_MJS',
            'email' => 'marsanchez@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);

        User::create([
            'name' => 'Bernard Herminigildo',
            'branch_id' => 1,
            'username' => 'AGN_BH',
            'email' => 'berherminigildo@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Mark Daniel',
            'branch_id' => 2,
            'username' => 'AGN_MD',
            'email' => 'markdaniel@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Michael Brown',
            'branch_id' => 3,
            'username' => 'AGN_MB',
            'email' => 'michaelbrown@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Sarah Davis',
            'branch_id' => 4,
            'username' => 'AGN_SD',
            'email' => 'sarahdavis@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'David Wilson',
            'branch_id' => 5,
            'username' => 'AGN_DW',
            'email' => 'davidwilson@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Laura Martinez',
            'branch_id' => 6,
            'username' => 'AGN_LM',
            'email' => 'lauramartinez@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Daniel Anderson',
            'branch_id' => 7,
            'username' => 'AGN_DA',
            'email' => 'danielanderson@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Jessica Taylor',
            'branch_id' => 8,
            'username' => 'AGN_JT',
            'email' => 'jessicataylor@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'James Moore',
            'branch_id' => 9,
            'username' => 'AGN_JM',
            'email' => 'jamesmoore@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Amy Thompson',
            'branch_id' => 10,
            'username' => 'AGN_AT',
            'email' => 'amythompson@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Brian White',
            'branch_id' => 11,
            'username' => 'AGN_BW',
            'email' => 'brianwhite@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Megan Garcia',
            'branch_id' => 12,
            'username' => 'AGN_MG',
            'email' => 'megangarcia@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Robert Martinez',
            'branch_id' => 13,
            'username' => 'AGN_RM',
            'email' => 'robertmartinez@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Elizabeth Hernandez',
            'branch_id' => 14,
            'username' => 'AGN_EH',
            'email' => 'elizabethhernandez@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Kevin Clark',
            'branch_id' => 15,
            'username' => 'AGN_KC',
            'email' => 'kevinclark@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Linda Lewis',
            'branch_id' => 16,
            'username' => 'AGN_LL',
            'email' => 'lindalewis@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Steven Walker',
            'branch_id' => 17,
            'username' => 'AGN_SW',
            'email' => 'stevenwalker@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);
        
        User::create([
            'name' => 'Anna Young',
            'branch_id' => 18,
            'username' => 'AGN_AY',
            'email' => 'annayoung@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);

        User::create([
            'name' => 'Ava Brown',
            'branch_id' => 19,
            'username' => 'AGN_AB',
            'email' => 'avabrown@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);

        User::create([
            'name' => 'Ava Blue',
            'branch_id' => 20,
            'username' => 'AGN_ABL',
            'email' => 'avablue@agnaap.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'
        ])->assignRole($agentRole);

        ////////////// OTHER ROLES ////////////////////
        User::create([
            'name' => 'Michelle Lee',
            'username' => 'ACC_ML',
            'email' => 'acctstaff@admin.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/teen.png'

        ])->assignRole($acctStaffRole);

        User::create([
            'name' => 'Sarah Johnson',
            'username' => 'ACCM_Johnson',
            'email' => 'acctmanager@admin.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/woman.png'

        ])->assignRole($acctManagerRole);

        User::create([
            'name' => 'Super Admin',
            'username' => 'SADM_Admin',
            'email' => 'superadmin@admin.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/boy.png'

        ])->assignRole($superAdminRole);

        User::create([
            'name' => 'John Lawrence',
            'username' => 'CFO_JL',
            'email' => 'cfo@user.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),
            'avatar_url' => '/storage/default_avatar/panda.png'

        ])->assignRole($cfoRole);

    }
}
