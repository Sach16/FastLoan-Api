<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Designations\Designation;

class DesignationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Designation::class, 10)->create();
    }
}
