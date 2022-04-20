<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsTableSeeder
 * @package Modules\Learning\Database\Seeders
 */
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
            ['title' => 'Курсы и сопутствующие: просмотр', 'name' => 'courses.list'],
            ['title' => 'Курсы и сопутствующие: создание', 'name' => 'courses.create'],
            ['title' => 'Курсы и сопутствующие: редактирование', 'name' => 'courses.update'],
            ['title' => 'Курсы и сопутствующие: удаление', 'name' => 'courses.delete'],

            ['title' => 'Тесты: просмотр', 'name' => 'quizzes.list'],
            ['title' => 'Тесты: создание', 'name' => 'quizzes.create'],
            ['title' => 'Тесты: редактирование', 'name' => 'quizzes.update'],
            ['title' => 'Тесты: удаление', 'name' => 'quizzes.delete'],

            ['title' => 'Зачисления на курсы: просмотр', 'name' => 'enrollments.list'],
            ['title' => 'Зачисления на курсы: создание', 'name' => 'enrollments.create'],
            ['title' => 'Зачисления на курс: редактирование', 'name' => 'enrollments.update'],
            ['title' => 'Зачисления на курс: удаление', 'name' => 'enrollments.delete'],

            ['title' => 'Просмотр и оценка ДЗ учеников', 'name' => 'homeworks.grade'],
        ];

        $permissions = array_map(function ($permission) use ($now) {
            return array_merge($permission, ['guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now]);
        }, $permissions);

        DB::table('permissions')->insertOrIgnore($permissions);
    }
}
