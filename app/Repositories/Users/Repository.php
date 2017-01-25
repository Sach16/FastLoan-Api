<?php

namespace Whatsloan\Repositories\Users;

use Carbon\Carbon;
use Whatsloan\Events\CustomerDocumentWasUpdated;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Loans\Loan;

/**
 * @property User user
 */
class Repository implements Contract
{

    /**
     * User Model
     * @var
     */
    protected $user;

    /**
     * Constructor
     * @param User $user - User Model
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the details of a single user
     *
     * @param $uuid
     * @return mixed
     */
    public function find($uuid)
    {
        return $this->user->with(['teams', 'teams.members', 'tasks', 'tasks.status'])->whereUuid($uuid)->firstOrFail();
    }

    /**
     * Get DSA owner team details
     * @param $uuid
     * @return User
     */
    public function getDsaOwnerTeam($uuid)
    {
        return $this->user
            ->with(['teams'])
        //->onlyMembers()
            ->whereUuid($uuid)
            ->firstOrFail();
    }

    /**
     * Function get DSA member team details
     * @param  string $uuid
     * @return User
     */
    public function getDsaMemberTeam($uuid)
    {
        return $this->user
            ->with(['teams'])
            ->with(['teams.members' => function ($q) use ($uuid) {
                $q->where('uuid', $uuid);
            }])
            ->whereUuid($uuid)
            ->firstOrFail();
    }

    /**
     * Get all details of a user
     *
     * @param $uuid
     * @return mixed
     */
    public function show($uuid)
    {
        return $this->user
            ->with(['addresses.city', 'reportsTo'])
            ->with(['attachments' => function ($query) {
                $query->whereIn('type', ['PRODUCT_DOCUMENT', 'ID_PROOF', 'ADDRESS_PROOF', 'EXPERIENCE_DOCUMENT', 'PROFILE_PICTURE']);
            }])
            ->whereUuid($uuid)
            ->first();
    }
    /**
     * Get Ratinfs of the DSA
     *
     * @param $uuid
     * @return mixed
     */
    public function dsaRating($uuid)
    {
        // return UserFeedback::where('dsa_id', User::whereUuid($uuid)->first()->id)->select(\DB::raw("ROUND(IFNULL(AVG(rating),0),2) as 'dsaRating'"))->first()->dsaRating;
    }

    /**
     * Update the current user
     *
     * @param $request
     * @param $uuid
     * @return mixed
     */
    public function update($request, $uuid)
    {
        $user = $this->user->whereUuid($uuid)->first();
        $user->update($request->all());
        $user->settings()->merge($request->all());
        $user_address = $user->addresses()->where('addressable_id', $user->id)->first();

        if ($request->city_uuid == '') {
            $user_address->update();
        } else {
            $city_id = City::whereUuid($request->city_uuid)->first()->id;
            $user_address->update(["city_id" => $city_id]);
        }
        if ($request->exists('profile_picture')) {
            $upload     = upload($user->getUserProfilePicturePath(), $request->file('profile_picture'));
            $attachment = $user->attachments()->whereType('PROFILE_PICTURE')->first();

            if (empty($attachment)) {
                $attachment = $user->attachments()->whereType('PROFILE_PICTURE')->firstOrNew([
                    'uuid' => uuid(),
                    'type' => 'PROFILE_PICTURE',
                    'uri'  => $upload,
                ]);
                $attachment->save();
            } else {
                $attachment->update([
                    'uri' => $upload,
                ]);
            }
        }

        return $user;
    }

    /**
     * Get user team
     * @return [type] [description]
     */
    public function getTeam($role, $uuid)
    {
        return ($role == 'DSA_MEMBER') ? $this->getDsaMemberTeam($uuid) : $this->getDsaOwnerTeam($uuid);
    }

    /**
     * Get team members of a user
     * @param string $role
     * @param string $uuid
     * @param array $request
     */
    public function getTeamMembers($role, $uuid, $request)
    {

        $user      = $this->getTeam($role, $uuid);
        $memberIds = $user->teams->first()->members->lists('id')->all();

        return $this->user
            ->with(['banks'])
            ->whereHas('banks', function ($query) use ($request) {
                if (isset($request['bank_uuid'])) {
                    $query->whereuuid($request['bank_uuid']);
                }
            })
            ->orderByRaw("FIELD(role , 'DSA_OWNER', 'DSA_MEMBER') ASC")
            ->whereIn('id', $memberIds)->paginate();
    }

    /**
     * Get member ids
     * @return array
     */
    public function getTeamMemberIds()
    {
        $authUser = \Auth::guard('api')->user();

        if ($authUser->role == 'DSA_MEMBER') {
            return [$authUser->id];
        }

        $user = $this->getTeam($authUser->role, $authUser->uuid);
        return $user->teams->first()->members->lists('id')->all();
    }

    /**
     * Show customer details
     * @param type $uuid
     * @return type
     */
    public function showCustomer($uuid)
    {
        return $this->user->with(['loans' => function($q){
                $q->whereIn('loans.agent_id',teamMemberIds(true));
            }])
            // ->with([ 'loans.agent', 'loans.agent.banks', 'loans.history', 'loans.total_tat', 'attachments', 'loans.agent.teams'])
            // ->with(['loans.history' => function ($query) {
            //     $query->OrderBy('updated_at', 'ASC');
            // }])
            // ->with(['loans.attachments' => function ($query) {
            //     $query->whereType('LOAN_DOCUMENT');
            // }])
            ->whereUuid($uuid)->first();
    }

    /**
     * Paginated list of customers
     * @param int $paginate
     */
    public function customers($paginate = 15)
    {
        $memberIds  = $this->getTeamMemberIds();
        $cutomerIds = Loan::whereIn('agent_id', $memberIds)->get()->lists('user_id')->all();

        return $this->user
            ->whereRole('CONSUMER')
            ->whereIn('id', $cutomerIds)
            ->orderBy('deleted_at')
            ->paginate();
    }

    /**
     * Paginated list of customers
     * @param int $paginate
     */
    public function customersAsAdmin()
    {
        $user       = $this->getTeam(\Auth::user()->role, \Auth::user()->uuid);
        $memberIds  = $user->teams->first()->members->lists('id')->all();
        $cutomerIds = Loan::whereIn('agent_id', $memberIds)->withTrashed()->get()->lists('user_id')->all();

        return $this->user
            ->whereRole('CONSUMER')
            ->whereIn('id', $cutomerIds)
            ->withTrashed()
            ->orderBy('deleted_at','asc')
            ->paginate();
    }

    /**
     * authorized list of customers
     * @param int $paginate
     */
    public function authorizedCustomersAsAdmin($id)
    {
        $user       = $this->getTeam(\Auth::user()->role, \Auth::user()->uuid);
        $memberIds  = $user->teams->first()->members->lists('id')->all();
        $cutomerIds = Loan::whereIn('agent_id', $memberIds)->withTrashed()->get()->lists('user_id')->all();

        if(!in_array($id,$cutomerIds)){
            return true;
        }
        return false;
    }

    /**
     * Get Team member Id's of the User
     *
     * @param $request
     * @param $uuid
     * @return Ids in array
     */
    public function getMemberIds($uuid)
    {
        $user = $this->user
            ->with(['teams.members'])
            ->whereUuid($uuid)
            ->first();
        return $user->teams->first()->members->lists('id')->all();
    }

    /**
     * Get Team member Id's of the User
     *
     * @param $request
     * @param $uuid
     * @return Ids in array
     */
    public function getTaskIds($uuid)
    {
        $user = $this->user
            ->with(['tasks'])
            ->whereUuid($uuid)
            ->first();
        return $user->tasks->first()->lists('id')->all();
    }

    /**
     * Get Team Id of the User
     * @param $uuid
     */
    public function getTeamId($uuid)
    {
        $user = $this->user
            ->with(['teams'])
            ->whereUuid($uuid)
            ->first();
        return $user->teams->first();
    }

    /**
     * Update the user profile as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id)
    {
        $user = $this->user->withTrashed()->find($id);
        $user->update([
            'first_name' => $request['first_name'],
            'last_name'  => $request['last_name'],
            'email'      => $request['email'],
            'phone'      => $request['phone'],
        ]);
        $user->addresses()->update([
            "alpha_street" => (isset($request["alphaStreet"])) ? $request["alphaStreet"] : "",
            "beta_street"  => (isset($request["betaStreet"])) ? $request["betaStreet"] : "",
            "city_id"      => (isset($request["city_id"])) ? $request["city_id"] : "",
            "state"        => (isset($request["state"])) ? $request["state"] : "",
            "country"      => (isset($request["country"])) ? $request["country"] : "",
            "zip"          => (isset($request["zipcode"])) ? $request["zipcode"] : "",
        ]);

        if (isset($request['settings']['dob']) && !empty($request['settings']['dob'])) {
            $request['settings']['age'] = Carbon::parse($request['settings']['dob'])->diff(Carbon::now())->format('%y');
            $request['settings']['dob'] = Carbon::parse($request['settings']['dob'])->format('Y-m-d');

        }
        if (isset($request['settings']['DOJ'])) {
            $request['settings']['DOJ'] = Carbon::parse($request['settings']['DOJ'])->format('Y-m-d');
        }
        $user->settings()->merge($request['settings']);
        if (isset($request["bank"])) {
            $user->banks()->sync([$request["bank"]]);
        }

        return $user;
    }

    /**
     * Store a new user as admin
     *
     * @param $request
     * @return mixed
     */
    public function storeAsAdmin($request)
    {

        return \DB::transaction(function () use ($request) {
            $user = $this->user->create($request);

            if (isset($request['settings']['dob']) && !empty($request['settings']['dob'])) {
                $request['settings']['age'] = Carbon::parse($request['settings']['dob'])->diff(Carbon::now())->format('%y');
                $request['settings']['dob'] = Carbon::parse($request['settings']['dob'])->format('Y-m-d');

            }
            if (isset($request['settings']['DOJ'])) {
                $request['settings']['DOJ'] = Carbon::parse($request['settings']['DOJ'])->format('Y-m-d');
            }
            $user->settings()->merge($request['settings']);
            $user->addresses()->create([
                "uuid"         => uuid(),
                "alpha_street" => (isset($request["alphaStreet"])) ? $request["alphaStreet"] : "",
                "beta_street"  => (isset($request["betaStreet"])) ? $request["betaStreet"] : "",
                "city_id"      => (isset($request["city_id"])) ? $request["city_id"] : "",
                "state"        => (isset($request["state"])) ? $request["state"] : "",
                "country"      => (isset($request["country"])) ? $request["country"] : "",
                "zip"          => (isset($request["zipcode"])) ? $request["zipcode"] : "",
            ]);

            if (isset($request["bank"])) {
                $user->banks()->attach($request["bank"]);
            }
            return $user;
        });
    }

    /**
     * Update a customer document
     *
     * @param array $request
     * @param $customerId
     * @param $documentId
     * @return mixed
     */
    public function updateDocumentAsAdmin(array $request, $customerId, $documentId)
    {
        $customer = $this->user->find($customerId);
        $document = $customer->attachments()->whereId($documentId)->first();

        $document->update($request);
        event(new CustomerDocumentWasUpdated($customer, $document));

        return $document;
    }

    /**
     * Store a customer document
     *
     * @param array $request
     * @param $customerId
     * @return mixed
     */
    public function storeDocumentAsAdmin(array $request, $customerId)
    {
        $customer = $this->user->customers()->find($customerId);
        $document = $customer->attachments()->create($request);

        event(new CustomerDocumentWasUpdated($customer, $document));

        return $document;
    }

    /**
     * Store a customer document
     *
     * @param array $request
     * @param $customerId
     * @return mixed
     */
    public function storeCustomerDocument(array $request, $customerId)
    {
        $customer = User::where('id', $customerId)->first();
        $document = $customer->attachments()->create($request);

        event(new CustomerDocumentWasUpdated($customer, $document));

        return $document;
    }

    /**
     * Update the customer
     *
     * @param $request
     * @param $uuid
     * @return mixed
     */
    public function updateCustomer($request, $uuid)
    {
        $user = $this->user->whereUuid($uuid)->first();
        $user->update($request->all());
        $user->settings()->merge($request->all());
        $user_address = $user->addresses()->where('addressable_id', $user->id)->first();
        $city_id      = City::whereUuid($request->city_uuid)->first()->id;
        $user_address->update(["city_id" => $city_id]);

        return $user;
    }

    /**
     * Transfer a phone number as admin
     *
     * @param array $request
     * @return mixed
     */
    public function transferPhoneAsAdmin(array $request)
    {
        $old        = $this->user->wherePhone($request['phone'])->withTrashed()->first();
        $new        = $this->user->whereId($request['user_id'])->first();
        $old_phone  = $old->phone;
        $new_phone = $new->phone;

        $old->phone = $old->phone . '__TRANSFERRED__' . $new->phone;
        $old->save();

        $this->user->whereId($request['user_id'])->update(['phone' => $request['phone']]);

        $old->phone = $new_phone;
        $old->save();

    }

    /**
     * set the user login credentials
     *
     * @param $id
     * @return mixed
     */
    public function setCredentials($id)
    {
        $user           = $this->user->find($id);
        $user->password = bcrypt('Qwerty123');
        $user->save();
        return $user;
    }

    public function getUserLoans()
    {
        $loans        = Loan::whereIn('agent_id', teamMemberIds(true))->first();
        $user         = new User();
        $user->status = (!empty($loans) ? true : false);
        return $user;
    }

    public function getTeamLoans()
    {
        return $loans = Loan::whereIn('agent_id', teamMemberIds(true))->get();
    }

    /**
     * Store Payout % for the Builder Project
     */
    public function StorePayoutAsAdmin(array $request)
    {
        $user = $this->user->find($request['user_id']);

        $user->payouts()->create([
            "uuid"       => uuid(),
            "percentage" => $request["percentage"],
        ]);

        return $user;
    }

    /**
     * Update Payout % for the Builder Project
     */
    public function UpdatePayoutAsAdmin(array $request)
    {
        $user = $this->user->find($request['user_id']);

        $user->payouts()->update([
            "percentage"        => $request["percentage"],
            "total_paid_amount" => $request["total_paid_amount"],
        ]);

        return $user;
    }

    public function trackLists()
    {
        return $tracking_status = TrackUser::get();
    }

    public function enableTrackingStatus($request, $userId)
    {
        $data['track_status'] = $request['track_status'];
        $user                 = $this->user->whereUuid($userId)->first();
        $user->update($data);
        return $user;
    }
}
