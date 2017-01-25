<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class UserSettingsTransformer extends TransformerAbstract
{

    /**
     * User settings transformer
     *
     * @param array $settings
     * @return array
     */
    public function transform(array $settings)
    {
        return $settings;
    }
}