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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function credentials(Request $request)
    {
        // return $request->all();
        if(auth()->attempt($request->only('name', 'password'))){
            // if(auth()->user()->hasRole('admin')){
            //     return "admin";
            // }
            // Store the token in the session
            // $token = auth()->user()->createToken('authToken')->accessToken;
            // session(['authToken' => $token]);
            return redirect('/');
        }

        return redirect()->back();
        // $credentials = $request->only($this->username(), 'password');
        // $credentials = array_add($credentials, 'is_deleted', '0');
        // return $credentials;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
