<?php

namespace Database\Seeders;

use App\Consts\SystemConst;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusesData = SystemConst::ORDER_STATUSES_MAP;

        foreach ($statusesData as $id => $data) {
            if ($id === 0) continue;
            DB::table('order_statuses')->insert($data);
        }
    }
}
