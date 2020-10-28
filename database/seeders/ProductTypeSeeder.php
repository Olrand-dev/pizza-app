<?php

namespace Database\Seeders;

use App\Consts\SystemConst;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typesData = SystemConst::PRODUCT_TYPES_MAP;

        foreach ($typesData as $id => $data) {
            DB::table('product_types')->insert($data);
        }
    }
}
