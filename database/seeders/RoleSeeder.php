<?php

namespace Database\Seeders;

use App\PermissionsEnum;
use App\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PgSql\Result;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::create([
            'name' => RolesEnum::USER->value,
        ]);
        $vendorRole = Role::create([
            'name' => RolesEnum::VENDOR->value,
        ]);
        $adminRole = Role::create([
            'name' => RolesEnum::ADMIN->value,
        ]);

        $approveVendors = Permission::create([
            'name' => PermissionsEnum::ApproveVendors->value
        ]);

        $sellProducts = Permission::create([
            'name' => PermissionsEnum::SellProducts->value
        ]);
        $buyProducts = Permission::create([
            'name' => PermissionsEnum::ByProducts->value
        ]);

        $userRole->syncPermissions([$buyProducts]);
        $vendorRole->syncPermissions([$sellProducts, $buyProducts]);
        $adminRole->syncPermissions([$approveVendors, $sellProducts, $buyProducts]);
    }
}
