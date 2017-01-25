<?php

namespace Whatsloan\Repositories\Addresses;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Services\Audits\Auditable;

/**
 * @property mixed uuid
 * @property mixed email
 * @property mixed alpha_street
 * @property mixed beta_street
 * @property mixed city
 * @property mixed state
 * @property mixed country
 * @property mixed zip
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed phone
 */
class Address extends Model
{

    use PresentableTrait, Auditable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'email',
        'alpha_street',
        'beta_street',
        'city_id',
        'state',
        'country',
        'zip',
        'latitude',
        'longitude'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Model Presenter
     *
     * @var $presenter
     */
    protected $presenter = Presenter::class;

    /**
     * Address has city
     * @return Item
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
