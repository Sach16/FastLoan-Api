<?php

namespace Whatsloan\Repositories\Feedbacks;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Repositories\FeedbackCategories\FeedbackCategory;
use Whatsloan\Repositories\UserFeedback\UserFeedback;
use Whatsloan\Services\Audits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{

    use Auditable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'feedback', 'category_id'];

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

    public function feedbackcategory()
    {
        return $this->belongsTo(FeedbackCategory::class, 'category_id');
    }

    public function feedbackcategoryTrashed()
    {
        return $this->belongsTo(FeedbackCategory::class, 'category_id')->withTrashed();
    }

    public function user_feedback()
    {
        return $this->hasMany(UserFeedback::class);
    }
}
