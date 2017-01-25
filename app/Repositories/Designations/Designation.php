<?php

namespace Whatsloan\Repositories\Designations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Services\Audits\Auditable;
use Whatsloan\Repositories\Users\User;

class Designation extends Model
{

    use SoftDeletes, Auditable;

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
     * Get the user that owns the designation.
     */
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
