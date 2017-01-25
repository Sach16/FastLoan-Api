<?php

namespace Whatsloan\Repositories\Campaigns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\Addresses\Addressable;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\AccessControl\ControlsAccess;
use Whatsloan\Services\Audits\Auditable;
use Carbon\Carbon;

/**
 * @property mixed uuid
 * @property mixed name
 * @property mixed description
 * @property mixed promotionals
 * @property mixed type
 * @property mixed from
 * @property mixed to
 * @property mixed created_at
 * @property mixed updated_at
 */
class Campaign extends Model
{

    use Addressable, PresentableTrait, SoftDeletes, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'organizer', 'name', 'description', 'promotionals', 'from', 'to', 'type','team_id'];

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
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'from', 'to'];

    /**
     * Model presenter
     *
     * @var $presenter
     */
    protected $presenter = Presenter::class;
    
     /**
     * Set the from, to.
     *
     * @param  string  $value
     * @return string
     */
    public function setFromAttribute($value)
    {
        $this->attributes['from'] = Carbon::parse($value);
    }
    
    public function setToAttribute($value)
    {
        $this->attributes['to'] = Carbon::parse($value);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'campaign_member', null, 'member_id')
                    ->withTimestamps();
    }
}
