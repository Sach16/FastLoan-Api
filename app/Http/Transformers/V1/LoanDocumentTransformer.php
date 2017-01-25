<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\LoanDocuments\LoanDocument;


class LoanDocumentTransformer extends TransformerAbstract
{
    /**
     * @param $resource
     * @return array
     */
    public function transform(LoanDocument $loan_document)
    {
        return [
            'uuid' => $loan_document->uuid,
            'loan_id' => $loan_document->key,
            'attachment_id' => $loan_document->label,
        ];
    }
}
