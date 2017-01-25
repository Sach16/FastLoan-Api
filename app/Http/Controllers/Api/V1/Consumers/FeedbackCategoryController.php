<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;

use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;

use Whatsloan\Repositories\FeedbackCategories\Contract as FeedbackCategories;
use Whatsloan\Http\Transformers\V1\Consumers\FeedbackCategoryTransformer ;

class FeedbackCategoryController extends Controller
{



    /**
     * @var $cities
     */
    private $feedback_category;



    /**
     * City controller constructor
     * @param 
     */
    public function __construct(FeedbackCategories $feedback_category)
    {
         $this->feedback_category = $feedback_category;
    }


    /**
     * Index action
     * @return [type] [description]
     */
    public function index()
    {
       $feedbackCategories = $this->feedback_category->paginate();
       return $this->transformCollection($feedbackCategories, FeedbackCategoryTransformer::class);
    }
    
    
}
