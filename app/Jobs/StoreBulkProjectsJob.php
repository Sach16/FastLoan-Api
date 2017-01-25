<?php

namespace Whatsloan\Jobs;

use Illuminate\Support\Facades\DB;
use Whatsloan\Jobs\Job;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Projects\Project;

class StoreBulkProjectsJob extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    /**
     * @var Collection
     */
    private $rows;

    /**
     * Create a new job instance.
     * @param Collection $rows
     */
    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function() {
            foreach ($this->rows->first() as $row) {
                $project = Project::create([
                    'uuid'            => uuid(),
                    'name'            => $row['name'],
                    'builder_id'      => $row['builder_id'],
                    'owner_id'        => \Auth::user()->id,
                  // 'assigned_to'     => $row['owner_id'],
                    'unit_details'    => $row['units_available'],
                    'status_id'       => $row['status_id'],
                    'possession_date' => $row['possession_date'],
                ]);
                $project->addresses()->create([
                    'uuid'         => uuid(),
                    'alpha_street' => $row['street_1'],
                    'beta_street'  => $row['street_2'],
                    'city_id'      => $row['city_id'],
                    'state'        => $row['state'],
                    'country'      => $row['country'],
                    'zip'          => $row['pin_code'],
                ]);
                $project->delete();
            }
        });
    }
}
