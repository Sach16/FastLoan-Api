<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\Consumers\TypeTransformer;
use Whatsloan\Repositories\Types\Contract as Types;


/**
 * Description of TypeController
 *
 * @author Habid
 */
class TypeController extends Controller {
    //
    private $types;

    public function __construct(Types $types) {
        $this->types = $types;
    }
    
    public function index() {
       
        $types = $this->types->paginate();
        return $this->transformCollection($types, TypeTransformer::class);
    }
    
    
}
