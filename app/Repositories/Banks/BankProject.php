<?php

namespace Whatsloan\Repositories\Banks;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\Audits\Auditable;

class BankProject extends Model
{
    use Auditable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_project';

    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'bank_id',
      'project_id',
      'agent_id',
      'status'
    ];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['approved_date'];
    
    /**
     * Bank project has a bank
     * @return type
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }



    /**
     * Bank project has a bank
     * @return type
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }



    /**
     * Bank project has a agent
     * @return type
     */
    public function agent()
    {
        return $this->belongsTo(User::class);
    }

}
