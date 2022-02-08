<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(20)->create(); //userテーブルのseeder factory
        $this->call(BooksTableSeeder::class);
        $this->call(BookOnloansTableSeeder::class);
        $this->call(BooklistsTableSeeder::class);
        $this->call(UsersTableSeeder::class);

    }
}
