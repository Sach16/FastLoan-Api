<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Localities\Locality;
use Whatsloan\Repositories\States\State;

class LocalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$states = State::get();
    	foreach ($states as $state) {
        	factory(Locality::class)->create([
        		'state_id' => $state->id
        		]);
    	}
    }
}
