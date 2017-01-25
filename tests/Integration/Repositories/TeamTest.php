<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Users\User;

class TeamTest extends TestCase
{

    use DatabaseMigrations;


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function team_should_be_created_by_dsa_owner()
    {
        $user = factory(User::class)->create(['role' => 'DSA_OWNER']);
        $team = factory(Team::class)->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($team->first()->owner->first()->role, 'DSA_OWNER');
    }
}
