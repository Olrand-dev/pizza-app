<?php

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
        DB::table('product_types')->insert([
            ['name' => 'основа пиццы', 'description' => 'круглая основа для пиццы'],
            ['name' => 'соус', 'description' => 'разнообразные соусы для пиццы'],
            ['name' => 'сыр', 'description' => 'сыр для пиццы'],
            ['name' => 'ингредиенты', 'description' => 'доп. ингредиенты для пиццы'],
            ['name' => 'доп. товары', 'description' => 'доп. товары для заказа'],
        ]);
    }
}
