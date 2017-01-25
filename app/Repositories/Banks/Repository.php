<?php

namespace Whatsloan\Repositories\Banks;

use Carbon\Carbon;
use Uuid;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Products\Product;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Users\Contract as Users;
use Whatsloan\Repositories\Users\User;

class Repository implements Contract
{

    /**
     * Bank Model
     * @var bank
     */
    private $bank;

    /**
     * User Model
     * @var user
     */
    private $user;

    /**
     * Bank repository contructor
     * @param Banks $bank
     */
    public function __construct(Bank $bank, Users $user)
    {
        $this->bank = $bank;
        $this->user = $user;
    }

    /**
     * Get a single bank details
     * @param  string $uuid
     * @return Item
     */
    public function find($uuid)
    {
        return $this->bank->whereUuid($uuid)->firstOrFail();
    }

    /**
     * Get paginated list of  banks
     * @param  string $uuid
     * @return Item
     */
    public function paginate($params, $limit = 15)
    {

        $bank = $this->bank->with(['addresses.city', 'projects', 'users'])->whereHas('users.addresses.city', function ($query) use ($params) {
            if (isset($params['city_uuid'])) {
                $authUser = \Auth::guard('api')->user();
                $city     = (new City())->whereuuid($params['city_uuid'])->first();
                if ($authUser->role == 'DSA_OWNER') {
                    $user_ids = $this->user->getMemberIds($authUser->uuid);
                    $users    = User::with(['addresses.city' => function ($q) use ($params) {
                        $q->where('cities.uuid', $params['city_uuid']);
                        $q->select(['cities.id as city_id,cities.uuid as city_uuid']);
                    }])->whereIn('id', $user_ids)->get();
                    foreach ($users as $user) {
                        if (isset($user->addresses->first()->city_id)) {
                            $city_ids[] = $user->addresses->first()->city_id;
                        }
                    }
                    $query->whereIn('city_id', [implode(", ", $city_ids)]);
                } else {
                    $query->where('users.id', $authUser->id);
                }
            }
        })->orderBy('updated_at', 'DESC');
        if (isset(request()->paginate) && request()->paginate == 'all') {
            return $bank->get();
        }

        return $bank->paginate();
    }

    /**
     * Store a new bank
     *
     * @param $request
     * @return mixed
     */
    public function store($request)
    {
        return $this->bank->create([
            'uuid'      => Uuid::generate()->string,
            'name'      => $request->bankName,
            'branch'    => $request->branch,
            'ifsc_code' => $request->ifsc,
        ]);
    }

    /**
     * Update an existing bank
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update($id, $request)
    {
        $bank = $this->bank->withTrashed()->find($id);
        $bank->update($request);
        return $bank;
    }

    /**
     * Update project approval status for bank
     *
     * @param $request
     * @param $bankId
     * @param $projectId
     * @return mixed
     */
    public function updateProjectApproval($request, $bankId, $projectId)
    {
        BankProject::where('bank_id', $bankId)
            ->where('project_id', $projectId)
            ->update(['status' => 'PENDING']);
        return BankProject::where('bank_id', $bankId)
            ->where('project_id', $projectId)
            ->where('agent_id', $request['agent_id'])
            ->update(['status' => $request['status'], 'approved_date' => Carbon::now()->toDateTimeString()]);
    }

    /**
     * Update project bank details
     *
     * @param $request
     * @param $bankId
     * @param $projectId
     * @return mixed
     */
    public function updateBankProject($data, $bankId, $projectId)
    {
        $bank = $this->bank->with('projects')->find($bankId);
        $bank->projects()->updateExistingPivot($projectId, $data);
        return $bank;
    }

    /**
     * Store a new bank document
     *
     * @param $id
     * @param array $request
     * @return mixed
     */
    public function storeBankDocument($id, array $request)
    {
        $bank       = $this->bank->where('id', $id)->first();
        $attachment = new Attachment([
            'uuid'        => uuid(),
            'name'        => $request['name'],
            'description' => strip_tags($request['description']),
            'type'        => 'CHECKLIST',
            'uri'         => $request['uri'],
        ]);

        return $bank->attachments()->save($attachment);
    }

    /**
     * Update a new bank document
     *
     * @param $id
     * @param $documentId
     * @param array $request
     * @return mixed
     */
    public function updateBankDocument($id, $documentId, array $request)
    {
        $bank = $this->bank->with(['attachments' => function ($query) use ($documentId) {
            $query->whereId($documentId)->take(1);
        }])->where('id', $id)->first();

        return $bank->attachments->first()->update($request);
    }

    /**
     * Get the bank list of team members
     * @return type
     */
    public function membersBanks($paginate = 100)
    {

        $authUser = \Auth::guard('api')->user();
        $user     = User::with(['teams', 'teams.members', 'teams.members.banks'])->find($authUser->id);

        $members = $user->teams->first()->members;

        $bankIds = [];
        foreach ($members as $member) {
            if ($member->banks->first()) {
                $bankIds[] = $member->banks->first()->id;
            }
        }

        return Bank::with(['addresses.city'])
            ->whereHas('addresses', function ($query) {
                if (isset(request()->city_uuid)) {
                    $query->where('city_id', City::whereUuid(request()->city_uuid)->first()->id);
                }
            })
            ->whereIn('id', $bankIds)
            ->get();
    }

    public function getBankDocuments()
    {
        return $this->bank->with(['attachments'])
            ->with(['attachments' => function ($query) {
                $query->whereType('PRODUCT_DOCUMENT');
            }])
            ->whereHas('attachments', function ($query) {
                $query->whereType('PRODUCT_DOCUMENT');
            })
            ->paginate();
    }

    /**
     * Get bank approval requests
     * @param string $status
     * @return Collection
     */
    public function approvalRequests($status)
    {

        $prj = BankProject::with([
            'bank',
            'project.queries' => function ($query) {
                $query->orderBy('start_date', 'ASC');
                $query->orderBy('end_date', 'ASC');
            }])
            ->whereHas('bank', function ($query) {
                if (isset(request()->bank_uuid)) {
                    $query->whereUuid(request()->bank_uuid);
                }
            })
            ->whereHas('project.addresses.city', function ($query) {
                if (isset(request()->city_uuid)) {
                    $query->whereUuid(request()->city_uuid);
                }
            })
            ->whereHas('project', function ($query) {
                if (isset(request()->project_uuid)) {
                    $query->whereUuid(request()->project_uuid);
                }
            })
            ->whereStatus($status);
        if (isset(request()->agent_uuid)) {
            $agent_id = User::whereUuid(request()->agent_uuid)->lists('id')->all();
            $prj->whereIn('agent_id', $agent_id);
        } else {
            $prj->whereIn('agent_id', teamMemberIds(true));
        }

        if (request()->paginate == 'all') {
            return $prj->get();
        } else {
            return $prj->paginate();
        }

    }

    /**
     * List of banks which is approved the projects
     * @return type
     */
    public function approvedBy()
    {

        $bankIds = BankProject::select(\DB::raw('distinct(bank_id) as bank_id'))
            ->with(['bank.attachments', 'project'])
            ->whereHas('bank.addresses.city', function ($query) {
                if (isset(request()->city_uuid)) {
                    $query->whereUuid(request()->city_uuid);
                }
            })
            ->whereHas('project', function ($query) {
                if (isset(request()->project_uuid)) {
                    $query->whereUuid(request()->project_uuid);
                }
            })
            ->whereStatus('APPROVED')
            ->whereIn('agent_id', teamMemberIds(true))
            ->get()->lists('bank_id')->all();

        return $this->bank
            ->whereIn('id', $bankIds)
            ->paginate();
    }

    /**
     * Getting Bank Product Documents
     * @return type
     */
    public function documentFilters()
    {
        return BankProduct::with([
            'bank',
            'bank.addresses.city',
            'attachments',
        ])
            ->whereHas('bank', function ($query) {
                if (isset(request()->bank_uuid)) {
                    $query->whereUuid(request()->bank_uuid);
                }
            })
            ->whereHas('bank.addresses.city', function ($query) {
                if (isset(request()->city_uuid)) {
                    $query->where('city_id', City::whereUuid(request()->city_uuid)->first()->id);
                }
            })
            ->whereHas('attachments', function ($query) {
                $query->whereType(Type::whereUuid(request()->type_uuid)->first()->key);
            })
            ->where('product_id', Type::whereUuid(request()->type_uuid)->first()->id)
            ->paginate();

    }

    public function getBankProducts()
    {
        $productIds      = BankProduct::where('bank_id', Bank::whereUuid(request()->bank_uuid)->first()->id)->distinct()->lists('product_id')->all();
        return $products = Type::whereIn('id', $productIds)->get();
    }
    public function paginateAsConsumers($params, $limit = 15)
    {

        $bank = $this->bank->with(['addresses.city', 'projects'])->whereHas('addresses.city', function ($query) use ($params) {
            if (isset($params['city_uuid'])) {
                $city = (new City())->whereuuid($params['city_uuid'])->first();
                $query->where('city_id', $city->id);
            }
        });
        if (isset(request()->paginate) && request()->paginate == 'all') {
            return $bank->get();
        }

        return $bank->paginate();
    }
}
