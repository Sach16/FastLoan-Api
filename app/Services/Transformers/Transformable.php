<?php

namespace Whatsloan\Services\Transformers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use \League\Fractal\Manager;
use \League\Fractal\Resource\Collection;
use \League\Fractal\Resource\Item;
use \League\Fractal\Pagination\IlluminatePaginatorAdapter;
use \Whatsloan\Services\Transformers\ArrayDataSerializer;

trait Transformable
{

    /**
     * @param $collection
     * @param $transformer
     * @param int $status
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function transformCollection($collection, $transformer, $status = 200)
    {
        $fractal = new Manager;
        $fractal->setSerializer(new ArrayDataSerializer());

        if (app('request')->get('include')) {
            $fractal->parseIncludes(app('request')->get('include'));
        }

        $resource = new Collection(
            $collection,
            new $transformer
        );
        
        
        if(get_class($collection) == "Illuminate\Pagination\LengthAwarePaginator") {
            $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        }

        return response($fractal->createData($resource)->toJson(), $status);
    }


     /**
     * @param $collection
     * @param $transformer
     * @param int $status
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function transformCollectionOnly($collection, $transformer, $status = 200)
    {
        $fractal = new Manager;
        //$fractal->setSerializer(new ArrayDataSerializer());

        if (app('request')->get('include')) {
            $fractal->parseIncludes(app('request')->get('include'));
        }

        $resource = new Collection(
            $collection,
            new $transformer
        );
        //$resource->setPaginator($collection);

        return response($fractal->createData($resource)->toJson(), $status);
    }


    /**
     * @param $item
     * @param $transformer
     * @param int $status
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function transformItem($item, $transformer, $status = 200)
    {
        $fractal = new Manager;
        //$fractal->setSerializer(new ArrayDataSerializer());

        if (is_null($item)) {
            throw new ModelNotFoundException('The requested resource could not be located.');
        }

        if (app('request')->get('include')) {
            $fractal->parseIncludes(app('request')->get('include'));
        }

        $resource = new Item(
            $item,
            new $transformer
        );


        return response($fractal->createData($resource)->toJson(), $status);
    }

    /**
     * @param $data
     * @param $transformer
     * @param int $status
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function transformDeleted($data, $transformer, $status = 200)
    {
        $fractal = new Manager;

        if (is_null($data)) {
            throw new ModelNotFoundException('The requested resource could not be located.');
        }

        $resource = new Item(
            $data,
            new $transformer
        );

        return response($fractal->createData($resource)->toJson(), $status);
    }


    /**
     * Tranforms multiple items
     * @param  array $items
     * @param  $transformer
     * @param  int $status
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function transformMultiple($data, $transformer, $status = 200)
    {

        $fractal = new Manager;
        $fractal->setSerializer(new ArrayDataSerializer());

        if (is_null($data)) {
            throw new ModelNotFoundException('The requested resource could not be located.');
        }

        if (app('request')->get('include')) {
            $fractal->parseIncludes(app('request')->get('include'));
        }

        $formatedData = [];
        foreach ($data as $key => $item) {
            $formatedData[$key] = json_decode($item->content())->data;
        }
        

        return $this->transformItem($formatedData, $transformer);
    }
    
    
    /**
     * Tranforms multiple items
     * @param  array $items
     * @param  $transformer
     * @param  int $status
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function transformMultipleWithPaginaotr($data, $transformer, $status = 200)
    {

        $fractal = new Manager;
        $fractal->setSerializer(new ArrayDataSerializer());

        if (is_null($data)) {
            throw new ModelNotFoundException('The requested resource could not be located.');
        }

        if (app('request')->get('include')) {
            $fractal->parseIncludes(app('request')->get('include'));
        }

        $formatedData = [];
        foreach ($data as $key => $item) {
            if(is_object($item)) {
                $formatedData[$key] = json_decode($item->content());
            } else {
                $formatedData[$key] = $item;
            }
        }
        
        

        return $this->transformItem($formatedData, $transformer);
    }
}
