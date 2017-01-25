<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Loans\Loan;

class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $faker = \Faker\Factory::create();
         //$users = User::take(20)->get();
         $sources = Source::take(20)->get();
         $cities = City::take(20)->get();
         $loans = Loan::take(50)->get();
         $leadUsers = factory(User::class, 20)->create(['role' => 'LEAD']);
         $users = User::where('role','DSA_MEMBER')->get();
        
         foreach(range(0,20) as $i) {
             $lead = factory(Lead::class)->create([
                'loan_id'     => $faker->randomElement($loans->lists('id')->all()),
                'source_id'   => $faker->randomElement($sources->lists('id')->all()),
                'assigned_to'   => $faker->randomElement($users->lists('id')->all()),
                'user_id'     => $faker->randomElement($leadUsers->lists('id')->all()),
            ]);
             
             
            //  factory(Address::class)->create([
            //     'addressable_type' => get_class($lead),
            //     'addressable_id'   => $lead->id,
            //     'city_id'          => $faker->randomElement($cities->lists('id')->all()),
            // ]);
         }
         
            //Set User address
            foreach ($leadUsers->lists('id')->all() as $userid) {
                factory(Address::class)->create([
                'addressable_type' => get_class($leadUsers->first()),
                'addressable_id'   => $userid,
                'city_id'          => $faker->randomElement($cities->lists('id')->all()),
                ]);
            }
         

    }
}
