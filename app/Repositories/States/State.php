<?php

namespace Whatsloan\Repositories\States;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Services\Audits\Auditable;
use Whatsloan\Repositories\Localities\Locality;

class State extends Model
{
    use Auditable, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'name', 'description'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];   
    /**
     * State has multiple Localities
     * @return Collection
     */
    public function localities()
    {
        return $this->hasMany(Locality::class);
    }
    
}
