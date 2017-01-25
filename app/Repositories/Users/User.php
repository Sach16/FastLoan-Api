<?php

namespace Whatsloan\Repositories\Users;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\Attachments\Attachable;
use Whatsloan\Repositories\Designations\Designation;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\Users\TrackUser;
use Whatsloan\Repositories\Attendances\Attendance;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Banks\Bank;

use Whatsloan\Repositories\Addresses\Addressable;
use Whatsloan\Services\Audits\Auditable;

use Whatsloan\Repositories\Payouts\Payoutable;
use Whatsloan\Repositories\Leads\Lead;

/**
 * @property mixed uuid
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed email
 * @property mixed role
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed id
 * @property mixed api_token
 * @property mixed tasks
 * @property mixed phone
 * @property mixed settings
 */
class User extends Authenticatable
{

    use PresentableTrait,
            Addressable,
            Attachable,
            Auditable,
            Payoutable,
            SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'first_name', 'last_name', 'phone', 'email', 'settings', 'role','designation_id','track_status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'json',
    ];

    /**
     * Model Presenter
     *
     *
     * @var $presenter
     */
    protected $presenter = Presenter::class;

    /**
     * Use has many teams
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class)
                ->withPivot(['is_owner','target','achieved','incentive_plan','incentive_earned']);
    }

    /**
     * Get the tasks
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    /**
     * Get the tasks
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * User has many attendances
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * User has many projects
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class,'owner_id');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyMembers($query)
    {
        return $query->with(['teams.members' => function ($q) {
                        $q->where('is_owner', false);
                    }]);
    }

    /**
     * Get the settings object
     *
     * @return Settings
     */
    public function settings()
    {
        return new Settings($this);
    }

    /**
     * User has many loans
     * @return Collection
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * User has many trashed loans
     * @return Collection
     */
    public function loansTrashed()
    {
        return $this->hasMany(Loan::class)->withTrashed();
    }

    /**
     * User has a bank
     * @return Collection
     */
    public function banks()
    {
        return $this->belongsToMany(Bank::class)->withTimestamps();
    }

    /**
     * Returns the consumer role
     *
     * @param $query
     * @return mixed
     */
    public function scopeCustomers($query)
    {
        return $query->whereRole('CONSUMER');
    }

    /**
     * Get the customers document path
     *
     * @return string
     */
    public function getCustomerDocumentPath()
    {
        $path = 'documents/';
        $path .= 'customers/';
        $path .= $this->uuid .'/';
        return $path;
    }

    /**
     * @return string
     */
    public function getUserProfilePicturePath()
    {
        $path = 'pictures/';
        $path .= 'customers/';
        $path .= $this->uuid .'/';
        return $path;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function designationTrashed()
    {
        return $this->designation()->withTrashed();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function track_user()
    {
        return $this->belongsTo(TrackUser::class,'track_status');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reportsTo()
    {
        return $this->belongsToMany(User::class,'user_reports','user_id','report_to')
                    ->withTimestamps();
    }

    /**
     * Each user has multiple team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referrals()
    {
        return $this->belongsToMany(Team::class,'referral_team','referral_id','team_id')->withTimestamps();
    }

    /**
     * Each user has assigned to  multiple leads
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignee()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }
}
