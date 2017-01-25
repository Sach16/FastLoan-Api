<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Dotenv\Exception\InvalidFileException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreBankDocumentRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateBankDocumentRequest;
use Whatsloan\Jobs\StoreBankDocumentJob;
use Whatsloan\Jobs\UpdateBankDocumentJob;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\Banks\Bank;

class BanksDocumentsController extends Controller
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
        return view('admin.v1.banks.documents.create')->withId($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreBankDocumentRequest $request
     * @param $bankId
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankDocumentRequest $request, $bankId)
    {
        $bank = Bank::find($bankId);
        $upload = upload($bank->getDocumentPath(), $request->file('document'));
        if ($upload)
        {
            $request->offsetSet('uri', $upload);
            $this->dispatch(new StoreBankDocumentJob($bankId, $request->only(['name', 'description', 'uri', 'type'])));
            return redirect()->route('admin.v1.banks.show', $bank->id)->withSuccess('Document uploaded');
        }
        return redirect()->route('admin.v1.banks.show', $bank->id)->withError('Document could not be uploaded');
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
        $bank = Bank::with(['attachments' => function($query) use ($documentId){
            $query->whereId($documentId)->take(1);
        }])->find($bankId);

        return view('admin.v1.banks.documents.edit')
                        ->withAttachment($bank->attachments->first())
                        ->withBank($bank);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateBankDocumentRequest $request
     * @param $bankId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankDocumentRequest $request, $bankId, $documentId)
    {
        $bank = Bank::find($bankId);
        if ($request->exists('document')) {
            $upload = upload($bank->getDocumentPath(), $request->file('document'));
            $request->offsetSet('uri', $upload);
        }

        $this->dispatch(new UpdateBankDocumentJob($bankId, $documentId,  $request->except('document')));
        return redirect()->route('admin.v1.banks.show', $bank->id)->withSuccess('Document updated');
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
        Bank::with('attachments')
                        ->find($bankId)
                        ->attachments()
                        ->where('id', $documentId)
                        ->delete();

        return redirect()->back()->withSuccess('Document deleted');
    }
}
