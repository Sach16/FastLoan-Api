<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Dotenv\Exception\InvalidFileException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreBankProductRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateBankProductRequest;
use Whatsloan\Jobs\StoreBankProductJob;
use Whatsloan\Jobs\UpdateBankProductJob;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Banks\BankProduct;
use Whatsloan\Repositories\Types\Type;

class BanksProductsController extends Controller
{
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
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        return view('admin.v1.banks.products.create')->withId($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreBankProductRequest $request
     * @param $bankId
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankProductRequest $request, $bankId)
    {
        $bank_product = BankProduct::firstOrNew(['bank_id'=>$bankId,'product_id'=>$request->product]);
        if (!$bank_product->exists) {
            $bank_product->uuid = uuid();
            $bank_product->save();
        }
        $upload = upload($bank_product->getProductPath(), $request->file('document'));
        if ($upload)
        {
            $request->offsetSet('uri', $upload);
            $this->dispatch(new StoreBankProductJob($bank_product->id, $request->only(['name', 'description', 'uri', 'type'])));
            return redirect()->route('admin.v1.banks.show', $bankId)->withSuccess('Document uploaded');
        }
        return redirect()->route('admin.v1.banks.show', $bankId)->withError('Document could not be uploaded');
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
     * @param $bankId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function edit($bankId, $documentId)
    {
        $attachment = Attachment::find($documentId);
        $bank = BankProduct::with(['attachments' => function($query) use ($documentId){
            $query->whereId($documentId)->take(1);
        }])->find($attachment->attachable_id);
        return view('admin.v1.banks.products.edit')
                        ->withAttachment($bank->attachments->first())
                        ->withBank($bankId)
                        ->withProduct($bank->product_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateBankProductRequest $request
     * @param $bankId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankProductRequest $request, $bankId, $documentId)
    {
        $bank_product = BankProduct::where('bank_id',$bankId)->where('product_id',$request->product)->first();
        if ($request->exists('document')) {
            $upload = upload($bank_product->getProductPath(), $request->file('document'));
            $request->offsetSet('uri', $upload);
        }
        $this->dispatch(new UpdateBankProductJob($bank_product->id, $documentId,  $request->except('document')));
        return redirect()->route('admin.v1.banks.show', $bankId)->withSuccess('Document updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $bankId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function destroy($bankId, $documentId)
    {
        $attachment = Attachment::find($documentId);
        BankProduct::with('attachments')
                        ->find($attachment->attachable_id)
                        ->attachments()
                        ->where('id', $documentId)
                        ->delete();

        return redirect()->back()->withSuccess('Document deleted');
    }
}
