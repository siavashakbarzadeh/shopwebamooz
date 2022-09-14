<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Jokoli\Category\Database\Seeders\CategoryTableSeeder;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Database\Seeders\CourseTableSeeder;
use Jokoli\Course\Database\Seeders\SeasonTableSeeder;
use Jokoli\Permission\Database\Seeders\PermissionTableSeeder;
use Jokoli\Permission\Database\Seeders\RoleTableSeeder;
use Jokoli\User\Database\Seeders\UserTableSeeder;
use Jokoli\User\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
//        $this->call(UserTableSeeder::class);
//        $this->call(CategoryTableSeeder::class);
//        $this->call(CourseTableSeeder::class);
//        $this->call(SeasonTableSeeder::class);
        Schema::enableForeignKeyConstraints();
    }
}
