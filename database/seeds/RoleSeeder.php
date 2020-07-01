<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'администратор пиццерии', 'slug' => 'admin', 'salary' => 25000],
            ['name' => 'менеджер', 'slug' => 'manager', 'salary' => 22000],
            ['name' => 'повар', 'slug' => 'cook', 'salary' => 12000],
            ['name' => 'старший повар', 'slug' => 'chef', 'salary' => 15000],
            ['name' => 'курьер', 'slug' => 'courier', 'salary' => 12000],
        ]);
    }
}
