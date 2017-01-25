<?php

use Illuminate\Database\Seeder;

use Whatsloan\Repositories\Sources\Source;

class SourcesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Source::class)->create([                
                'name' => 'Campaign',
                'key' =>'CAMPAIGN',
            ]);
        
        factory(Source::class)->create([                
                'name' => 'Referral',
                'key' =>'REFERRAL',
            ]);     
        
    }  
}
