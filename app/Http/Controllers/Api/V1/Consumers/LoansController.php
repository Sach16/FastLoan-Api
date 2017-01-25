<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\Consumers\ForbiddenActionTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\LoanTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\AttachmentTransformer;

//use Whatsloan\Repositories\Users\Contract as IUsers;


use Whatsloan\Repositories\Loans\Contract as ILoans;

class LoansController extends Controller
{

    /**
     * @var $loans 
     */
    private $loans;

    
    /**
     * Inteface Contract
     * @param ILoans $loans
     */
    public function __construct(ILoans $loans)
    {
        $this->loans = $loans;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = \Auth::guard('api')->user();
        $loans = $this->loans->paginateAsConsumers($authUser->id);
        return $this->transformCollection($loans, LoanTransformer::class); 
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
     * @lastupdate 22042016
     */
    public function show($uuid)
    {
        $loan = $this->loans->show($uuid);
        return $this->transformItem($loan,LoanTransformer::class);
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

}
