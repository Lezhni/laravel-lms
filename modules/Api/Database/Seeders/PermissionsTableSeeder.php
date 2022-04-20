<?php

namespace Modules\Api\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $now = Carbon::now();

        $permissions = [
            ['title' => 'API: просмотр', 'name' => 'api.list'],
            ['title' => 'API: создание ключей доступа', 'name' => 'api.create'],
            ['title' => 'API: редактирование ключей доступа', 'name' => 'api.update'],
            ['title' => 'API: удаление ключей доступа', 'name' => 'api.delete'],
        ];

        $permissions = array_map(function ($permission) use ($now) {
            return array_merge($permission, ['guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now]);
        }, $permissions);

        DB::table('permissions')->insertOrIgnore($permissions);
    }
}
