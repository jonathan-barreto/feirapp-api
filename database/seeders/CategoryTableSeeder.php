<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = [
            [
                'category' => 'Todos',
                'image' => null,
            ],
            [
                'category' => 'Fruta',
                'image' => 'morango.png',
            ],
            [
                'category' => 'Vegetal',
                'image' => 'couve-flor.png',
            ],
            [
                'category' => 'Verdura',
                'image' => 'alface.png',
            ],
            [
                'category' => 'Tempero',
                'image' => 'alface.png',
            ],
        ];


        DB::table('categories')->insert($categories);
    }
}
