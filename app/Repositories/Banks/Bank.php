<?php

namespace Whatsloan\Repositories\Banks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\Attachments\Attachable;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Projects\Agent;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Addresses\Addressable;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\Audits\Auditable;

class Bank extends Model
{

    use Addressable,
        PresentableTrait,
        SoftDeletes,
        Attachable,
        Auditable;

    /**
     * Model Presenter
     *
     * @var
     */
    protected $presenter = Presenter::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'name', 'branch', 'ifsc_code'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Returns the path of documents
     *
     * @return string
     */
    public function getDocumentPath()
    {
        return 'documents/' . $this->uuid . '/';
    }

    /**
     * Returns the path of banks
     *
     * @return string
     */
    public function getBankPath()
    {
        return 'banks/' . $this->uuid . '/';
    }

    /**
     * Bank has multiple projects
     * @return Collection
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)
                        ->withTimestamps()
                        ->withPivot(['agent_id', 'status']);
    }

    /**
     * Custom Pivots
     *
     * @param Model $parent
     * @param array $attributes
     * @param string $table
     * @param bool $exists
     * @return \Illuminate\Database\Eloquent\Relations\Pivot|Agent
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        // Override the pivot on the relation
        if ($parent instanceof Project) {
            return new Agent($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }



    /**
     * Banks has many users
     * @return type
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Bank has multiple products
     * @return Collection
     */
    public function products()
    {
        return $this->belongsToMany(Type::class,'bank_product','bank_id','product_id')
                    ->withPivot(['id'])
                    ->withTimestamps();
    }

}
