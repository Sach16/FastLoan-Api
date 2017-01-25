<?php

use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Users\User;

class LeadsTest extends TestCase
{
    use WithoutMiddleware, WithoutEvents, DatabaseMigrations;

    /** @test */
    public function it_should_display_list_of_leads()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $type = factory(Type::class)->create();

        $data = [
            'name'             => 'Deepika Padukone',
            'user_id'          => $user->id,
            'source_id'        => $source->id,
            'type_id'          => $type->id,
        ];
        factory(Lead::class)->create($data);

        $this->visit(route('api.v1.leads.index'))
            ->seeStatusCode(200)
            ->seeJsonContains(['name' => 'Deepika Padukone']);
    }

    /** @test */
    public function it_should_display_the_details_of_a_single_lead()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $type = factory(Type::class)->create();

        $data = [
            'name'      => 'Deepika Padukone',
            'user_id'   => $user->id,
            'source_id' => $source->id,
            'type_id'   => $type->id,
        ];
        $lead = factory(Lead::class)->create($data);

        $this->visit(route('api.v1.leads.show', $lead->uuid))
            ->seeStatusCode(200)
            ->seeJsonContains(['name' => 'Deepika Padukone']);
    }
}
