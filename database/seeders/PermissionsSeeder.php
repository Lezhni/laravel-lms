<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class PermissionsSeeder
 * @package Database\Seeders
 */
class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $permissions = [
            ['title' => 'Пользователи: просмотр', 'name' => 'users.list'],
            ['title' => 'Пользователи: создание', 'name' => 'users.create'],
            ['title' => 'Пользователи: редактирование', 'name' => 'users.update'],
            ['title' => 'Пользователи: удаление', 'name' => 'users.delete'],

            ['title' => 'Роли и права: просмотр', 'name' => 'roles.list'],
            ['title' => 'Роли и права: создание', 'name' => 'roles.create'],
            ['title' => 'Роли и права: редактирование', 'name' => 'roles.update'],
            ['title' => 'Роли и права: удаление', 'name' => 'roles.delete'],
        ];

        try {
            DB::transaction(function () use ($permissions, $now) {
                foreach ($permissions as $permission) {
                    $permission = array_merge($permission, [
                        'created_at' => $now,
                        'updated_at' => $now,
                        'guard_name' => 'web',
                    ]);
                    DB::table('permissions')->insert($permission);
                }
            });
        } catch (Throwable $e) {
            dump('Не удалось добавить разрешения в БД: ' . $e->getMessage());
        }

        $admin = User::admins()->orderBy('id', 'asc')->first();
        if (! $admin instanceof User) {
            return;
        }

        foreach ($permissions as $permission) {
            $admin->givePermissionTo($permission['name']);
        }
    }
}
