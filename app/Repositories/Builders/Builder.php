<?php

namespace Whatsloan\Repositories\Builders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Addresses\Addressable;
use Whatsloan\Services\Audits\Auditable;

class Builder extends Model
{

    use Addressable, SoftDeletes, Auditable;

    /**
     * Fillable fields on the model
     * 
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Builder has many projects
     * @return Collection
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }


    /**
     * Builder belongs to many cities
     * @return type
     */
    public function cities()
    {
        return $this->belongsToMany(Builder::class);
    }

}
