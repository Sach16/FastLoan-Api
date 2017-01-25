<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Payouts\Payout;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Builders\Builder;

class PayoutsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $builders = Builder::take(20);
        $users = User::whereRole('REFERRAL')->take(20);
        
        foreach (range(0,20) as $i) {
            
            factory(Payout::class)->create([
                'builder_id' => $faker->randomElement($builders->lists('id')->all()),
                'user_id' => $faker->randomElement($users->lists('id')->all()),
            ]);

        }
    }

}
