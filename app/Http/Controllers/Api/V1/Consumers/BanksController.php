<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Banks\Contract as Banks;
use Whatsloan\Http\Transformers\V1\Consumers\BankTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\BankValidationFaildTransformer;

use Illuminate\Support\Facades\Validator;

class BanksController extends Controller
{

    /**
     * @var $banks
     */
    private $banks;

    /**
     * Banks controller constructor
     * @param Banks $banks
     */
    public function __construct(Banks $banks)
    {
        $this->banks = $banks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = $this->banks->paginate(request()->all());
        return $this->transformCollection($banks, new BankTransformer);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
     * Get Team members bank list
     */
    public function teamMembersBanks()
    {
        $banks = $this->banks->membersBanks();
        return $this->transformCollection($banks, new BankTransformer);
    }
    
    /**
     * Get Bank Documents lists
     */
    public function bankDocuments()
    {
        $banks = $this->banks->getBankDocuments();
        
        return $this->transformCollection($banks, new BankTransformer);
    }
    public function getBankByCity(){
        
        $validator = Validator::make(request()->all(), [
            'city_uuid' => 'required|exists:cities,uuid',
        ]);
         if ($validator->fails()) {
             return $this->transformItem($validator->messages(),BankValidationFaildTransformer::class, 400);
         }
        
        $banks = $this->banks->paginateAsConsumers(request()->all());
        return $this->transformCollection($banks, new BankTransformer);
    }

}
