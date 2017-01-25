<?php

namespace Whatsloan\Repositories\LoanStatuses;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\Audits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanStatus extends Model
{
    use Auditable,SoftDeletes;
}
