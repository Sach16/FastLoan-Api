<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\ProjectStatuses\ProjectStatus;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Units\Unit;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $status = $this->statuses();
        foreach (range(1, 10) as $range) {
            factory(Project::class, rand(1, 4))->create([
                'owner_id'    => factory(User::class)->create(['role' => 'DSA_OWNER'])->id,
                'status_id'   => $faker->randomElement($status->toArray()),
                'builder_id'  => $faker->randomElement(Builder::take(50)->lists('id')->all()),
            ])->each(function($project) use ($faker) {
                $pivot = [];
                foreach (range(1, 3) as $number) {
                    $pivot[] = [
                        'agent_id' => factory(User::class)->create(['role' => 'DSA_MEMBER'])->id,
                        'status' => $faker->randomElement(['APPROVED', 'REJECTED', 'PENDING']),
                        'approved_date' => date('Y-m-d H:i:s'),
                    ];
                }
                $project->banks()->saveMany(Bank::take(count($pivot))->get(), $pivot);
                $project->addresses()->save(factory(Address::class)->make([
                    'city_id' => factory(City::class)->create()->id,
                ]));
                
                $project->units()->save(factory(Unit::class)->make([
                    'project_id' => $project->id,
                ]));
            });
        }
    }

    public function statuses()
    {
        factory(ProjectStatus::class)->create([
            'status' => 'UNDER_CONSTRUCTION',
            'label'  => 'Under Construction',
        ]);
        factory(ProjectStatus::class)->create([
            'status' => 'COMPLETED',
            'label'  => 'Completed',
        ]);

        return ProjectStatus::lists('id');
    }
}
