<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // إنشاء الصلاحيات
        $permissions = [
            'create attachment',
            'show attachment',
            'update attachment',
            'destroy attachment',
            'get all tenants' ,
            'create tenant',
            'show tenant',
            'update tenant',
            'destroy tenant',
            'assigne user to tenant',
            'get all task' ,
            'create task',
            'show task',
            'update task',
            'destroy task',
            'assigne user to task',
            'get all role of tenant' ,
            'create role',
            'show role',
            'update role',
            'destroy role',
            'assigne user to role',
            'remove user to role',
            
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        Tenant::create(['name' => 'public']);

        // إنشاء الأدوار وربط الصلاحيات بها
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'tenant_id' => 1 ,
        ]);
        $adminRole->givePermissionTo($permissions);

        $user = User::create([
            'name' => 'hossam' ,
            'email' => 'hossam@gmail.com' ,
            'password' => bcrypt('123456'),
        
        ]);

        $user->assignRole('admin');

       
}
}