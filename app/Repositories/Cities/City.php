<?php

namespace Whatsloan\Repositories\Cities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Services\Audits\Auditable;

class City extends Model
{

    use SoftDeletes, Auditable;

    /**
     * Fillable fields on the model
     *
     * @var array
     */
    protected $fillable = ['uuid','name'];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }




    public function builders() {

    }

    /**
     * City has multiple Leads
     * @return Collection
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }


    /**
     * City has multiple banks
     * @return Collection
     */
    public function banks()
    {
        return $this->hasMany(Bank::class);
    }



}
