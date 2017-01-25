<?php

namespace Whatsloan\Repositories\Roles;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\Audits\Auditable;

class Role extends Model
{
    use Auditable;
}
