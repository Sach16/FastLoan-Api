<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Campaigns\Campaign;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Users\User;

class CampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $cities = factory(City::class, 20)->create();
        $team = factory(Team::class)->create();

        foreach (range(1, 10) as $range) {
            factory(Campaign::class, rand(1, 3))->create([
                'team_id' => $team->id,
            ])->each(function($campaign)  use($faker, $cities, $team) {
                $campaign->members()->saveMany(factory(User::class, 5)->make())->each(function($member) use ($faker,$team){
                    $team->members()->attach($member->id, ['is_owner' => false,'target' => $faker->numberBetween($min = 0, $max = 15),'achieved' => $faker->numberBetween($min = 0, $max = 15), 'incentive_plan' => $faker->numberBetween($min = 10000, $max = 900000) ,'incentive_earned' => $faker->numberBetween($min = 10000, $max = 900000)]);
                });
                $campaign->addresses()->save(factory(Address::class)->make([
                    'city_id' => $faker->randomElement($cities->lists('id')->all()),
                ]));
            });
        }
    }
}
