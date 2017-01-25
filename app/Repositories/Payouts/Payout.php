<?php

namespace Whatsloan\Repositories\Payouts;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\Audits\Auditable;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Users\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payout extends Model
{

    use Auditable,SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid','percentage','total_paid_amount'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payoutable()
    {
        return $this->morphTo();
    }
    
    /**
     * Payout has users
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    /**
     * Payout has a builder
     * @return type
     */
    public function builder()
    {
        return $this->belongsTo(Builder::class);
    }
}
