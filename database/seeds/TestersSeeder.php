<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Cities\City;

class TestersSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['first_name' => "Srinivasarao", 'phone' => '9003185306', 'role' => 'CONSUMER'],
            ['first_name' => "Manoj", 'phone' => '8883102253', 'role' => 'CONSUMER'],
            ['first_name' => "Vinotha", 'phone' => '9894865249', 'role' => 'CONSUMER'],
            ['first_name' => "KK", 'phone' => '9513513513', 'role' => 'DSA_MEMBER'],
            ['first_name' => "Rajdeep", 'phone' => '8050011340', 'role' => 'CONSUMER'],
            ['first_name' => "Sreenadh", 'phone' => '8281170840', 'role' => 'DSA_OWNER','designation_id' => 1],
            ['first_name' => "Sachin", 'phone' => '9844870970', 'role' => 'DSA_OWNER'],
            ['first_name' => "Deepika Padukone", 'phone' => '8892348234', 'role' => 'DSA_OWNER'],
            ['first_name' => "Vinodh", 'phone' => '9493525217', 'role' => 'DSA_OWNER'],
            ['first_name' => "Pampans", 'phone' => '9972411199', 'role' => 'CONSUMER'],
            ['first_name' => "Tester1", 'phone' => '9972711881', 'role' => 'CONSUMER'],
            ['first_name' => "Tester2", 'phone' => '9448314257', 'role' => 'CONSUMER'],
            ['first_name' => "Tester3", 'phone' => '9164318165', 'role' => 'DSA_OWNER'],
            ['first_name' => "Tester4", 'phone' => '9986360847', 'role' => 'DSA_MEMBER'],
            ['first_name' => "Dhanasekaran", 'phone' => '8050030506', 'role' => 'DSA_OWNER'],
            ['first_name' => "Tester5", 'phone' => '8553884053', 'role' => 'DSA_OWNER'],
            ['first_name' => "Tester6", 'phone' => '9663114008', 'role' => 'DSA_MEMBER'],
            ['first_name' => "Tester7", 'phone' => '9448314021', 'role' => 'DSA_MEMBER'],
        ];


        $faker = \Faker\Factory::create();
        $city =  City::take(1)->first();

        $cities = City::take(50)->first();

        $address = factory(Address::class)->make([
            'city_id' => $city->id,
        ]);

        $banks = factory(Bank::class, 5)->create();
        $bankids = $banks->lists('id')->all();

        foreach($banks as $bank) {
            $bank->addresses()->save($address);
        }


        foreach ($users as $userD) {
            $user = factory(User::class)->create($userD);

            $address = factory(Address::class)->make([
                'city_id'          => $city->id,
            ]);
            $user->addresses()->save($address);

            //Attach a bank to the user
            $user->banks()->save($banks->random());
            $team = factory(Team::class)->create();

            if ($userD['role'] == 'DSA_MEMBER') {
                $team->members()->attach($user->id, ['is_owner' => false,'target' => $faker->numberBetween($min = 0, $max = 15),'achieved' => $faker->numberBetween($min = 0, $max = 15), 'incentive_plan' => $faker->numberBetween($min = 10000, $max = 900000) ,'incentive_earned' => $faker->numberBetween($min = 10000, $max = 900000)]);
                //Adding an owner to the team
                $owner = factory(User::class)->create(['role' => 'DSA_OWNER']);

                $address = factory(Address::class)->make([
                    'city_id'          => $city->id,
                ]);
                $owner->addresses()->save($address);


                $team->members()->attach($owner->id, ['is_owner' => true,'target' => $faker->numberBetween($min = 0, $max = 15),'achieved' => $faker->numberBetween($min = 0, $max = 15), 'incentive_plan' => $faker->numberBetween($min = 10000, $max = 900000) ,'incentive_earned' => $faker->numberBetween($min = 10000, $max = 900000)]);

            } else {
                $team->members()->attach($user->id, [ 'is_owner' => true,'target' => $faker->numberBetween($min = 0, $max = 15),'achieved' => $faker->numberBetween($min = 0, $max = 15), 'incentive_plan' => $faker->numberBetween($min = 10000, $max = 900000) ,'incentive_earned' => $faker->numberBetween($min = 10000, $max = 900000)]);
            }


            //Adding two more members to the team
            foreach (range(0, 4) as $range) {
                $m = factory(User::class)->create(['role' => 'DSA_MEMBER']);
                //Attach bank to the user
                $b = Bank::find($bankids[$range]);
                $m->banks()->save($b);

                $address = factory(Address::class)->make([
                    'city_id'          => $city->id,
                ]);
                $m->addresses()->save($address);

                $team->members()->attach($m->id, ['is_owner' => false,'target' => $faker->numberBetween($min = 0, $max = 15),'achieved' => $faker->numberBetween($min = 0, $max = 15), 'incentive_plan' => $faker->numberBetween($min = 10000, $max = 900000) ,'incentive_earned' => $faker->numberBetween($min = 10000, $max = 900000)]);
            }

        }
    }



    /**
     * @param $id
     * @param $phone
     * @param $role
     * @return mixed
     */
    public function user($id, $phone, $role)
    {
        $user = User::find($id);
        $user->phone = $phone;
        $user->role = $role;
        $user->save();

        return $user;
    }

}
