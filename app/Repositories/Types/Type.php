<?php

namespace Whatsloan\Repositories\Types;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Services\Audits\Auditable;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Attachments\Attachable;

/**
 * @property mixed uuid
 * @property mixed name
 * @property mixed description
 * @property mixed created_at
 * @property mixed updated_at
 */
class Type extends Model
{

    use Auditable,Attachable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'name', 'description','key'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Associated banks
     * @return Collection
     */
    public function banks()
    {
        return $this->belongsToMany(Bank::class,'bank_product','product_id','bank_id')->withTimestamps();
    }

    /**
     * Associated loans
     * @return Collection
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

}
