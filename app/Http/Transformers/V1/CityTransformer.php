<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

use Whatsloan\Repositories\Cities\City;

class CityTransformer extends TransformerAbstract
{

    /**
     * @param $e
     * @return array
     */
    public function transform(City $city)
    {
        return [
            'uuid'      => $city->uuid,
            'name'      => $city->name,
            'latitude'  => $city->latitude,
            'longitude' => $city->longitude
        ];
    }
}
