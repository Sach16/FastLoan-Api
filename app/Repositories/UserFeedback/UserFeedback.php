<?php

namespace Whatsloan\Repositories\UserFeedback;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\Audits\Auditable;

class UserFeedback extends Model
{

    use Auditable;

    protected $fillable = ['uuid','feedback_id','rating','user_id'];

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
     * Returns the path of documents
     *
     * @return string
     */

}
