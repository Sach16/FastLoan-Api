<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Requests;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Builders\Contract as Builder;
use Whatsloan\Http\Transformers\V1\BuilderTransformer;

class BuildersController extends Controller
{
    
    
    
    /**
     * Builders
     * @var $builder 
     */
    private $builders;
    
    
    
    /**
     * Controller constructor
     * @param Builder $builders
     */
    public function __construct(Builder $builders)
    {
        $this->builders = $builders;
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $builders = $this->builders->paginate();
        return $this->transformCollection($builders, BuilderTransformer::class);
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
     * Getting the Builders who has the project
     * @return type
     */
    public function getBuilders()
    {        
        $builders = $this->builders->getBuilders();
        return $this->transformCollection($builders, BuilderTransformer::class);
    }
}
