<?php

namespace Whatsloan\Repositories\Localities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Services\Audits\Auditable;
use Whatsloan\Repositories\States\State;

class Locality extends Model
{
    use Auditable, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'name', 'description','state_id'];

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
     * Locality Associated State
     * @return Collection
     */
    public function state()
    {
        return $this->belongsTo(State::class)->withTrashed();
    }
    
}
