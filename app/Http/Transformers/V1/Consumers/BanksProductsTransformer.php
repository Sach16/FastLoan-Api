<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;

use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Banks\BankProduct;
use Whatsloan\Repositories\Addresses\Address;


class BanksProductsTransformer extends TransformerAbstract
{
    
    
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'attachments',
        
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'bank',
        'product',
        'addresses',

    ];

    /**
     * @param $e
     * @return array
     */
    public function transform(BankProduct $bankProduct)
    {
       
        return [
            'bank_id'    => $bankProduct->bank_id,
            'product_id' => $bankProduct->product_id,
            'created_at' => $bankProduct->created_at,
            'updated_at' => $bankProduct->updated_at,
        ];
    }
      
    public function includeProduct(BankProduct $bankProduct)
    {
        return $this->item($bankProduct->product, new ProductTransformer);
    }
    
     /**
      * Include banks
      * @param \Whatsloan\Http\Transformers\V1\BankProduct $bankProduct
      * @return type
      */
    public function includeBank(BankProduct $bankProduct)
    {
        return $this->item($bankProduct->bank, new BankTransformer);
    }
    
    /**
     * Include Products Attachments
     * @param BankProduct $bankProduct
     * @return type
     */
    public function includeAttachments(BankProduct $bankProduct)
    {
        return $this->collection($bankProduct->attachments, new AttachmentTransformer);
    }
    
    /**
     * Include Products Attachments
     * @param BankProduct $bankProduct
     * @return type
     */
    public function includeAddresses(BankProduct $bankProduct)
    {       
        return $this->collection($bankProduct->bank->addresses, new AddressTransformer);
    }    
    
    
}
