<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\Campaigns\Campaign;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\Attendances\Attendance;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Products\Product;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\ProjectStatuses\ProjectStatus;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Queries\Query;
use Whatsloan\Repositories\Loans\Loan;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $this->call(DesignationsSeeder::class);
        $cities = factory(City::class, 20)->create();
        
        $users = factory(User::class, 20)->create()->each(function($user) {
            $attachments = factory(Attachment::class, 5)->make();
            $user->attachments()->saveMany($attachments);
        });

        $banks = factory(Bank::class, 50)->create()->each(function($bank) {
            $attachments = factory(Attachment::class, 5)->make();
            $bank->attachments()->saveMany($attachments);
        });

        //Set User address
        foreach ($users->lists('id')->all() as $userid) {
            factory(Address::class)->create([
            'addressable_type' => get_class($users->first()),
            'addressable_id'   => $userid,
            'city_id'          => $faker->randomElement($cities->lists('id')->all()),
            ]);
        }
        
        
        //Set bank address
        foreach ($banks->lists('id')->all() as $bankid) {
            factory(Address::class)->create([
            'addressable_type' => get_class($banks->first()),
            'addressable_id'   => $bankid,
            'city_id'          => $faker->randomElement($cities->lists('id')->all()),
            ]);
        }

        $builders = factory(Builder::class,10)->create();
        $builderIds = $builders->lists('id')->all();

        foreach($builderIds as $id) {
            factory(Address::class)->create([
                'addressable_type' => Builder::class,
                'addressable_id'   => $id,
                'city_id'          => $faker->randomElement($cities->lists('id')->all()),
            ]);
        }


        foreach (range(1, 3) as $teamCount) {
            $owner = factory(User::class)->create(['role' => 'DSA_OWNER']);
            $owner->banks()->attach($faker->randomElement($banks->lists('id')->all()));

            $this->seedUserAttendance($owner);

            $team = factory(Team::class)->create();
            $team->members()->attach($owner->id, ['is_owner' => true,'target' => $faker->numberBetween($min = 0, $max = 15),'achieved' => $faker->numberBetween($min = 0, $max = 15), 'incentive_plan' => $faker->numberBetween($min = 10000, $max = 900000) ,'incentive_earned' => $faker->numberBetween($min = 10000, $max = 900000),]);



            foreach (range(1, 5) as $memberCount) {

                $member = factory(User::class)->create(['role' => 'DSA_MEMBER']);
                $member->banks()->attach($faker->randomElement($banks->lists('id')->all()));

                $memberIds[] = $member->id;
                $this->seedUserAttendance($member);
                $team->members()->attach($member->id, ['is_owner' => false,'target' => $faker->numberBetween($min = 0, $max = 15),'achieved' => $faker->numberBetween($min = 0, $max = 15), 'incentive_plan' => $faker->numberBetween($min = 10000, $max = 900000) ,'incentive_earned' => $faker->numberBetween($min = 10000, $max = 900000),]);

            }
            $referral_users = User::where('role','REFERRAL')->lists('id')->all();
            $team->referrals()->attach($referral_users);
        }

        $customers = factory(User::class, 20)->create([
            'role' => 'CONSUMER'
        ]);

        $this->call(SourcesSeeder::class);
        $this->call(TypesSeeder::class);
        $this->call(ProjectsSeeder::class);
        $this->call(LoanStatusesSeeder::class);
        $this->call(LoansSeeder::class);
        $this->call(LeadsSeeder::class);

        $this->call(ProductsSeeder::class);
        $this->call(TasksSeeder::class);
        $this->call(CampaignsSeeder::class);
        $this->call(QueriesSeeder::class);
        //$this->call(PayoutsSeeder::class);
        $this->call(CalendarsSeeder::class);
        $this->call(TestersSeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(LocalitiesSeeder::class);

    }


    /**
     * [seedUserAttendance description]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function seedUserAttendance($user)
    {

        factory(Attendance::class, 20)->create([
            'user_id' => $user->id
        ]);
    }
}
