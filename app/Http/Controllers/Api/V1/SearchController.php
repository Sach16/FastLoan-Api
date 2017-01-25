<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Users\Contract as Users;
use Whatsloan\Repositories\Searchs\Contract as Searchs;
use Whatsloan\Http\Requests\Api\V1\SearchRequest;
use Whatsloan\Http\Transformers\V1\SearchTransformer;
use Whatsloan\Http\Transformers\V1\LoanTransformer;

class SearchController extends Controller
{

    /**
     * @var Leads
     */
    private $search;

    /**
     * TasksController constructor
     *
     * @param Tasks $tasks
     */
    public function __construct(Searchs $search, Users $users)
    {
        $this->search = $search;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchRequest $request)
    {

        $result = [
            'loanTypeCount' => [
                'data' => [
                    'customers' => [
                        'data' => [
                            'HL' => $this->search->search('CONSUMER','HL'),
                            'PL' => $this->search->search('CONSUMER','PL'),
                        ]
                    ],
                    'leads' => [
                        'data' => [
                            'HL' => $this->search->search('LEAD','HL'),
                            'PL' => $this->search->search('LEAD','PL'),
                        ]
                    ]
                ]
            ],
            'leads' => $this->transformCollection($this->search->search('LEAD'), LoanTransformer::class),
            'customers' => $this->transformCollection($this->search->search('CONSUMER'), LoanTransformer::class),
        ];
        

        return $this->transformMultipleWithPaginaotr($result, SearchTransformer::class);
    }

    /**
     * Search customer
     * @param SearchRequest $request
     * @return type
     */
    public function customers(SearchRequest $request)
    {
        $result = [
            'loanTypeCount' => [
                'data' => [
                    'customers' => [
                        'data' => [
                            'HL' => $this->search->search('CONSUMER','HL'),
                            'PL' => $this->search->search('CONSUMER','PL'),
                        ]
                    ],
                    
                ]
            ],
            'customers' => $this->transformCollection($this->search->search('CONSUMER'), LoanTransformer::class),
        ];
        return $this->transformMultipleWithPaginaotr($result, SearchTransformer::class);
    }
    
    
    /**
     * Search leads
     * @param SearchRequest $request
     * @return type
     */
    public function leads(SearchRequest $request)
    {
        $result = [
            'loanTypeCount' => [
                'data' => [
                    'leads' => [
                        'data' => [
                            'HL' => $this->search->search('LEAD','HL'),
                            'PL' => $this->search->search('LEAD','PL'),
                        ]
                    ]
                ]
            ],
            'leads' => $this->transformCollection($this->search->search('LEAD'), LoanTransformer::class),            
        ];
        
        return $this->transformMultipleWithPaginaotr($result, SearchTransformer::class);
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
    public function store(StoreTaskRequest $request)
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
    public function update(UpdateTaskRequest $request, $uuid)
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
