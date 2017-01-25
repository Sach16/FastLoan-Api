<?php

namespace Whatsloan\Repositories\LoanDocuments;

use Illuminate\Database\Eloquent\Model;

use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Services\Audits\Auditable;

class LoanDocument extends Model
{
    use Auditable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['loan_id', 'attachment_id'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'created_at', 'updated_at',];


    /**
     * Get the Loan thats owns the Loan Documents.
     */
    public function loans()
    {
        return $this->belongsTo(Loan::class);
    }
}
