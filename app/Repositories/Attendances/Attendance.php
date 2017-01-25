<?php

namespace Whatsloan\Repositories\Attendances;

use Illuminate\Database\Eloquent\Model;

use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\Audits\Auditable;
use Carbon\Carbon;

class Attendance extends Model
{

    //use Auditable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'start_time',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date','start_time'];


    /**
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Filter API queries
     *
     * @param $query
     * @param $request
     */
    public function scopeFilters($query, $request)
    {
        $memberId = $request->has('memberId') ? $request->memberId : \Auth::guard('api')->user()->id;
        $query->where('user_id', $memberId);

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('start_time', [Carbon::parse($request->from)->startOfDay(),  Carbon::parse($request->to)->endOfDay()]);
        }
    }
}
