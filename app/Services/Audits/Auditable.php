<?php

namespace Whatsloan\Services\Audits;

use ConceptByte\TimeTraveller\Scopes\TimeTravel;

trait Auditable
{

    use TimeTravel;

    /**
     * Revision made by
     *
     * @return string
     */
    public function getBy()
    {
        if (\Auth::user() != null) {
            return \Auth::user()->present()->name;
        }

        if (\Auth::guard('api')->user() != null) {
            return \Auth::guard('api')->user()->present()->name;
        }

        return 'NONE';
    }
}