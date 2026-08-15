<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Surcharger guard() pour forcer le guard 'web'
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * Surcharger credentials() pour s'assurer qu'on utilise 'email' et 'password'
     */
    protected function credentials(Request $request)
    {
        return [
            'email'    => trim($request->email),
            'password' => $request->password,
        ];
    }

    /**
     * توجيه المستخدم بعد تسجيل الدخول حسب دوره (Role)
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('client')) {
            return redirect()->route('client.dashboard');
        }

        return redirect()->route('dashboard');
    }
}