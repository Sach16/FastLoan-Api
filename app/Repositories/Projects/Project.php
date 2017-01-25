<?php

namespace Whatsloan\Repositories\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Attachments\Attachable;
use Whatsloan\Repositories\ProjectStatuses\ProjectStatus;
use Whatsloan\Repositories\Addresses\Addressable;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Queries\Query;
use Whatsloan\Repositories\Units\Unit;
use Whatsloan\Services\Audits\Auditable;
use Carbon\Carbon;
use Whatsloan\Repositories\Payouts\Payoutable;
use Whatsloan\Repositories\Loans\Loan;

class Project extends Model
{
    use Addressable,
        PresentableTrait,
        SoftDeletes,
        Auditable,
        Attachable,
        Payoutable;

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
        'name',
        'builder_id',
        'unit_details',
        'status_id',
        'owner_id',
       // 'assigned_to',
        'possession_date',
        'lsr_start_date',
        'lsr_end_date',
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
     * Returns the path of documents
     *
     * @return string
     */
    public function getDocumentPath()
    {
        return 'documents/' . $this->uuid . '/';
    }

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'possession_date',
        'lsr_end_date',
        'lsr_start_date',
        'created_at',
        'updated_at',
    ];

    /**
     * Set the possession_date.
     *
     * @param  string  $value
     * @return string
     */
    public function setPossessionDateAttribute($value)
    {
        $this->attributes['possession_date'] = Carbon::parse($value);
    }
    
    /**
     * Associated banks
     * @return Collection
     */
    public function banks()
    {
        return $this->belongsToMany(Bank::class)
                        ->withTimestamps()
                        ->withPivot(['agent_id', 'status']);
    }

    /**
     * Project has owner
     * @return Item
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Task has assignee
     * @return Item
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Project has status
     */
    public function status()
    {
        return $this->belongsTo(ProjectStatus::class);
    }

    /**
     * Projects has a builder
     * @return type
     */
    public function builder()
    {
        return $this->belongsTo(Builder::class);
    }

    /**
     * Projects has a builder
     * @return type
     */
    public function builderTrashed()
    {
        return $this->builder()->withTrashed();
    }




    /**
     * Project has many Lsr queries
     * @return type
     */
    public function queries()
    {
        return $this->hasMany(Query::class);
    }

    /**
     * Custom Pivots
     *
     * @param Model $parent
     * @param array $attributes
     * @param string $table
     * @param bool $exists
     * @return \Illuminate\Database\Eloquent\Relations\Pivot|Agent
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        // Override the pivot on the relation
        if ($parent instanceof Bank) {
            return new Agent($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }



    /**
     * Project has many Unit numbers
     * @return Collection
     */
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    /**
     * Returns the path of documents
     *
     * @return string
     */
    public function getProjectPicturePath()
    {
        return 'projects/' . $this->uuid . '/';
    }

    /**
     *  project has Loan
     * @return Item
     */
    public function loan()
    {
        return $this->belongsToMany(Loan::class)->withTimestamps();
    }

}
