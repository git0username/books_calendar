<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'studentNo' => '1',
            'name' => 'AAAAAA',            
            'password' => bcrypt('1234'),
        ];
        DB::table('users')->insert($param);

        
        
        // $faker = \Faker\Factory::create('ja_JP');
        // for ($i = 0; $i < 10; $i++){
        //     $param = [
        //         'name' => $faker->name,
        //         'email' => $faker->email,
        //         'password' => $faker->password,
        //         'remember_token' => $faker->randomDigit,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ];
        //     DB::table('users')->insert($param);
        // }
    }
}
