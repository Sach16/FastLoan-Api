<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;

use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;

use Whatsloan\Repositories\Feedbacks\Contract as Feedbacks; 
use Whatsloan\Http\Transformers\V1\Consumers\FeedbackTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\FeedbackValidationFailedTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\UserFeedbackTransformer;

use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{



    /**
     * @var $cities
     */
    private $feedbacks;



    /**
     * Feedback controller constructor
     * @param 
     */
    public function __construct(Feedbacks $feedbacks)
    {
         $this->feedbacks = $feedbacks;
    }

    /**
     * Index action
     * @return [type] [description]
     */
    public function index()
    {
       
    }
    
    public function create()
    {
        //        
    }
    public function store(Request $request)
    {
        //
       
    }
    public function show($id)
    {
        //
        
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $uuid)
    {
        
    }
    public function destroy($id)
    {
        //
    }
    public function getFeedbacks(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'category_uuid' => 'required'
        ]);
        if ($validator->fails()) {
             return $this->transformItem($validator->messages(), FeedbackValidationFailedTransformer::class, 400);
        }         
        
       $feedbacks = $this->feedbacks->paginateAsConsumers($request->category_uuid);
       return $this->transformCollection($feedbacks, FeedbackTransformer::class);

    }
   public function submitFeedbacks(Request $request){
       $categoryId = $request->category_uuid;
       $userFeedback = $this->feedbacks->submitFeedback($request);
       if($userFeedback){
          $feedbacks = $this->feedbacks->paginateAsConsumers($categoryId);
          return $this->transformCollection($feedbacks, FeedbackTransformer::class);
       }
   }  
}
