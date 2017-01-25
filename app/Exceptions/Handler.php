<?php

namespace Whatsloan\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Whatsloan\Http\Transformers\V1\NotFoundTransformer;
use Whatsloan\Http\Transformers\V1\ModelNotFoundTransformer;
use Whatsloan\Http\Transformers\V1\QueryExceptionTransformer;
use Whatsloan\Http\Transformers\V1\ValidationError;
use Whatsloan\Services\Transformers\Transformable;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{

    use Transformable;


    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request $request
     * @param  \Exception $e
     * @return Response
     */
    public function render($request, Exception $e)
    {
        // dd($e);
        if ($request->is('api/*')) {
            switch ($e) {
                case $e instanceof ModelNotFoundException:
                    return $this->transformItem($e, new ModelNotFoundTransformer, 404);
                    break;
                case $e instanceof QueryException:
                    return $this->transformItem($e, new QueryExceptionTransformer, 404);
                    break;
                case $e instanceof NotFoundHttpException:
                    return $this->transformItem($e, new NotFoundTransformer, 404);
                    break;
                 case $e instanceof GeneralException:
                    return $this->transformItem($e->getMessage(), new GeneralException, 404);
                    break;
            }
        }

        if ($request->is('admin/*')) {

            switch ($e) {
                case $e instanceof NotFoundHttpException:
                    return response()->view('errors.404', [], 404);
                    break;
                /**
                 * Redirect if token mismatch error
                 * Usually because user stayed on the same screen too long and their session expired
                 */
                case $e instanceof TokenMismatchException:
                    return redirect()->route('admin.v1.auth.login.get');
                    break;

                case $e instanceof GeneralException:
                    return redirect()->back()->withError($e->getMessage());
                    break;
            }
        }

        return parent::render($request, $e);
    }
}
