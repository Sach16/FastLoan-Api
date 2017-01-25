<?php

namespace Whatsloan\Repositories\Cities;

use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Users\User;

class Repository implements Contract
{

    /**
     * @var City
     */
    private $city;

    /**
     * City repository constructor
     * @param City $city
     */
    public function __construct(City $city)
    {
        $this->city = $city;
    }

    /**
     * Get a single city details
     * @param  string $uuid
     * @return Item
     */
    public function find($uuid)
    {
        return $this->city->whereUuid($uuid)->firstOrFail();
    }

    /**
     * Get a paginated list of ciries
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {

        $bank = null;
        if (isset(request()->bank_uuid)) {
            $bank = Bank::whereUuid(request()->bank_uuid)->first();
        }

        $city = $this->city->with(['addresses' => function($query) use($bank) {
                        if ($bank) {
                            $query->where('addressable_type', Bank::class);
                            $query->whereIn('addressable_id', $bank->id);
                        } else {
                            $query->where('addressable_type', User::class);
                            $query->whereIn('addressable_id', teamMemberIds(true));
                        }
                    }])
                ->whereHas('addresses', function($query) use($bank) {

                    if (isset(request()->bank_uuid)) {
                        if ($bank) {
                            $query->where('addressable_type', Bank::class);
                            $query->whereIn('addressable_id', $bank->id);
                        }
                    } else {
                        $query->where('addressable_type', User::class);
                        $query->whereIn('addressable_id', teamMemberIds(true));
                    }
                });

           if(isset(request()->paginate) && request()->paginate == 'all') {
               return $city->get();
           }

          return $city->paginate($limit);
    }



    public function paginateAsConsumers($limit = 15)
    {
         return $this->city->paginate();
    }

    /**
     * Update a city as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id)
    {
        $city = $this->city->withTrashed()->find($id);
        $city->update($request);

        return $city;
    }

    /**
     * Store a new city as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request)
    {
        return $this->city->create($request);
    }
}
