<?php

namespace Whatsloan\Repositories\Teams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Repositories\Calendars\Calendar;
use Whatsloan\Repositories\Users\User;

/**
 * @property mixed name
 * @property mixed description
 * @property mixed status
 * @property mixed created_by
 * @property mixed uuid
 * @property mixed created_at
 * @property mixed updated_at
 */
use Whatsloan\Services\AccessControl\ControlsAccess;
use Whatsloan\Services\Audits\Auditable;

class Team extends Model
{

    use ControlsAccess, SoftDeletes, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'bank_code',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    /**
     * Each team has multiple members
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps()->withPivot(['is_owner','target','achieved','incentive_plan','incentive_earned']);
    }
    
    /**
     * Each team has multiple members
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referrals()
    {
        return $this->belongsToMany(User::class,'referral_team','team_id','referral_id')->withTimestamps();
    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyMembers($query)
    {
        return $query->with(['members' => function ($q) {
            $q->where('is_owner', false);
        }]);
    }

    /**
     * Scope a query to only include active owner.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyOwners($query)
    {
        return $query->with(['members' => function ($q) {
            $q->where('is_owner', true);
        }]);
    }


    /**
     * Each team has multiple task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }
}
