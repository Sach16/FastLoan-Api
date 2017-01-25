<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Attendances\Attendance;
use Whatsloan\Repositories\Users\User;

class UserTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'tasks',
        'taskStatusCount',
        'settings',
        'attendances',
        'loans',
        'addresses',
        'attachments',
        'banks',
        'designation',
        'track_user',
        'reports_to',
        'rating',
    ];

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {

        return [
            'uuid'       => $user->uuid,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'role'       => $user->role,
            'token'      => $user->api_token,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    /**
     * Include Tasks
     * @param User $user
     * @return Collection
     */
    public function includeTasks(User $user)
    {
        return $this->collection($user->tasks, new TaskTransformer);
    }

    /**
     * Include Tasks
     * @param User $user
     * @return collection
     */
    public function includeTaskStatusCount(User $user)
    {
        $taskStatusCount = [];
        $slug            = [];

        foreach ($user->tasks as $task) {

            if (isset($task->status->key) && !isset($taskStatusCount[$task->status->key])) {

                $taskStatusCount[$task->status->key] = [
                    'count'  => 0,
                    'status' => $task->status,
                ];
                $slug[] = $task->status->label;

            }

            $taskStatusCount[$task->status->key]['count'] += 1;

        }

        return $this->item(array_values($taskStatusCount), new TaskStatusCountTransformer);
    }

    /**
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeSettings(User $user)
    {
        $settings = is_array($user->settings) ? $user->settings : [];
        return $this->item($settings, new UserSettingsTransformer);
    }

    /**
     * Include Attendances
     * @param User $user
     * @return collection
     */
    public function includeAttendances(User $user)
    {
        return $this->collection($user->attendances, new AttendanceTransformer);
    }

    /**
     * Include Loans
     * @param User $user
     * @return collection
     */
    public function includeLoans(User $user)
    {
        return $this->collection($user->loans, new LoanTransformer);
    }

    /**
     * Include User Address
     * @param User $user
     * @return type
     */
    public function includeAddresses(User $user)
    {
        if ($user->addresses->isEmpty()) {
            return $this->collection($user->addresses, new AddressTransformer);
        }
        return $this->item($user->addresses->first(), new AddressTransformer);

    }

    /**
     * Include User Attachments
     * @param User $user
     * @return type
     */
    public function includeAttachments(User $user)
    {
        return $this->collection($user->attachments, new AttachmentTransformer);
    }

    /**
     * Include User Banks
     * @param User $user
     * @return type
     */
    public function includeBanks(User $user)
    {
        if ($user->banks->isEmpty()) {
            return $this->collection($user->banks, new BankTransformer);
        }
        return $this->item($user->banks->first(), new BankTransformer);
    }

    /**
     * Include User Banks
     * @param User $user
     * @return type
     */
    public function includeDesignation(User $user)
    {
        return $this->item($user->designation, new DesignationTransformer);
    }

    /**
     * Include User Track status
     * @param User $user
     * @return type
     */
    public function includeTrackUser(User $user)
    {
        return $this->item($user->track_user, new TrackUserTransformer);
    }

    /**
     * Include User Reports To
     * @param User $user
     * @return type
     */
    public function includeReportsTo(User $user)
    {
        return $this->collection($user->reportsTo, new UserReportsToTransformer);
    }

    public function includeRating(User $user)
    {
        return $this->item($user, new RatingTransformer);
    }

}
