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
            'price' => '500'
        ];
        DB::table('books')->insert($param);
    
        $param = [
            'title' => 'こころ',
            'price' => '600'
        ];
        DB::table('books')->insert($param);

        $param = [
           'title' => '三四郎',
            'price' => '450'
        ];
        DB::table('books')->insert($param);
    }
}
