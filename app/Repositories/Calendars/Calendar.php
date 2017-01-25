<?php

namespace Whatsloan\Repositories\Calendars;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Services\Audits\Auditable;
use Carbon\Carbon;

class Calendar extends Model
{
    use PresentableTrait, SoftDeletes, Auditable;

    /**
     * Model Presenter
     *
     * @var $presenter
     */
    protected $presenter = Presenter::class;

    /**
     * Date attributes on the model
     *
     * @var array
     */
    protected $dates = ['date', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Fillable fields on the model
     *
     * @var array
     */
    protected $fillable = ['uuid', 'description', 'date', 'team_id'];

    /**
     * Set the start_date, end_date, due_date.
     *
     * @param  string  $value
     * @return string
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value);
    }
}
