<?php

namespace Whatsloan\Services\AccessControl;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Collection;
use Whatsloan\Repositories\Users\User;

class AccessControlScope implements Scope
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Collection
     */
    private $members;

    /**
     * AccessControlScope constructor.
     * @param User $user
     * @param Collection $members
     */
    public function __construct(User $user, Collection $members)
    {
        $this->user = $user;
        $this->members = $members;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        switch ($this->user->role) {
            case 'DSA_MEMBER':
                $where = $builder->where('user_id', $this->user->id);
                break;
            case 'DSA_OWNER':
                $where = $builder->whereIn('user_id', array_values($this->members->lists('id')->toArray()));
                break;
            default:
                $where = [];
                break;
        }
        if (!empty($where)) {
            return $where;
        }
    }
}
