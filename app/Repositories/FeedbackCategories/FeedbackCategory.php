<?php

namespace Whatsloan\Repositories\FeedbackCategories;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\Audits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Repositories\Feedbacks\Feedback;

class FeedbackCategory extends Model
{
    use Auditable, SoftDeletes;
    /**
     * Fillable fields on the model
     *
     * @var array
     */
    protected $fillable = ['uuid','name','key'];

	public function feedbackQuestions()
    {
        return $this->hasMany(Feedback::class,'category_id');
    }
}
