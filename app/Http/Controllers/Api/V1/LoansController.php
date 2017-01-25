<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Whatsloan\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Loans\Contract as ILoans;
use Whatsloan\Http\Transformers\V1\LoanTransformer;
use Whatsloan\Http\Transformers\V1\LoanStatusTransformer;
use Whatsloan\Http\Transformers\V1\TypeTransformer;
use Whatsloan\Http\Transformers\V1\TaskTransformer;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\LoanDocuments\LoanDocument;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Http\Requests\Api\V1\UpdateLoanRequest;
use Whatsloan\Http\Requests\Api\V1\UpdateLoanStatusRequest;
use Whatsloan\Http\Requests\Api\V1\StoreLoanRequest;
use Whatsloan\Http\Requests\Api\V1\UploadLoanDocumentRequest;
use Whatsloan\Http\Transformers\V1\ResourceUpdated;
use Whatsloan\Http\Transformers\V1\DocumentUploaded;
use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Jobs\UpdateCustomerDocumentJob;
use Whatsloan\Jobs\StoreLoanDocumentJob;
use Whatsloan\Services\Zipper\IZip;

class LoansController extends Controller
{

    /**
     * @var $loans 
     */
    private $loans;

    /**
     * Inteface Contract
     * @param ILoans $loans
     */
    public function __construct(ILoans $loans)
    {
        $this->loans = $loans;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = $this->loans->paginate();
        return $this->transformCollection($loans, LoanTransformer::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoanRequest $request)
    {
        $data = $this->loans->includeIdsOfUuids($request->all());
        $loan = $this->loans->store($data);
        return $this->transformItem($loan, ResourceCreated::class);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $loan = $this->loans->show($uuid);
        return $this->transformItem($loan, LoanTransformer::class);
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
    public function update(UpdateLoanRequest $request, $uuid)
    {
        $loan = Loan::with(['user'])->whereUuid($uuid)->first();  
        
        $loan_documents = $request->loan_document;
        foreach($loan_documents as $loan_document)
        {            
            $loanDocumentAttachmentId = Attachment::whereUuid($loan_document)->first()->id;
            if (!LoanDocument::where('attachment_id', '=', $loanDocumentAttachmentId)->exists()) {
                $this->loans->saveAttachedDocument($loanDocumentAttachmentId, $loan->id);
            }            
        }
        
        if($request->file('document')) { 
            $upload = upload($loan->getDocumentPath(), $request->file('document'), true);
            if ($upload) {
                $request->offsetSet('uri', $upload);            
                $document_attached = $this->dispatch(new UpdateCustomerDocumentJob($request->except('document'), $loan->user->id));
                $this->loans->saveAttachedDocument($document_attached->id, $loan->id);
            }    
        }
            
        $data = $this->loans->includeIdsOfUuids($request->all());
        $loan = $this->loans->update($data, $uuid);
        return $this->transformItem($loan, ResourceUpdated::class);
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
     * Display a listing of the type.
     *
     * @return \Illuminate\Http\Response
     */
    public function type()
    {
        $type = Type::get();
        return $this->transformCollection($type, TypeTransformer::class);        
    }
    
    /**
     * 
     * Getting Tasks for particular loans
     */
    public function getTasks()
    {
        $loans = $this->loans->getLoanTasks();
        return $this->transformCollection($loans->tasks, TaskTransformer::class);  
    }
    
    /**
     * Updating Loan Statuses
     * @param UpdateLoanStatusRequest $request
     * @param type $loanId
     * @return type
     */
    public function updateStatus(UpdateLoanStatusRequest $request, $loanId)
    {
        $loan = $this->loans->updateStatus($request, $loanId);
        return $this->transformItem($loan, ResourceUpdated::class);
    }
    
    /**
     * 
     * @param \Whatsloan\Http\Controllers\Api\V1\UploadLoanDocumentRequest $request
     * @param type $loanId
     * @return type
     */
    public function upload(UploadLoanDocumentRequest $request, $loanId)
    {
        $loan = Loan::whereUuid($loanId)->first();        
        if ($request->exists('document')) {
            $upload = upload($loan->getLoanDocumentPath(), $request->file('document'));
            $request->offsetSet('attachment', $upload);
            $request->offsetSet('name', $request->name);            
        }
        $this->dispatch(new StoreLoanDocumentJob($request->except(['document']), $loan));
        return $this->transformItem($loan, DocumentUploaded::class);
    }
    
    /**
     * Fetching Loan Statuses list
     * @return type
     */
    public function getLoanStatuses()
    {        
        $loanStatuses = $this->loans->loanStatuses();
        return $this->transformCollection($loanStatuses, LoanStatusTransformer::class);
    }
}
