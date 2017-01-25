<?php

namespace Whatsloan\Repositories\Projects;

use Illuminate\Http\Request;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Events\ProjectImageWasUpdated;
use Uuid;
use Carbon\Carbon;
use Whatsloan\Repositories\Banks\BankProject;

class Repository implements Contract
{

    /**
     * Project Model object
     * @var $project
     */
    private $project;

    /**
     * Project repository constructor
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Paginated approved projects
     * @param  integer $limit
     * @return Collection
     */
    public function approved($params, $limit = 15)
    {

        $teamMemberIds[] = authUser()->id;
        if (authUser()->role == 'DSA_OWNER') {
            $userTeam = User::with(['teams', 'teams.members'])->find(authUser()->id)->teams()->first();
            $teamMemberIds = $userTeam->members->lists('id')->all();
        }

        $prj =  $this->project
                        ->with([
                            'units',
                            'builder',
                            'status',
                            'addresses',
                            'addresses.city',
                            'banks' => function($query) use($teamMemberIds) {
                                $query->whereStatus('APPROVED');
                                $query->whereIn('agent_id', $teamMemberIds);
                            },
                            'banks.addresses',
                            'banks.addresses.city'
                        ])->whereHas('addresses.city', function($query) use($params) {
                            if (isset($params['city_uuid'])) {
                                $query->whereUuid($params['city_uuid']);
                            }
                        })->whereHas('banks', function($query) use($params, $teamMemberIds) {
                            $query->whereStatus('APPROVED');
                            $query->whereIn('agent_id', $teamMemberIds);

                            if (isset($params['bank_uuid'])) {
                                $query->whereUuid($params['bank_uuid']);
                            }
                        });
                        $query->orderBy('updated_at','DESC');
                        if(request()->paginate == 'all') {
                           return $prj->get();
                        } else {
                             return $prj->paginate($limit);

                        }
    }

    /**
     * Get the details of a single project
     *
     * @param $uuid
     * @return mixed
     */
    public function find($uuid)
    {
        return $this->project
                        ->with([
                            'units',
                            'builder',
                            'addresses',
                            'banks',
                            'owner',
                            'assignee',
                            'queries'
                        ])
                        ->whereUuid($uuid)
                        ->firstOrFail();
    }

    public function show($uuid)
    {
        return $this->project
                        ->with([
                            'units',
                            'builder',
                            'addresses',
                            'banks' => function($query) {
                                $query->where('agent_id', request()->agent_id);
                                $query->where('bank_id', request()->bank_id);
                            },
                            'owner',
                            'assignee',
                            'queries'
                        ])
                        ->whereUuid($uuid)
                        ->firstOrFail();
    }

    /**
     * Add new project
     * @param  array $data
     * @return
     */
    public function store($data)
    {

        $project = new Project();

        \DB::transaction(function () use (&$data, &$project) {

            $city = (new City())->whereuuid($data['city_uuid'])->first();
            $assignee = (new User())->whereuuid($data['assignee'])->first();
            $bank = (new Bank())->whereuuid($data['bank_uuid'])->first();
            $builder = (new Builder())->whereuuid($data['builder_uuid'])->first();

            $project->uuid = \Webpatser\Uuid\Uuid::generate()->string;
            $project->name = $data['name'];
            $project->status_id = 1;
            $project->builder_id = $builder->id;
            $project->owner_id = \Auth::guard('api')->user()->id;
            $project->unit_details = $data['unit_details'];
            $project->possession_date = $data['possession_date'];
            $project->save();

//            foreach($data['unit_details'] as $unit) {
//                $project->units()->create([
//                    'uuid'   => uuid(),
//                    'number' => $unit,
//                    'project_id' => $project->id
//                ]);
//            }

            $address = new Address();
            $address->uuid = \Webpatser\Uuid\Uuid::generate()->string;
            $address->alpha_street = $data['street'];
            $address->city_id = $city->id;
            $project->addresses()->save($address);

            $project->banks()->attach($bank->id, ['status' => 'PENDING', 'agent_id' => $assignee->id]);
        });


        return $this->project
                        ->with(['banks', 'addresses'])
                        ->find($project->id);
    }

    /**
     * Projects to be approved
     */
    public function toBeApproved()
    {
        return $this->project->with(['banks' => function($query) {
                        $query->whereStatus('PENDING');
                    }])->paginate();
    }

    /**
     * Checkes whether project and bank already associated
     * @param integer $agentId
     * @param string $bankUuid
     * @param string $projectUuid
     * @return boolean
     */
    public function isBankAssociated($agentId, $bankUuid, $projectUuid)
    {

        $bank = (new Bank())->whereuuid($bankUuid)->first();

        $project = $this->project
                        ->with(['banks'])
                        ->whereUuid($projectUuid)->first();

        if (!$project->banks) {
            return false;
        }

        foreach ($project->banks as $pbank) {
            if ($pbank->pivot->agent_id == $agentId && $project->uuid == $projectUuid && $pbank->id == $bank->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a new project as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request)
    {
        $project = $this->project->create([
            'uuid' => uuid(),
            'name' => $request['project'],
            'builder_id' => $request['builder_id'],
            'owner_id' => $request['owner_id'],
            //'assigned_to' => $request['owner_id'],
            'unit_details' => $request['units'],
            'possession_date' => $request['possession'],
            'status_id' => $request['status_id'],
        ]);
        $project->addresses()->create([
            "uuid" => uuid(),
            "alpha_street" => $request["alphaStreet"],
            "beta_street" => $request["betaStreet"],
            "city_id" => $request["city"],
            "state" => $request["state"],
            "country" => $request["country"],
            "zip" => $request["zip"],
        ]);

        $user = User::with('banks')->whereId($request['owner_id'])->first();
        BankProject::create([
                'status' => 'PENDING',
                'bank_id' => $user->banks()->first()->id,
                'project_id' => $project->id,
                'agent_id' => $user->id,
            ]);

        return $project;
    }

    /**
     * Update an existing project
     *
     * @param array $request
     * @return mixed
     */
    public function updateAsAdmin(array $request)
    {
        $project = $this->project->withTrashed()->find($request['project_id']);
        $project->update([
            'name' => $request['project'],
            'unit_details' => $request['units'],
            'possession_date' => $request['possession'],
            'status_id' => $request['status_id'],
        ]);
        $project->addresses()->update([
            "alpha_street" => $request["alphaStreet"],
            "beta_street" => $request["betaStreet"],
            "city_id" => $request["city"],
            "state" => $request["state"],
            "country" => $request["country"],
            "zip" => $request["zip"],
        ]);

        return $project;
    }

    /**
     * Update a single project
     * @param string $uuid
     * @param array $data
     */
    public function update($uuid, $data)
    {
        $project = $this->project->whereId($uuid)->first();
        $data['city_id'] = City::whereUuid($data['city_uuid'])->first()->id;        
        $project->update($data);
        $project->addresses()->update(['city_id' => $data['city_id'], 'alpha_street' => $data['street']]);
        return $project;
    }

    public function getProjectsLists($data)
    {
        return $projects = $this->project
                                ->with(['builder'])
                                ->whereHas('builder', function($query) use ($data) {
                                    if (isset($data['builder_uuid'])) {
                                        $builder = (new Builder())->whereuuid($data['builder_uuid'])->first();
                                        $query->where('builder_id', $builder->id);
                                    }
                                })->get();
    }

    /**
    * Store Payout % for the Builder Project 
    */
    public function StorePayoutAsAdmin(array $request)
    {
        $project = $this->project->find($request['project_id']);

        $project->payouts()->create([
                "uuid" => uuid(),
                "percentage" => $request["percentage"],
            ]);

        return $project;
    }

    /**
    * Update Payout % for the Builder Project 
    */
    public function UpdatePayoutAsAdmin(array $request)
    {
        $project = $this->project->find($request['project_id']);

        $project->payouts()->update([
                "percentage" => $request["percentage"],
            ]);

        return $project;
    }
}
