<?php

namespace Whatsloan\Repositories\Feedbacks;

use Whatsloan\Repositories\FeedbackCategories\FeedbackCategory;
use Whatsloan\Repositories\UserFeedback\UserFeedback;

class Repository implements Contract
{

    /**
     * @var City
     */
    private $feedback;

    /**
     * City repository constructor
     * @param City $city
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Get a paginated list of
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($categoryId, $limit = 15)
    {

    }
    public function paginateAsConsumers($categoryId, $limit = 15)
    {
        $feedbackCategory = null;
        if ($categoryId != '') {
            $feedbackCategory = FeedbackCategory::whereUuid($categoryId)->first();
        }
        return $this->feedback->with(['user_feedback' => function ($q) {
            $q->where('user_id', \Auth::guard('api')->user()->id);
        }])->whereCategory_id($feedbackCategory->id)->paginate($limit);
    }

    public function submitFeedback($request)
    {

        return \DB::transaction(function () use ($request) {
            foreach ($request->all() as $feedbackUuid => $rating) {
                if ($feedbackUuid != 'category_uuid') {
                    $feedbacks     = $this->feedback->whereUuid($feedbackUuid)->firstOrFail();
                    $user          = \Auth::guard('api')->user();
                    $userFeedBacks = UserFeedback::whereFeedback_id($feedbacks->id)->whereUser_id(\Auth::guard('api')->user()->id)->first();
                    if (isset($userFeedBacks->uuid)) {
                        $userFeedBacks->rating = $rating;
                        $userFeedBacks->save();
                    } else {
                        $userFeedBacks = UserFeedback::create([
                            'uuid'        => uuid(),
                            'user_id'     => $user->id,
                            'feedback_id' => $feedbacks->id,
                            'rating'      => $rating,
                        ]);
                    }
                }
            }
            return $userFeedBacks;
        });
    }
    /**
     * storeAsAdmin create new record
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function storeAsAdmin(array $data)
    {
        $this->feedback->create([
            'uuid'        => $data['uuid'],
            'feedback'    => $data['question'],
            'category_id' => $data['category'],
        ]);
    }

    /**
     * updateAsAdmin update Question record
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function updateAsAdmin($id, array $data)
    {
        $this->feedback->where('id', $id)->update([
            'feedback'    => $data['question'],
            'category_id' => $data['category'],
        ]);
    }

}
