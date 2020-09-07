<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        $models = [
            'role', 'user', 'captain',
            'category', 'address', 'social', 'phone',
            'email', 'product', 'offer',
            'coupon', 'feature', 'screenshot',
            'city', 'governorate', 'salon', 'branch',
            'service', 'course', 'lesson'
        ];

        foreach($models as $model) {
            Permission::create(['name' => $model . '.create']);
            if($model != 'coupon')
                Permission::create(['name' => $model . '.edit']);
            Permission::create(['name' => $model . '.view']);
            Permission::create(['name' => $model . '.delete']);
            Permission::create(['name' => $model . '.list']);
        }
        Permission::create(['name' => 'translator']);
        Permission::create(['name' => 'setting.view']);
        Permission::create(['name' => 'setting.edit']);

        $orders = [
            'salon_order', 'captain_order'
        ];
        foreach($orders as $order) {
            Permission::create(['name' => $order . '.status']);
            Permission::create(['name' => $order . '.view']);
            Permission::create(['name' => $order . '.delete']);
            Permission::create(['name' => $order . '.list']);
        }

        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->syncPermissions(Permission::all());

        $user = Factory(App\User::class)->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'phone' => '01010101010',
        ]);
        $user->assignRole($admin_role);

        Role::create(['name' => 'captain']);
        Role::create(['name' => 'salon']);
    }
}
