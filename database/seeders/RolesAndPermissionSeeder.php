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

        // generate cashier permission
        $cashierRole = Permission::create(['name' => 'create: cashier']);
        $cashierRole = Permission::create(['name' => 'read: cashier']);
        $cashierRole = Permission::create(['name' => 'update: cashier']);
        $cashierRole = Permission::create(['name' => 'delete: cashier']);

        // generate accounting staff permission
        $acctStaffRole = Permission::create(['name' => 'create: acctstaff']);
        $acctStaffRole = Permission::create(['name' => 'read: acctstaff']);
        $acctStaffRole = Permission::create(['name' => 'update: acctstaff']);
        $acctStaffRole = Permission::create(['name' => 'delete: acctstaff']);

        // generate accounting manager permission
        $acctManagerRole = Permission::create(['name' => 'create: acctmanager']);
        $acctManagerRole = Permission::create(['name' => 'read: acctmanager']);
        $acctManagerRole = Permission::create(['name' => 'update: acctmanager']);
        $acctManagerRole = Permission::create(['name' => 'delete: acctmanager']);

        $cashierRole = Role::create(['name' => 'cashier'])->syncPermissions([
            $permission1, $permission2, $permission3, $permission4, $permission5, $permission6
        ]);

        $acctStaffRole = Role::create(['name' => 'acctstaff'])->syncPermissions([
            $permission7, $permission8, $permission9
        ]);

        $acctManagerRole = Role::create(['name' => 'acctmanager'])->syncPermissions([
            $permission10, // 'read: report'
            $cashierRole,  // 'create: cashier'
            $acctStaffRole, // 'create: acctstaff'
            $acctManagerRole // 'create: acctmanager'
        ]);

        $createAccountPermission = Permission::create(['name' => 'create: account']);

        $acctManagerRole->givePermissionTo($createAccountPermission);

        User::create([
            'name' => 'cashier',
            'is_admin' => 1,
            'email' => 'cashier@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),

        ])->assignRole($cashierRole);

        User::create([
            'name' => 'acctstaff',
            'is_admin' => 1,
            'email' => 'acctstaff@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),

        ])->assignRole($acctStaffRole);

        User::create([
            'name' => 'acctmanager',
            'is_admin' => 1,
            'email' => 'acctmanager@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),

        ])->assignRole($acctManagerRole);



    }
}
