<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Users\User;

class UserTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = ['settings','address','attachments'];

    /**
     * @var array
     */
    protected $availableIncludes = [];

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
            'documents' => $user->attachments(),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
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
     * Include address
     * @param  Bank $bank
     * @return
     */
    public function includeAddress(User $user)
    {
        if($user->addresses->first() != null){
            return $this->collection($user->addresses, new AddressTransformer);
        }
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
}
