<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Whatsloan\Http\Transformers\V1\LeadTransformer;
use Whatsloan\Repositories\Leads\Lead;

class LeadTransformerTest extends TestCase
{

    /** @test */
    public function it_should_transform_a_lead_model()
    {
        $data = ['uuid' => 123];
        $lead = factory(Lead::class)->make($data);
        $transformer = new LeadTransformer();

        $this->assertArraySubset($data, $transformer->transform($lead));
    }
}
