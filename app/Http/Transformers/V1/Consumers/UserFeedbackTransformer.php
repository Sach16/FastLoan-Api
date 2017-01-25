<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;

use Whatsloan\Repositories\UserFeedback\UserFeedback;

class UserFeedbackTransformer extends TransformerAbstract
{

    /**
     * @param $e
     * @return array
     */
    public function transform(UserFeedback $feedback)
    {
        return [
            'uuid'      => $feedback->uuid,
            'rating'      => $feedback->rating
        ];
    }
}
