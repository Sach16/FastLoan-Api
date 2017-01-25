<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use PHPExcel_Shared_Date;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreBulkCustomersRequest;
use Whatsloan\Http\Requests\Admin\V1\StoreCustomerRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateCustomerRequest;
use Whatsloan\Jobs\SetCredentialsJob;
use Whatsloan\Jobs\StoreBulkCustomersJob;
use Whatsloan\Jobs\StoreCustomerJob;
use Whatsloan\Jobs\StoreUserAttachmentsJob;
use Whatsloan\Jobs\UpdateCustomerJob;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Designations\Designation;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Users\Contract as IUsers;
use Whatsloan\Repositories\Users\UploadValidator;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\Excel\IExcel;

class CustomersController extends Controller
{
    /**
     * @var IExcel
     */
    private $excel;

    /**
     * @var IUsers
     */
    private $users;

    /**
     * ProjectsBulkController constructor.
     * @param IExcel $excel
     */
    public function __construct(IExcel $excel, IUsers $users)
    {
        $this->excel = $excel;
        $this->users = $users;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $customers = User::withTrashed()->customers()->orderBy('deleted_at', 'asc')->paginate();
        } else {
            $customers = $this->users->customersAsAdmin();
        }
        return view('admin.v1.customers.index')->withCustomers($customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designations = Designation::all();
        return view('admin.v1.customers.create')->withDesignations($designations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $request->offsetSet('designation_id', Designation::whereName('Consumer')->first()->id);
        $user = $this->dispatch(new StoreCustomerJob($request->except(['profile_picture', 'address_proof', 'id_proof'])));
        $this->dispatch(new SetCredentialsJob($user->id));
        $request = $this->CheckAttachments($request, $user);
        $this->dispatch(new StoreUserAttachmentsJob($request->except(['profile_picture', 'address_proof', 'id_proof']), $user));
        return redirect()->route('admin.v1.customers.index')->withSuccess('Customer added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.customers.index')->withSuccess('Access Restricted');
        }
        $customer = User::with(['loansTrashed', 'loansTrashed.type', 'loansTrashed.agent', 'loansTrashed.agent.banks'])->withTrashed()->find($id);
        return view('admin.v1.customers.show')->withCustomer($customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.customers.index')->withSuccess('Access Restricted');
        }
        $customer = User::with(['addresses'])->withTrashed()->find($id);
        $designations = Designation::all();
        return view('admin.v1.customers.edit')
                    ->withCustomer($customer)
                    ->withDesignations($designations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateCustomerRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $user    = User::withTrashed()->find($id);
        $request = $this->CheckAttachments($request, $user);
        $this->dispatch(new UpdateCustomerJob($request->except(['profile_picture', 'address_proof', 'id_proof']), $id));
        return redirect()->route('admin.v1.customers.show', $id)->withSuccess("Customer updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->find($id);
        if($user->loans->count() > 0)
        {
            return redirect()->route('admin.v1.customers.index')->withSuccess('Customer conno\'t disabled with active loans.');    
        }
        $user->trashed() ? $user->restore() : $user->delete();
        return redirect()->route('admin.v1.customers.index')->withSuccess('Customer updated successfully');
    }

    /**
     * @return mixed
     */
    public function bulk_create()
    {
        return view('admin.v1.customers.bulk.create');
    }

    /**
     * Download the template for customers
     */
    public function template()
    {
        $professions     = ['Salaried' => 'Salaried', 'Doctor' => 'Doctor', 'Self Employed Professional' => 'Self Employed Professional', 'Self Employed-Others' => 'Self Employed Others'];
        $marital_status  = ['Married' => 'Married', 'Unmarried' => 'Unmarried'];
        $cibil_status    = ['Settled A/C' => 'Settled A/C', 'Written off A/C' => 'Written off A/C', 'Overdue A/C' => 'Overdue A/C', 'Good A/C' => 'Good A/C'];
        $resident_status = ['Indian' => 'Indian', 'NRI' => 'NRI', 'PIO/OCI' => 'PIO/OCI'];
        $loan_status     = LoanStatus::get();
        $cities          = City::join('addresses', 'addresses.city_id', '=', 'cities.id')
            ->where('addresses.addressable_type', 'Whatsloan\Repositories\Banks\Bank')
            ->groupBy('city_id')
            ->get();
        $sheets = collect([
            ['Customers' => 'admin.v1.customers.bulk.sheets.customers', 'data' => null, 'format' => ['F' => 'dd-mm-yyyy']],
            ['Professions' => 'admin.v1.customers.bulk.sheets.professions', 'data' => $professions, 'format' => []],
            ['Marital_Status' => 'admin.v1.customers.bulk.sheets.marital', 'data' => $marital_status, 'format' => []],
            ['CIBIL_Status' => 'admin.v1.customers.bulk.sheets.cibil', 'data' => $cibil_status, 'format' => []],
            ['Resident_Status' => 'admin.v1.customers.bulk.sheets.resident', 'data' => $resident_status, 'format' => []],
            ['Loan_Status' => 'admin.v1.customers.bulk.sheets.loan', 'data' => $loan_status, 'format' => []],
            ['Cities' => 'admin.v1.customers.bulk.sheets.cities', 'data' => $cities, 'format' => []],
        ]);

        $this->excel->template($sheets, 'Customers_Upload_Worksheet');
    }

    /**
     * Display list of all upload errors
     * @return mixed
     */
    public function errors($id)
    {
        $errors = session($id);
        return view('admin.v1.customers.bulk.errors')->withErrors($errors);
    }

    /**
     * @param StoreBulkCustomersRequest $request
     */
    public function bulk_store(StoreBulkCustomersRequest $request)
    {
        $sheets = $this->excel->load($request->file('upload'));
        $data   = $sheets->first();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $data[$key]['date_of_birth'] = date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP($data[$key]['date_of_birth']));
            }
            $validator = \Validator::make($data, UploadValidator::rules());
            if ($validator->fails()) {
                $error = uuid();
                session()->put($error, $validator->errors());
                return redirect()->route('admin.v1.customers.bulk.errors', $error);
            }
            $this->dispatch(new StoreBulkCustomersJob($sheets));
            return redirect()->back()->withSuccess('Customers added successfully');
        } else {
            return redirect()->back()->withSuccess('The import process cannot proceed with an empty sheet.');
        }
    }

    private function CheckAttachments($request, $user)
    {
        if ($request->exists('profile_picture')) {
            $upload = upload($user->getUserProfilePicturePath(), $request->file('profile_picture'));
            $request->offsetSet('attachment', $upload);
        }
        if ($request->exists('address_proof')) {
            $upload = upload($user->getUserProfilePicturePath(), $request->file('address_proof'));
            $request->offsetSet('address_attachment', $upload);
        }
        if ($request->exists('id_proof')) {
            $upload = upload($user->getUserProfilePicturePath(), $request->file('id_proof'));
            $request->offsetSet('id_attachment', $upload);
        }
        return $request;
    }

    /**
     * Authorized the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function isAuthorized($id)
    {
        if(\Auth::user()->role <> 'SUPER_ADMIN')
        {
            $user = User::with(['teams'])
                    ->withTrashed()
                    ->whereUuid(\Auth::user()->uuid)
                    ->firstOrFail();
                if($this->users->authorizedCustomersAsAdmin($id))
                {
                    return true;
                }
        }
    }
}
