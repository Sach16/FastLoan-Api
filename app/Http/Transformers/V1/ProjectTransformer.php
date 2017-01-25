<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Http\Transformers\V1\BuilderTransformer;

class ProjectTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'status',
        'builder',        
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'address',
        'banks',
        'owner',
        'assignee',
        'queries',
        'attachments',
    ];

    /**
     * @param $e
     * @return array
     */
    public function transform(Project $project)
    {
   
        if(count($project->queries) > 0) {        
            foreach($project->queries as $key => $value) {
                $start_dates[$key] = $value['start_date'];
                $end_dates[$key] = $value['end_date'];
            }       
            
            $lsr_start_date = min($start_dates);
            $lsr_end_date = max($end_dates);
            
        } else {
            $lsr_start_date = null;
            $lsr_end_date = null;
        }
    
        return [
            'uuid' => $project->uuid,
            'name' => $project->name,
            'builder_name' => $project->builder()->first()->name,
            'status' => $project->status,
            'unit_number' => ($project->unit_details) ? $project->unit_details : 0,
            'possession_date' => $project->possession_date,
            //'bank_approval_date' => $project->bank_approval_date,
            'lsr_start_date' => $lsr_start_date,
            'lsr_end_date' => $lsr_end_date,
            'is_approved' => $project->is_approved,
        ];
    }

    /**
     * Include Banks
     * @param  Project $project
     * @return Collection
     */
    public function includeBanks(Project $project)
    {
        return $this->collection($project->banks, new BankTransformer);
    }

    /**
     * Inlude the owner of the project
     * @param  Project $project
     * @return Item
     */
    public function includeOwner(Project $project)
    {
        return $this->item($project->owner()->first(), new UserTransformer);
    }

    /**
     * Inlude the assignee of the project
     * @param  Project $project
     * @return Item
     */
    public function includeAssignee(Project $project)
    {
        return $this->item($project->assignee()->first(), new UserTransformer);
    }

    /**
     * Include addresses
     * @param  Project $project
     * @return Item
     */
    public function includeAddress(Project $project)
    {
        return $this->item($project->addresses->first(), new AddressTransformer);
    }

    /**
     * Include addresses
     * @param  Project $project
     * @return Item
     */
    public function includeStatus(Project $project)
    {
        return $this->item($project->status()->first(), new ProjectStatusTransformer);
    }

    /**
     * Include builder details
     * @param Project $project
     * @return Item
     */
    public function includeBuilder(Project $project)
    {
        return $this->item($project->builder()->first(), new BuilderTransformer);
    }

    
    
    /**
     * Include LSR queries
     * @param Project $project
     * @return type
     */
    public function includeQueries(Project $project)
    {
        return $this->collection($project->queries, new QueryTransformer);
    }
    
    /**
     * Include LSR queries
     * @param Project $project
     * @return type
     */
    public function includeAttachments(Project $project)
    {
        return $this->collection($project->attachments, new AttachmentTransformer);
    }

}
