<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'title' => '坊ちゃん',
            'booktypeId' => 1,
            // 'number' => 2,
            'price' => '500'
        ];
        DB::table('books')->insert($param);

        $param = [
            'title' => '坊ちゃん',
            'booktypeId' => 1,
            // 'number' => 2,
            'price' => '500'
        ];
        DB::table('books')->insert($param);

        $param = [
            'title' => '坊ちゃん',
            'booktypeId' => 1,
            // 'number' => 2,
            'price' => '500'
        ];
        DB::table('books')->insert($param);
    
        $param = [
            'title' => 'こころ',
            'booktypeId' => 2,
            // 'number' => 3,
            'price' => '600'
        ];
        DB::table('books')->insert($param);

        $param = [
            'title' => 'こころ',
            'booktypeId' => 2,
            // 'number' => 3,
            'price' => '600'
        ];
        DB::table('books')->insert($param);

        $param = [
           'title' => '三四郎',
           'booktypeId' => 3,
        //    'number' => 5,
            'price' => '450'
        ];
        DB::table('books')->insert($param);
    }
}
