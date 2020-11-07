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
        //Will create without making the relationships
        
        \App\Models\User::factory(1)->create();
        \App\Models\Customer::factory(10)->create();

        //Create a random amount of numbers between 1 and 30
        \App\Models\Number::factory(rand(1,30))->create();
    }
}
