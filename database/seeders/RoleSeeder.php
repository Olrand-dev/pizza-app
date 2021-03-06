<?php

namespace Database\Seeders;

use App\Consts\SystemConst;
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
        $rolesData = SystemConst::USER_ROLES_MAP;

        foreach ($rolesData as $id => $data) {
            if ($id === 0) continue;
            DB::table('roles')->insert($data);
        }
    }
}
