<?php

namespace Whatsloan\Providers;

use Illuminate\Support\ServiceProvider;

use Whatsloan\Services\Excel\ExcelAdaptor;
use Whatsloan\Services\Excel\IExcel;
use Whatsloan\Services\Sms\Contract as IGupshupSms;
use Whatsloan\Services\Sms\GupshupSms;
use Whatsloan\Services\Zipper\IZip;
use Whatsloan\Services\Zipper\LeagueZipper;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_MONETARY, 'en_IN');
        
        Validator::extend('isPhoneUpdatable', function($attribute, $value, $parameters, $validator) {
            dd("Update phone");
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IGupshupSms::class, GupshupSms::class);
        $this->app->bind(IExcel::class, ExcelAdaptor::class);
        $this->app->bind(IZip::class, LeagueZipper::class);
    }
}
