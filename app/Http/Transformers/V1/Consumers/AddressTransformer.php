<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Addresses\Address;

class AddressTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
         'city'
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [

    ];


    /**
     * Address transformer
     * @param  Address $address
     * @return array
     */
    public function transform(Address $address)
    {
        return [
            'uuid'         => $address->uuid,
            'email'        => $address->email,
            'phone'        => $address->phone,
            'alpha_street' => $address->alpha_street,
            'beta_street'  => $address->beta_street,
            'city_id'      => $address->city_id,
            'state'        => $address->state,
            'country'      => $address->country,
            'zip'          => $address->zip,
            'created_at'   => $address->created_at,
            'updated_at'   => $address->updated_at,
        ];
    }

    /**
     * Inclider City
     * @return Item
     */
    public function includeCity(Address $address)
    {

        return $this->item($address->city, new CityTransformer);
    }
}
