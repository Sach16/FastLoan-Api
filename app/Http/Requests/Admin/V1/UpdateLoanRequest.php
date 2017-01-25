<?php

namespace Whatsloan\Http\Requests\Admin\V1;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Http\Requests\Request;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;

class UpdateLoanRequest extends Request
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

        $rules =  [
            "agent_id" => "required|numeric|exists:users,id",
            "loan_status_id" => "required|numeric|exists:loan_statuses,id",
            "type_id" => "required|numeric|exists:types,id",
            "amount" => "required|numeric|min:1",
            "eligible_amount" => "required|numeric|min:1",
        ];

        $data = request()->all();

        if( isset($data['loan_status_id']) &&  !empty($data['loan_status_id']) && LoanStatus::find($data['loan_status_id'])->key == 'SANCTION' ) {
            $rules = [
                        "approved_amount" => "required|numeric|min:1",
                        "emi"               => "required|numeric|min:1",
                        "emi_start_date"    => "required",
                    ];
        }

        if( isset($data['loan_status_id']) && !empty($data['loan_status_id']) && in_array(LoanStatus::find($data['loan_status_id'])->key, ['FIRST_DISB','PART_DISB','FINAL_DISB']) ) {
            $rules = [
                    "disbursement_amount"   => "required|numeric|min:1",
            ];
        }

        if(isset($data['type_id']) && $data['type_id'] != -1 && Type::find($data['type_id'])->key != null && Type::find($data['type_id'])->key == 'HL'  && isset($data['builder_id']) && $data['builder_id'] != -1) {
            $rules["builder_id"] = "exists:builders,id";
            $rules["project_id"] = "required|exists:projects,id";
        }

        return $rules;
    }

}
