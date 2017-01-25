<?php

namespace Whatsloan\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\Audits\Auditable;
use Carbon\Carbon;

class Query extends Model
{

    use PresentableTrait, SoftDeletes, Auditable;

    /**
     * Model Presenter
     *
     * @var
     */
    protected $presenter = Presenter::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'project_id',
        'query',
        'assigned_to',
        'start_date',
        'end_date',
        'due_date',
        'status',
        'pending_with',
    ];

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date', 'due_date'];

    /**
     * Query has project
     * @return type
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }



    /**
     * Query has an assignee
     * @return type
     */
    public function assignee()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }

    /**
     * Set the start_date, end_date, due_date.
     *
     * @param  string  $value
     * @return string
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = date('Y-m-d H:i:s',strtotime($value)) ;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = date('Y-m-d H:i:s',strtotime($value));
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::parse($value);
    }
}
