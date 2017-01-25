<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Calendars\Calendar;
use Whatsloan\Repositories\Teams\Team;

class CalendarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = Team::all();

        $teams->each(function($team) {
           factory(Calendar::class, 19)->create([
               'team_id' => $team->id
           ]);
        });
    }
}
