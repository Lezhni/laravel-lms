<?php

namespace Modules\Pages\Database\Seeders;

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
            ['title' => 'Страницы: просмотр', 'name' => 'pages.list'],
            ['title' => 'Страницы: создание', 'name' => 'pages.create'],
            ['title' => 'Страницы: редактирование', 'name' => 'pages.update'],
            ['title' => 'Страницы: удаление', 'name' => 'pages.delete'],
        ];

        $permissions = array_map(function ($permission) use ($now) {
            return array_merge($permission, ['guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now]);
        }, $permissions);

        DB::table('permissions')->insertOrIgnore($permissions);
    }
}
