<?php

namespace Whatsloan\Repositories\LoanAlert;

use Illuminate\Database\Eloquent\Model;


use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Services\Audits\Auditable;

class LoanAlert extends Model
{

    use Auditable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid','bank_id','type_id','loan_emi_amount','due_date','interest_rate','balance_amount','emi_start_date','emi','take_over','part_pre_payement','type_of_property'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * User has a bank
     * @return Collection
     */
    public function banks()
    {
        return $this->belongsTo(Bank::class,'bank_id');
    }

    public function types()
    {
        return $this->belongsTo(Type::class,'type_id');
    }

}
