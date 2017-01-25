<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Products\Product;
use Whatsloan\Repositories\Banks\BankProduct;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\ProjectStatuses\ProjectStatus;
use Whatsloan\Repositories\Users\User;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        
        $bankProduct = factory(BankProduct::class, 20)->create()->each(function($bankProduct) {
            $attachments = factory(Attachment::class, 1)->make();
            $bankProduct->attachments()->save($attachments);
        });
    }
}
