<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreBulkLeadsRequest;
use Whatsloan\Jobs\StoreBulkLeadsJob;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Leads\Contract as ILeads;
use Whatsloan\Repositories\Leads\UploadValidator;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\Excel\IExcel;
use PHPExcel_Shared_Date;

class LeadsBulkController extends Controller
{

    /**
     * @var IExcel
     */
    private $excel;

    /**
     * @var ILeads
     */
    private $leads;

    /**
     * ProjectsBulkController constructor.
     * @param IExcel $excel
     */
    public function __construct(IExcel $excel, ILeads $leads)
    {
        $this->excel = $excel;
        $this->leads = $leads;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.leads.bulk.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBulkLeadsRequest $request)
    {
        $sheets = $this->excel->load($request->file('upload'));
        $data   = $sheets->first();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $data[$key]['date_of_birth'] = date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP($data[$key]['date_of_birth']));
            }
            foreach ($data as $rowIndex => $row) 
            {
                $validator = \Validator::make($row, UploadValidator::rules($row));
                if ($validator->fails()) {
                    $error = uuid();
                    session()->put($error, ['errors' => $validator->errors(), 'rowIndex' => $rowIndex]);
                    return redirect()->route('admin.v1.leads.bulk.errors', $error);
                }
            }
            $this->dispatch(new StoreBulkLeadsJob($sheets));
            return redirect()->back()->withSuccess('Leads added successfully');

        } else {
            return redirect()->back()->withSuccess('The import process cannot proceed with an empty sheet.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Download the template for projects
     */
    public function template()
    {

        if (!in_array(\Auth::user()->role, ['SUPER_ADMIN', 'DSA_OWNER'])) {
            return redirect()->route('admin.v1.leads.index')->withError("Access Restricted");
        }

        $sources   = Source::all();
        $referrals = User::where('role', 'REFERRAL')->get();

        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $agents = User::whereIn('role', ['DSA_OWNER', 'DSA_MEMBER'])->get();
        } else {
            $agents = User::whereIn('id', teamMemberIds())->get();
        }

        $projects = Project::all();
        $cities   = City::join('addresses', 'addresses.city_id', '=', 'cities.id')
            ->where('addresses.addressable_type', 'Whatsloan\Repositories\Banks\Bank')
            ->groupBy('city_id')
            ->get();
        $types = Type::all();

        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $members = User::whereIn('role', ['DSA_MEMBER', 'DSA_OWNER'])->get();
        } else {
            $members = User::whereIn('id', teamMemberIds())->get();
        }

        $sheets = collect([
            ['Leads' => 'admin.v1.leads.bulk.sheets.leads', 'data' => null, 'format' => []],
            ['Cities' => 'admin.v1.leads.bulk.sheets.cities', 'data' => $cities, 'format' => []],
            ['Types' => 'admin.v1.leads.bulk.sheets.types', 'data' => $types, 'format' => []],
            ['Sources' => 'admin.v1.leads.bulk.sheets.sources', 'data' => $sources, 'format' => []],
            ['Agents' => 'admin.v1.leads.bulk.sheets.agents', 'data' => $agents, 'format' => []],
            ['Property_Verified' => 'admin.v1.leads.bulk.sheets.propertyverified', 'data' => null, 'format' => []],
            ['Referrals' => 'admin.v1.leads.bulk.sheets.referrals', 'data' => $referrals, 'format' => []],
            ['Projects' => 'admin.v1.leads.bulk.sheets.projects', 'data' => $projects, 'format' => []],
            ['Assigned_To' => 'admin.v1.leads.bulk.sheets.assignedto', 'data' => $members, 'format' => []],
        ]);

        $this->excel->template($sheets, 'Leads_Upload_Worksheet');
    }

    /**
     * Display list of all upload errors
     *
     * @return mixed
     */
    public function errors($id)
    {
        $errors = session($id);

        return view('admin.v1.leads.bulk.errors')
            ->withErrors($errors['errors'])
            ->withRow(($errors['rowIndex'] + 2));
    }

    /**
     * Export leads to excel
     */
    public function export()
    {

        $leads = $this->leads->listAsAdmin();

        $sheets = collect([
            ['Leads' => 'admin.v1.leads.bulk.sheets.export', 'data' => $leads, 'format' => []],
        ]);

        $this->excel->template($sheets, 'Leads');
    }

}
