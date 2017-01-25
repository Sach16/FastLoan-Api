<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateTaskRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description'    => 'required|min:3',
            'task_status_id' => 'required|exists:task_statuses,id',
            'task_stage_id'  => 'required|exists:task_stages,id',
            'priority'       => 'required|in:Low,Medium,High',
            'from'           => 'required|date',
            'to'             => 'required|date',
        ];
    }
}
