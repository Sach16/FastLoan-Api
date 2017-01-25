<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\Consumers\ForbiddenActionTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\LoanAlertFailedTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\LoanAlertTransformer;
use Whatsloan\Repositories\LoanAlert\Contract as LoanAlerts;

class LoanAlertController extends Controller
{
    private $loanalerts;
    public function __construct(LoanAlerts $loanalerts)
    {
        $this->loanalerts = $loanalerts;
    }

    public function index()
    {
        $loanalert = $this->loanalerts->show(\Auth::guard('api')->user()->id);
        return $this->transformItem($loanalert, LoanAlertTransformer::class);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
        if ($request->loanalert_uuid) {
            if ($request->loanalert_uuid != $this->loanalerts->show(\Auth::guard('api')->user()->id)->uuid) {
                return $this->transformItem('', ForbiddenActionTransformer::class, 403);
            }
            $loanalert = $this->loanalerts->update($request, $request->loanalert_uuid);
        } else {
            $loanAlertUuid = isset($this->loanalerts->show(\Auth::guard('api')->user()->id)->uuid) ? $this->loanalerts->show(\Auth::guard('api')->user()->id)->uuid : '';
            if ($loanAlertUuid == '') {
                $validator = Validator::make($request->all(), [
                    'loan_emi_amount'   => 'required',
                    'interest_rate'     => 'required',
                    'due_date'          => 'required',
                    'bank_uuid'         => 'required|exists:banks,uuid',
                    'type_uuid'         => 'required|exists:types,uuid',
                    'balance_amount'    => 'required|numeric',
                    'emi_start_date'    => 'required',
                    'emi'               => 'required|numeric',
                    'take_over'         => 'required|numeric',
                    'part_pre_payement' => 'required|numeric',
                    'type_of_property'  => 'required',
                ]);
                if ($validator->fails()) {
                    return $this->transformItem($validator->messages(), LoanAlertFailedTransformer::class, 400);
                }

                $loanalert = $this->loanalerts->store($request);
            } else {
                $loanalert = $this->loanalerts->update($request, $loanAlertUuid);
            }
        }

        return $this->transformItem($loanalert, LoanAlertTransformer::class);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $uuid)
    {
        //
        if ($uuid != $this->loanalerts->show(\Auth::guard('api')->user()->id)->uuid) {
            return $this->transformItem('', ForbiddenActionTransformer::class, 403);
        }

        $loanalert = $this->loanalerts->update($request, $uuid);
        return $this->transformItem($loanalert, LoanAlertTransformer::class);
    }

}
