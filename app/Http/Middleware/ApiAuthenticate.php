<?php

namespace Whatsloan\Http\Middleware;

use Closure;
use Whatsloan\Services\Transformers\Transformable;
use Whatsloan\Http\Transformers\V1\AuthenticationFailedTransformer;

class ApiAuthenticate
{

    use Transformable;

    /**
     * Handle an incoming request of API
     *
     * @param  Request $request
     * @param \Closure|Closure $next
     * @param  string|api $guard
     * @return response
     */
    public function handle($request, Closure $next, $guard = 'api')
    {
        if (\Auth::guard($guard)->guest()) {
            return $this->transformItem([], AuthenticationFailedTransformer::class, 401);
        }
        return $next($request);
    }
}