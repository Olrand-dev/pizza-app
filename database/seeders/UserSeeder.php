<?php

namespace Database\Seeders;

use App\Consts\SystemConst;
use App\Http\Controllers\Controller;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersData = SystemConst::USERS_START_SEED;

        foreach ($usersData as $data) {
           Controller::createEmployee($data);
        }
    }
}
