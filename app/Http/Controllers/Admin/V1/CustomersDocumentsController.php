<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Requests\Admin\V1\StoreCustomerDocumentRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateCustomerDocumentRequest;
use Whatsloan\Jobs\StoreCustomerDocumentJob;
use Whatsloan\Jobs\UpdateCustomerDocumentJob;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\Zipper\IZip;

class CustomersDocumentsController extends Controller
{
    /**
     * @var IZip
     */
    private $zip;

    /**
     * CustomersDocumentsController constructor.
     * @param IZip $zip
     */
    public function __construct(IZip $zip)
    {
        $this->zip = $zip;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $customerId
     * @return \Illuminate\Http\Response
     */
    public function index($customerId)
    {
        $customer = User::customers()->with(['attachments' => function($query) {
            $query->whereType('CUSTOMER_DOCUMENT');
        }])->orderBy('updated_at', 'desc')->find($customerId);

        return view('admin.v1.customers.documents.index')->withCustomer($customer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $customerId
     * @return \Illuminate\Http\Response
     */
    public function create($customerId)
    {
        $customer = User::customers()->find($customerId);
        return view('admin.v1.customers.documents.create')->withCustomer($customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreCustomerDocumentRequest $request
     * @param $customerId
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerDocumentRequest $request, $customerId)
    {
        $customer = User::customers()->find($customerId);
        if ($request->exists('document')) {
            $upload = upload($customer->getCustomerDocumentPath(), $request->file('document'));
            $request->offsetSet('uri', $upload);
        }
        $request->offsetSet('uuid', uuid());
        $request->offsetSet('type', 'CUSTOMER_DOCUMENT');
        $this->dispatch(new StoreCustomerDocumentJob($request->except('document'), $customerId));

        return redirect()->route('admin.v1.customers.documents.index', $customerId)->withSuccess('Document Added Successfully');
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
     * @param $customerId
     * @param $documentId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit($customerId, $documentId)
    {
        $customer = User::customers()->with(['attachments' => function($query) use ($documentId) {
            $query->whereId($documentId);
        }])->find($customerId);

        return view('admin.v1.customers.documents.edit')
                    ->withCustomer($customer)
                    ->withDocument($customer->attachments->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateCustomerDocumentRequest $request
     * @param $customerId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerDocumentRequest $request, $customerId, $documentId)
    {
        $customer = User::customers()->find($customerId);
        if ($request->exists('document')) {
            $upload = upload($customer->getCustomerDocumentPath(), $request->file('document'));
            $request->offsetSet('uri', $upload);
        }
        $this->dispatch(new UpdateCustomerDocumentJob($request->except('document'), $customerId, $documentId));
        return redirect()->back()->withSuccess('Document updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $customerId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerId, $documentId)
    {
        $customer = User::customers()->find($customerId)->attachments()->find($documentId)->delete();
        return redirect()->route('admin.v1.customers.documents.index', $customerId)->withSuccess('Deleted document');
    }

    /**
     * Download all customer documents as zip
     *
     * @param $customerId
     */
    public function download($customerId)
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.customers.index')->withError("Access Restricted");
        }

        $customer = User::customers()->with(['attachments' => function($query) {
            $query->whereType('CUSTOMER_DOCUMENT');
        }])->find($customerId);

        if ($customer->attachments->count() == 0) {
            return redirect()->back()->withError('No Documents here.');
        }

        $this->zip->archive(
            $customer->getCustomerDocumentPath(),
            $customer->present()->name . '.zip'
        );
    }
}
