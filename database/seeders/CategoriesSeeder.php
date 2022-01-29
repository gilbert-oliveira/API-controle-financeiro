<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $expenses_categories = [
            'Alimentação',
            'Saúde',
            'Moradia',
            'Transporte',
            'Educação',
            'Lazer',
            'Imprevistos',
            'Outras',
        ];

        foreach ($expenses_categories as $category) {
            DB::table('categories')->insert([
               'name' =>  $category
            ]);
        }
    }
}
