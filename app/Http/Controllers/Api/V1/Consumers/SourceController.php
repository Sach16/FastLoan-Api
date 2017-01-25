<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\Consumers\SourceTransformer;
use Whatsloan\Repositories\Sources\Contract as Sources;

class SourceController extends Controller {

    /**
     * @var $
     */
    private $sources;

    /**
     * Source controller constructor
     * @param 
     */
    public function __construct(Sources $sources) {
        $this->sources = $sources;
    }

    /**
     * Index action
     * @return [type] [description]
     */
    public function index() {
        $sources = $this->sources->paginate();
        return $this->transformCollection($sources, SourceTransformer::class);
    }

}
