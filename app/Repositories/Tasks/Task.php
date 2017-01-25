<?php

namespace Whatsloan\Repositories\Tasks;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;
use Whatsloan\Repositories\TaskStages\TaskStage;
use Whatsloan\Services\AccessControl\ControlsAccess;
use Whatsloan\Services\Audits\Auditable;
use Carbon\Carbon;
use Whatsloan\Repositories\Loans\Loan;

class Task extends Model
{

    use PresentableTrait, SoftDeletes;
    
    //use PresentableTrait, SoftDeletes, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'user_id', 'taskable_id','taskable_type','task_status_id', 'task_stage_id', 'priority', 'description', 'from', 'to'];


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
     * Set the 'from' date format.
     *
     * @param  string  $value
     * @return string
     */
    public function setFromAttribute($value)
    {
        $this->attributes['from'] = Carbon::parse($value);
    }
    /**
     * Set the date 'to' format.
     *
     * @param  string  $value
     * @return string
     */
    public function setToAttribute($value)
    {
        $this->attributes['to'] = Carbon::parse($value);
    }

    /**
     * Model presenter
     *
     * @var $presenter
     */
    protected $presenter = Presenter::class;

    /**
     * Get all of the owning taskable models.
     */
    public function taskable()
    {
        return $this->morphTo();
    }

    /**
     * Get the assignee that owns the Task.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    

    /**
     * Get the post that owns the Task.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(TaskStatus::class,'task_status_id');
    }

    /**
     * Get the post that owns the tashed Task.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusTrashed()
    {
        return $this->status()->withTrashed();
    }

    /**
     * Get the loan that owns the Task.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the stage that owns the Task.
     */
    public function stage()
    {
        return $this->belongsTo(TaskStage::class, 'task_stage_id');
    }

    /**
     * Get the trashed stage that owns the Task.
     */
    public function stageTrashed()
    {
        return $this->stage()->withTrashed();
    }

    /**
    * Get the team that owns the Task.
    */
    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get the User who owns the Task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
