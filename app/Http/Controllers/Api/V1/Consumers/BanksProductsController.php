<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Products\Product;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Jobs\UpdateProductApiJob;
//
use Whatsloan\Http\Requests\Api\V1\ProductFilterRequest;
use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Http\Transformers\V1\ResourceUpdated;
use Whatsloan\Http\Transformers\V1\Consumers\BanksProductsTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\BankTransformer;
use Whatsloan\Repositories\Banks\Contract as Banks;

class BanksProductsController extends Controller
{    
    
    protected $banks;
    
    public function __construct(Banks $banks)
    {
        $this->banks = $banks;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $bankId
     * @param $projectId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bankUuid, $projectUuid)
    {
       // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Get Product documents
     * @return type
     */
    public function documentFilters(ProductFilterRequest $request)
    {        
        $bank = $this->banks->documentFilters();
        return $this->transformCollection($bank, BanksProductsTransformer::class);   
    }  
}
