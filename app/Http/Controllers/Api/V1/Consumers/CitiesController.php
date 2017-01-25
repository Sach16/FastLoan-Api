<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;

use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;

use Whatsloan\Repositories\Cities\Contract as Cities;
use Whatsloan\Http\Transformers\V1\Consumers\CityTransformer;

class CitiesController extends Controller
{



    /**
     * @var $cities
     */
    private $cities;



    /**
     * City controller constructor
     * @param Cities $cities
     */
    public function __construct(Cities $cities)
    {
        $this->cities = $cities;
    }


    /**
     * Index action
     * @return [type] [description]
     */
    public function index()
    {
        $cities = $this->cities->paginateAsConsumers();
        return $this->transformCollection($cities, CityTransformer::class);
    }
}
