<?php

namespace Whatsloan\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Whatsloan\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {

        $router->group(['namespace' => 'Whatsloan\Http\Controllers\Api\V1'], function ($router) {
            require app_path('Http/Routes/api.php');
        });
        $router->group(['namespace' => 'Whatsloan\Http\Controllers\Admin\V1'], function ($router) {
            require app_path('Http/Routes/admin.php');
        });
    }
}
