<?php

namespace Whatsloan\Repositories\Leads;

use Illuminate\Http\Request;

interface Contract
{
    /**
     * Get a paginated list of leads
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);

    /**
     * Get the details of a single lead
     *
     * @param $uuid
     * @return mixed
     */
    public function find($uuid);

    /**
     * Add a new lead
     *
     * @param Request $request
     * @return mixed
     */
    public function add($request);

    /**
     * Get the validation rules for the model
     *
     * @return mixed
     */
    public function getValidations();

    /**
     * Getting Leads based on User
     * @return Lead
     */
    public function getLeadsByUserIds($ids);


    /**
     * Add a single loan
     * @param array $data
     */

    public function getLeadsByUserIdAsConsumers($userId);

    public function store($data);

    public function newLeadAsConsumers($data);
    public function referralAsConsumers($data);
    
    
    /**
     * Paginated list of leads
     */
    public function listAsAdmin();
    
    
    
    public function getReferralsAsConsumers($userId);    
    /**
     * Store lead as admin
     * @param array $data
     */
    public function storeAsAdmin($data);
}
