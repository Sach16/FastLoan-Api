<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Feedbacks\Feedback;
use Whatsloan\Http\Transformers\V1\Consumers\UserFeedbackTransformer;

class FeedbackTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = ['rating'];

    /**
     * @var array
     */
    protected $availableIncludes = [];

    
    /**
     * @param $e
     * @return array
     */
    public function transform(Feedback $feedback)
    {
        return [
            'uuid'      => $feedback->uuid,
            'feedback'      => $feedback->feedback,
//            'category_id'  => $feedback->category_id
        ];
    }
    
    public function includeRating(Feedback $feedback)
    {
        return $this->collection($feedback->user_feedback, new UserFeedbackTransformer);   
    }
}
