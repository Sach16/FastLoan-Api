<?php

namespace Whatsloan\Repositories\Builders;

use Whatsloan\Repositories\Projects\Project;

class Repository implements Contract
{

    /**
     * @var $builder
     */
    private $builder;

    /**
     * Builder repository constructor
     * @param \Whatsloan\Repositories\Builders\Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Paginated list of builder
     * @param integer $limit
     */
    public function paginate($limit = 15)
    {
        return $this->builder->with(['addresses','addresses.city'])
                             ->whereHas('addresses.city',function($query){
                                 if(isset(request()->city_uuid)) {
                                    $query->where('uuid',request()->city_uuid);
                                 }
                              })
                             ->paginate();
    }

    /**
     * Update an existing builder
     *
     * @param $request
     * @param $id
     * @return mixed
     */
    public function update($request, $id)
    {
        $builder = $this->builder->withTrashed()->find($id);
        $builder->update($request);
        $builder->addresses()->first()->update($request);
        return $builder;
    }

    /**
     * Getting builder who has the projects
     * @return type
     */
    public function getBuilders()
    {
        $builderIds = Project::whereNotNull('builder_id')->distinct()->lists('builder_id')->all();
        return $builders = $this->builder->whereIn('id',$builderIds)->get();
    }
}
