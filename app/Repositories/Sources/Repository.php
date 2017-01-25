<?php

namespace Whatsloan\Repositories\Sources;


class Repository implements Contract
{

    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function paginate($limit = 15)
    {
        return $this->source
                ->with('leads')
                ->orderBy('deleted_at', 'asc')
                ->withTrashed()
                ->paginate();
    }

    /**
     * Store source
     * @param array $data
     */
    public function store($data)
    {
        $this->source->create([
            'uuid' => uuid(),
            'name' => $data['name'],
            'key' => strtoupper(str_slug($data['name']))
        ]);
    }

    /**
     * Get single source details
     * @param integer $id
     */
    public function find($id)
    {
        return $this->source
                ->withTrashed()
                ->findOrFail($id);
    }

    
    
    /**
     * Update single source 
     * @param array $request
     * @param integer $id
     */
    public function update($request, $id){
        
        $source = $this->source
            ->withTrashed()
            ->find($id);
        return $source->update($request);
    }
    
}
