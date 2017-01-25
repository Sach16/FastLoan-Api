<?php

use Illuminate\Database\Seeder;

use Whatsloan\Repositories\Types\Type;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            factory(Type::class)->create([
                'name' =>'Home Loan',
                'key' => 'HL'
            ]);
            
            factory(Type::class)->create([
                'name' => 'Personal Loan',
                'key' => 'PL'
            ]);
            
            
    }
}
