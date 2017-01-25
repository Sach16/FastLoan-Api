<?php

namespace Whatsloan\Repositories\FeedbackCategories;


class Repository implements Contract
{

    /**
     * @var City
     */
    private $feedbackCategory;

    /**
     * feedbackCategory repository constructor
     * @param 
     */
    public function __construct(FeedbackCategory $feedbackCategory)
    {
        $this->feedbackCategory = $feedbackCategory;
    }

    /**
     * Get a single feedbackCategory details
     * @param  string $uuid
     * @return Item
     */
    public function find($uuid)
    {
        
    }

    /**
     * Get a paginated list of feedback category
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->feedbackCategory->paginate();
    }

    /**
     * Update a feedback category as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id)
    {
        $feedback_category = $this->feedbackCategory->withTrashed()->find($id);
        $feedback_category->update($request);

        return $feedback_category;
    }

    /**
     * Store a new feedback category as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request)
    {
        return $this->feedbackCategory->create($request);
    }
    
}
