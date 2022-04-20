<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsSeeder::class);
        $this->call(PermissionsSeeder::class);

        if (Module::has('Learning')) {
            $this->call(\Modules\Learning\Database\Seeders\PermissionsTableSeeder::class);
        }

        if (Module::has('Api')) {
            $this->call(\Modules\Api\Database\Seeders\PermissionsTableSeeder::class);
        }

        if (Module::has('Pages')) {
            $this->call(\Modules\Pages\Database\Seeders\PermissionsTableSeeder::class);
        }
    }
}
