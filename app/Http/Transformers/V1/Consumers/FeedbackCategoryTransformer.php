<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;

use Whatsloan\Repositories\FeedbackCategories\FeedbackCategory;

class FeedbackCategoryTransformer extends TransformerAbstract
{

    /**
     * @param $e
     * @return array
     */
    public function transform(FeedbackCategory $feedbackCategory)
    {
        return [
            'uuid'      => $feedbackCategory->uuid,
            'name'      => $feedbackCategory->name,
            'key'  => $feedbackCategory->key,
        ];
    }
}
