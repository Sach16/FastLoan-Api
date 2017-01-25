<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreBulkProjectsRequest;
use Whatsloan\Jobs\StoreBulkProjectsJob;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Projects\UploadValidator;
use Whatsloan\Repositories\ProjectStatuses\ProjectStatus;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\Excel\IExcel;

class ProjectsBulkController extends Controller
{
    /**
     * @var IExcel
     */
    private $excel;

    /**
     * ProjectsBulkController constructor.
     * @param IExcel $excel
     */
    public function __construct(IExcel $excel)
    {
        $this->excel = $excel;
    }

    /**
     * @return mixed
     */
    public function create()
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.projects.index')->withError("Access Restricted");
        }
        return view('admin.v1.projects.bulk.create');
    }

    /**
     * @param StoreBulkProjectsRequest $request
     */
    public function store(StoreBulkProjectsRequest $request)
    {
        $sheets = $this->excel->load($request->file('upload'));
        if(!empty($sheets->first())){
          $data = $sheets->first();
            foreach ($data as $key => $value) {
                $data[$key]['possession_date'] = date('d-m-Y', strtotime($data[$key]['possession_date']));
            }
            
            $validator = \Validator::make($data, UploadValidator::rules());
            if ($validator->fails()) {
                $error = uuid();
                session()->put($error, $validator->errors());
                return redirect()->route('admin.v1.projects.bulk.errors', $error);
            }
            $this->dispatch(new StoreBulkProjectsJob($sheets));
            return redirect()->back()->withSuccess('Projects added successfully');
        }else{
            return redirect()->back()->withSuccess('The import process cannot proceed with an empty sheet.');
        }
    }

    /**
     * Download the template for projects
     */
    public function template()
    {
        $cities = City::all();
        $builders = Builder::all();
        $user = request()->user();


//        $owners = User::with('teams', 'teams.members')
//                        ->whereId($user->id)
//                        ->first()->teams->first()->members;

        $statuses = ProjectStatus::all();
        $sheets = collect([
            ['Projects' => 'admin.v1.projects.bulk.sheets.projects', 'data' => null, 'format' => ['D' => 'dd-mm-yyyy']],
            ['Cities' => 'admin.v1.projects.bulk.sheets.cities', 'data' => $cities, 'format' => []],
            ['Builders' => 'admin.v1.projects.bulk.sheets.builders', 'data' => $builders, 'format' => []],
           // ['Owners' => 'admin.v1.projects.bulk.sheets.owners', 'data' => $owners, 'format' => []],
            ['Statuses' => 'admin.v1.projects.bulk.sheets.statuses', 'data' => $statuses, 'format' => []],
        ]);

        $this->excel->template($sheets, 'Projects_Upload_Worksheet');
    }

    /**
     * Display list of all upload errors
     *
     * @return mixed
     */
    public function errors($id)
    {
        $errors = session($id);
        return view('admin.v1.projects.bulk.errors')->withErrors($errors);
    }
}
