<?php
namespace Whatsloan\Repositories\Types;


class Repository implements Contract
{
    private $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }


    public function paginate($limit = 15)
    {
        return $this->type->paginate();
    }
    
        
    /**
     * Store a new Product
     *
     * @param $request
     * @return mixed
     */
    public function store(array $request)
    {
       return $this->type->create($request);
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
        $product = $this->type->withTrashed()->find($id);
        return $product->update($request); 
    }

}
