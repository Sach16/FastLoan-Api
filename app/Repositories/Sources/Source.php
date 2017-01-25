<?php

namespace Whatsloan\Repositories\Sources;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\Audits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Repositories\Leads\Lead;
/**
 * @property mixed uuid
 * @property mixed name
 * @property mixed description
 * @property mixed created_at
 * @property mixed updated_at
 */
class Source extends Model
{
    use Auditable,SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid','name','key'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Source has many Leads
     * @return Collection
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

}
