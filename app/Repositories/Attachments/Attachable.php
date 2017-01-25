<?php

namespace Whatsloan\Repositories\Attachments;

trait Attachable
{

    /**
     * @return mixed
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}