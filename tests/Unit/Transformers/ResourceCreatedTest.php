<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Repositories\Leads\Lead;

class ResourceCreatedTest extends TestCase
{
    /** @test */
    public function it_should_transform_a_new_resource()
    {
        $data = ['id' => 123];
        $lead = factory(Lead::class)->make($data);
        $transformer = new ResourceCreated();

        $this->assertArraySubset([
            'status'   => 'created',
            'code'     => 201,
        ], $transformer->transform($lead));
    }
}
