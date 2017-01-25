<?php

namespace Whatsloan\Repositories\Products;


use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Banks\BankProduct;
use Whatsloan\Repositories\Cities\City;
use Illuminate\Http\Request;
use Uuid;

class Repository implements Contract
{
    /**
     * @var Product
     */
    private $product;
    
    /**
     * @var Product
     */
    private $bank;
    
    /**
     * Repository constructor.
     * @param Product $product
     */
    public function __construct(Product $product, Bank $bank)
    {
        $this->product = $product;
        $this->bank = $bank;
    }

    /**
     * Get a paginated list of products
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->task->paginate($limit);
    }
    
    /**
     * Store a new Product
     *
     * @param $request
     * @return mixed
     */
    public function store(array $request)
    {
       return $this->product->create($request);
    }
    
     /**
     * Update an existing Product
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update(array $request, $id)
    {           
        $product = $this->product->find($id);
        return $product->update($request); 
    }  
    
}
