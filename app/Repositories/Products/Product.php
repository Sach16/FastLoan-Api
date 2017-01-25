<?php

namespace Whatsloan\Repositories\Products;

use Illuminate\Database\Eloquent\Model;

use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Attachments\Attachable;
use Whatsloan\Services\AccessControl\ControlsAccess;
use Whatsloan\Services\Audits\Auditable;

class Product extends Model
{

    use PresentableTrait,
        Attachable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'name', 'description','key'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Date objects on the model
     *
     * @var array
     */
    protected $dates = ['from', 'to', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Model presenter
     *
     * @var $presenter
     */
    protected $presenter = Presenter::class;

    /**
     * Associated banks
     * @return Collection
     */
    public function banks()
    {
        return $this->belongsToMany(Bank::class)->withTimestamps();
    }

}
