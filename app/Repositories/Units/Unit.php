<?php

namespace Whatsloan\Repositories\Units;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\Audits\Auditable;

class Unit extends Model
{

    use Auditable;
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'number',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];




}
