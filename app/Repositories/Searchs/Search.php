<?php

namespace Whatsloan\Repositories\Searchs;

use Illuminate\Database\Eloquent\Model;


use Laracasts\Presenter\PresentableTrait;

use Whatsloan\Services\AccessControl\ControlsAccess;
use Whatsloan\Services\Audits\Auditable;

class Search extends Model
{

    use PresentableTrait, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'user_id', 'task_status_id', 'task_stage_id', 'priority', 'description', 'from', 'to'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Date objects on the model
     *
     * @var array
     */
    protected $dates = ['from', 'to', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Model presenter
     *
     * @var $presenter
     */
    protected $presenter = Presenter::class;



}
