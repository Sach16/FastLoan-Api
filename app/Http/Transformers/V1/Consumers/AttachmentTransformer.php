<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;

use Whatsloan\Repositories\Attachments\Attachment;

class AttachmentTransformer extends TransformerAbstract
{
    /**
     * @param $e
     * @return array
     */
    public function transform(Attachment $attachment)
    {

        return [
            'uuid'          => $attachment->uuid,
            'name'          => $attachment->name,
            'description'   => $attachment->description,
            'uri'           => uploaded($attachment->uri),
            'type'          => $attachment->type,                       
        ];
    }    
    
}
