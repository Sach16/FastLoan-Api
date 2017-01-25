<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Requests;

use Whatsloan\Http\Controllers\Controller;

use Whatsloan\Repositories\Homes\Contract as Homes;
use Whatsloan\Http\Transformers\V1\HomeTeamTransformer;
use Whatsloan\Http\Transformers\V1\TrackUserTransformer;
use Whatsloan\Http\Transformers\V1\TeamTransformer;
use Whatsloan\Http\Transformers\V1\LeadTransformer;
use Whatsloan\Http\Transformers\V1\CountTransformer;
use Whatsloan\Http\Transformers\V1\HomeTransformer;

class HomeController extends Controller
{

    /**
     * [$home description]
     * @var [type]
     */
    private $home;

    /**
     * [__construct description]
     * @param Homes $home [description]
     */
    public function __construct(Homes $home)
    {
        $this->home = $home;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $team = $this->transformCollection($this->home->getTeam(), TeamTransformer::class);
        $leadCount = $this->transformItem(['name' => 'lead','count' => $this->home->getLeadCount()], CountTransformer::class);
        $customerCount = $this->transformItem(['name' => 'customer','count' => $this->home->getCustomerCount()], CountTransformer::class);
        $tracking_status = $this->transformItem($this->home->trackingStatus(), TrackUserTransformer::class);
     
        return $this->transformMultiple([ 'team' => $team,'leadCount' => $leadCount, 'customerCount' => $customerCount, 'tracking_status' => $tracking_status], HomeTransformer::class);
    }
}
