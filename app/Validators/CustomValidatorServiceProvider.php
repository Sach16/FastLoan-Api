<?php
namespace Whatsloan\Validators;

use Illuminate\Support\ServiceProvider;
use Whatsloan\Validators\CustomValidator;

class CustomValidatorServiceProvider extends ServiceProvider
{
    public function register(){}

    public function boot()
    {
        $this->app->validator->resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }

}