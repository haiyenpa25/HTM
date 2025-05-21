<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DefaultUserSeeder extends Seeder
{
    public function run()
    {
        // Tạo hoặc cập nhật vai trò admin
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'admin', 'guard_name' => 'web']
        );

        // Tạo các quyền nếu chưa tồn tại
        $permissions = [
            'xem_thanh_vien',
            'them_thanh_vien',
            'sua_thanh_vien',
            'xoa_thanh_vien',
            'diem_danh',
            'tham_vieng',
            'to_chuc_su_kien',
            'quan_ly_bao_cao',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Gán tất cả quyền cho vai trò admin
        $adminRole->syncPermissions($permissions);

        // Tạo user mặc định
        $adminUser = User::updateOrCreate(
            ['email' => 'admin'],
            [
                'name' => 'Admin',
                'email' => 'admin',
                'password' => bcrypt('admin'),
            ]
        );

        // Gán vai trò admin cho user
        $adminUser->assignRole('admin');
    }
}
