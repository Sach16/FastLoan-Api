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
use Whatsloan\Jobs\StoreLoanDocumentJob;
use Whatsloan\Jobs\UpdateLoanDocumentJob;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\Loans\Loan;

class LoansDocumentsController extends Controller
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
            return redirect()->route('admin.v1.leads.index')->withError("Access Restricted");
        }
        return view('admin.v1.loans.documents.create')->withId($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreBankDocumentRequest $request
     * @param $loanId
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankDocumentRequest $request, $loanId)
    {
        $loan = Loan::find($loanId);
        $upload = upload($loan->getLoanDocumentPath(), $request->file('document'));
        if ($upload)
        {
            $request->offsetSet('attachment', $upload);
            $this->dispatch(new StoreLoanDocumentJob( $request->only(['name', 'description', 'attachment', 'type']),$loan));
            return redirect()->route('admin.v1.loans.show', $loan->id)->withSuccess('Document uploaded');
        }
        return redirect()->route('admin.v1.loans.show', $loan->id)->withError('Document could not be uploaded');
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
     * @param $loanId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function edit($loanId, $documentId)
    {
        $loan = Loan::with(['attachments' => function($query) use ($documentId){
            $query->whereId($documentId)->take(1);
        }])->find($loanId);

        return view('admin.v1.loans.documents.edit')
                        ->withAttachment($loan->attachments->first())
                        ->withLoan($loan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateBankDocumentRequest $request
     * @param $loanId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankDocumentRequest $request, $loanId, $documentId)
    {
        $loan = Loan::find($loanId);
        if ($request->exists('document')) {
            $upload = upload($loan->getDocumentPath(), $request->file('document'));
            $request->offsetSet('uri', $upload);
        }

        $this->dispatch(new UpdateLoanDocumentJob($loanId, $documentId,  $request->except('document')));
        return redirect()->route('admin.v1.loans.show', $loan->id)->withSuccess('Document updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $loanId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function destroy($loanId, $documentId)
    {
        Loan::with('attachments')
                        ->find($loanId)
                        ->attachments()
                        ->where('id', $documentId)
                        ->delete();

        return redirect()->back()->withSuccess('Document deleted');
    }
}
