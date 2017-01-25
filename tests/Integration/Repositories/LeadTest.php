<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Whatsloan\Events\LeadWasAdded;
use Whatsloan\Repositories\Leads\Contract;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\AccessControl\AccessControlScope;

class LeadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_should_fetch_a_paginated_list_of_leads()
    {
        factory(Lead::class, 20)->create();
        $repository = app(Contract::class);
        $this->assertEquals(15, $repository->paginate()->count());
    }

    /** @test */
    public function it_should_fetch_the_details_of_a_single_lead()
    {
        $lead = factory(Lead::class)->create(['uuid' => '123']);
        $repository = app(Contract::class);
        $this->assertEquals($lead->uuid, $repository->find(123)->uuid);
    }

    /** @test */
    public function it_should_add_a_new_lead()
    {
        $repository = app(Contract::class);
        $user = factory(User::class)->create();
        factory(Source::class)->create();
        factory(Type::class)->create();
        $request = new Request(factory(Lead::class)->make()->toArray());
        $request->offsetSet('user_id', $user->id);

        $response = $repository->add($request);
        $this->seeInDatabase('leads', $response->toArray());
    }

    /** @test */
    public function it_should_get_the_validation_rules_of_the_model()
    {
        $repository = app(Contract::class);
        $rules = $repository->getValidations();

        $keys = [
            'uuid',
            'user_uuid',
            'source_uuid',
            'type_uuid',
            'name',
            'loan_amount',
            'net_salary',
            'existing_loan_emi',
            'company_name',
            'created_by',
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $rules);
        }
    }
}
