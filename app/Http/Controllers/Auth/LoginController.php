<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public $maxAttempts  = 3;
    public $decayMinutes = 3 * 60;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'login';
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if($this->guard()->validate($this->credentials($request))) {
            if(Auth::attempt([
                $this->username() => $request->login,
                'password'        => $request->password,
                'is_active'       => 1,
            ])) {
                $this->clearLoginAttempts($request);

                return redirect()->intended();
            }  else {
                $this->incrementLoginAttempts($request);

                return back()
                    ->withErrors(['login' => __('auth.not_active')])
                    ->withInput(request()->except('password'));
            }
        } else {
            $this->incrementLoginAttempts($request);

            return back()
                ->withErrors(['login' => __('auth.failed')])
                ->withInput(request()->except('password'));
        }
    }
}
