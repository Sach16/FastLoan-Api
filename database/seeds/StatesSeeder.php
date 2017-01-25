<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\States\State;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(State::class, 10)->create();
    }
}
