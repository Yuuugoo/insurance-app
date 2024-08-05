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

        User::create([
            'name' => 'Ethan Chen',
            'username' => 'CSH_EC',
            'email' => 'cashier@admin.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),

        ])->assignRole($cashierRole);

        User::create([
            'name' => 'Michelle Lee',
            'username' => 'ACC_ML',
            'email' => 'acctstaff@admin.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),

        ])->assignRole($acctStaffRole);

        User::create([
            'name' => 'Sarah Johnson',
            'username' => 'ACCM_Johnson',
            'email' => 'acctmanager@admin.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),

        ])->assignRole($acctManagerRole);

        User::create([
            'name' => 'Super Admin',
            'username' => 'SADM_Admin',
            'email' => 'superadmin@admin.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),

        ])->assignRole($superAdminRole);

        User::create([
            'name' => 'Mark Daniel',
            'username' => 'AGN_MD',
            'email' => 'agentview@user.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),

        ])->assignRole($agentRole);

        
        User::create([
            'name' => 'John Lawrence',
            'username' => 'CFO_JL',
            'email' => 'agentview@user.com',
            'email_verified_at' => now(),
            'password' => hash('sha512', 'password'),
            'remember_token' => Str::random(10),

        ])->assignRole($cfoRole);

        



    }
}
