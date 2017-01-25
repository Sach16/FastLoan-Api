<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Queries\Query;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Types\Type;

class QueriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::take(50)->get();
        $members = User::whereRole('DSA_MEMBER')->take(50)->get();

        foreach (range(1, 10) as $range) {
            factory(Query::class, rand(1, 3))->create([
                'project_id'  => $projects->random()->id,
                'assigned_to' => $members->random()->id,
            ]);
        }
    }
}
