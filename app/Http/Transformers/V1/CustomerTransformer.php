<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{


    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'attachments',
        'addresses',
        'loans',
        'profession'
    ];



    /**
     * @param $e
     * @return array
     */
    public function transform(Customer $customer)
    {
        return [
            'uuid' => $customer->uuid,
            'name' => $customer->name,
            'loan_amount' => $customer->loan_amount,
            'rate_of_interest' => $customer->rate_of_interest,
            'loan_tenure' => $customer->loan_tenure,
            'payment_cycle' => $customer->payment_cycle,
            'residential_status' => $customer->residential_status,
            'profession' => $customer->profession,
            'dob' => $customer->dob,
            'age' => $customer->age,
            'education' => $customer->education,
            'marital_status' => $customer->marital_status,
            'company' => $customer->company,
            'pan_number' => $customer->pan_number,
            'salary_account_no' => $customer->salary_account_no,
            'skype' => $customer->skype,
            'facetime_id' => $customer->facetime_id,
            'right_time_to_contact' => $customer->right_time_to_contact,
            'cibil_score' => $customer->cibil_score
        ];
    }



    /**
     * includeAttachments
     * @param  Customer $customer
     * @return collection
     */
    public function includeAttachments(Customer $customer)
    {
        return $this->collection($customer->attachments, new AttachmentTransformer);
    }


    /**
     * Include address
     * @return collection
     */
    public function includeAddresses(Customer $customer)
    {
        return $this->collection($customer->address, new AddressTransformer);
    }


    /**
     * include Loans
     * @return collection
     */
    public function includeLoans(Customer $customer)
    {
        return $this->item($customer->loans, new LoanTransformer);
    }


    /**
     * includeProfession transformer
     * @param Customer $customer
     * @return
     */
    public function includeProfession(Customer $customer)
    {
        return $this->item($customer->profession, new ProfessionTransformer);
    }


    /**
     * [includeResidentialStatus
     * @param Customer $customer
     * @return item
     */
    public function includeResidentialStatus(Customer $customer)
    {
        return $this->item($customer->residential_status, new ResidentialStatusTransformer);
    }


   
}
