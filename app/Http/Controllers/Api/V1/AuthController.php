<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\AuthenticationFailedTransformer;
use Whatsloan\Http\Transformers\V1\AuthenticationLoggedOutTransformer;
use Whatsloan\Http\Transformers\V1\AuthenticationOtpSentTransformer;
use Whatsloan\Http\Transformers\V1\AuthenticationRoleFailedTransformer;
use Whatsloan\Http\Transformers\V1\UserTransformer;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Designations\Designation;
use Whatsloan\Repositories\Users\User;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;
use Whatsloan\Services\Sms\Contract as IOtp;

class AuthController extends Controller
{

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        if (!$request->has('password') && $request->has('phone')) {
            $user = User::where('phone',$request->phone)->first();
            if ($user) {
                $role = User::where('phone',$request->phone)->first()->role;
                if($request->has('app_role') && $request->app_role == 'DSA') {
                    if($role == 'DSA_OWNER' || $role == 'DSA_MEMBER') {
                        return $this->authenticateByOtp($request);
                    } else { 
                        return $this->transformItem('You are not an DSA User', AuthenticationRoleFailedTransformer::class, 401);
                    }
                } else if($request->has('app_role') && $request->app_role == 'CONSUMER') { 
                    if($role == 'CONSUMER') {
                        return $this->authenticateByOtp($request);
                    } else {
                        return $this->transformItem('You are not an Consumer', AuthenticationRoleFailedTransformer::class, 401);
                    }
                } 
            }  else { 
                return $this->transformItem('User team not found', AuthenticationFailedTransformer::class, 401);
            }                      
        }
        if (Auth::attempt($request->all())) {
            session()->set('ROLE', Auth::user()->role);
            //Later remove hard coded value
            session()->set('TEAM_ID', 1);
            return $this->transformItem(Auth::user(), UserTransformer::class, 200);
        }
        return $this->transformItem('', AuthenticationFailedTransformer::class, 401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function authenticateByOtp(Request $request)
    {
        $user = User::with(['teams'])->wherePhone($request->phone)->first();

        if ($user == null) {
            return $this->transformItem('User does not exist', AuthenticationFailedTransformer::class, 401);
        }

        if ($user->teams->count() == 0 &&  $request->app_role == 'DSA' ) {
            return $this->transformItem('User team not found', AuthenticationFailedTransformer::class, 401);
        }

        $code = rand(111111, 999999);
        $response = app(IOtp::class)->send($request->phone, $code);

        if ($response->getStatusCode() == 200) {
            $user->api_token = md5(time());
            $user->otp = $code;
            $user->save();
        }

        return $this->transformItem($response, AuthenticationOtpSentTransformer::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verifyOtp(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'otp' => 'required|numeric'
        ]);
        $user = User::with(['attachments' => function($query) {
                        $query->whereType('PROFILE_PICTURE')->orderBy('updated_at','DESC');
                    }])->wherePhone($request->phone)->whereOtp($request->otp)->first();

        if ($user != null) {
            $user->otp = null;
            $user->save();
            return $this->transformItem($user, UserTransformer::class, 200);
        }
        return $this->transformItem('', AuthenticationFailedTransformer::class, 401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'phone' => 'required|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return $this->transformItem('', AuthenticationFailedTransformer::class, 401);
        }
        $user = User::wherePhone($request->phone)->first();

        if ($user != null) {
            $user->api_token = null;
            $user->save();
            return $this->transformItem($user, AuthenticationLoggedOutTransformer::class, 200);
        }
        return $this->transformItem('', AuthenticationFailedTransformer::class, 401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|unique:users|email',
            'phone' => 'required|min:8|unique:users|numeric',
            'city_uuid' => 'required|exists:cities,uuid',
        ]);

        $city = City::whereUuid($request->city_uuid)->first();

        $user = new User([
            'uuid' => uuid(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'designation_id' => Designation::first()->id,
            'role' => 'CONSUMER',
            'api_token' => md5(time()),
        ]);
        $user->save();
        $address = new Address(['city_id' => $city->id]);
        $user->addresses()->save($address);

        return $this->transformItem($user, UserTransformer::class, 200);
    }

}
