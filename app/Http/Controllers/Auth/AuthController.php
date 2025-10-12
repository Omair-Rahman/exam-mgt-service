<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }
        return view('auth.password-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $request->username)->first();

        if ($user && $user->isRole('examinee')) {
            Alert::error('OTP Required', 'Examinees must use the OTP login.');
            return back()->withInput(['username' => $request->username]);
        }

        if (Auth::attempt([$field => $request->username, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $tokenCookie = null;

            if (config('auth.guards.api.driver') === 'jwt') {
                $token      = JWTAuth::fromUser(Auth::user());
                $cookieName = config('jwt.cookie', 'token');
                $minutes    = (int) config('jwt.ttl', 60);
                $domain     = config('session.domain');
                $secure     = (bool) config('session.secure', app()->environment('production'));
                $sameSite   = config('session.same_site', 'lax'); // 'lax', 'strict', or 'none'

                $tokenCookie = cookie()->make(
                    name: $cookieName,
                    value: $token,
                    minutes: $minutes,
                    path: '/',
                    domain: $domain,
                    secure: $secure,
                    httpOnly: true,
                    raw: false,
                    sameSite: $sameSite
                );
            }

            Alert::success('Welcome!', 'You have successfully logged in.');

            $defaultDashboard = $this->dashboardPathFor(Auth::user());

            return redirect()->intended($defaultDashboard)
                ->withCookie($tokenCookie ?? cookie()->forget(config('jwt.cookie', 'token')));
        }

        Alert::error('Login Failed', 'Invalid email or password.');
        return back()->withInput(['username' => $request->username]);
    }

    public function logout(Request $request)
    {
        $cookieName = config('jwt.cookie', 'token');

        // Try to logout API guard if present
        if (config('auth.guards.api.driver') === 'jwt') {
            if ($token = $request->cookie($cookieName)) {
                JWTAuth::setToken($token)->invalidate();
            }
        }

        Auth::logout();

        // Invalidate and regen CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::success('Logged out', 'You have been successfully logged out.');

        // Remove token cookie
        return redirect()->route('login')
            ->withCookie(cookie()->forget(config('jwt.cookie', 'token')));
    }

    private function dashboardPathFor(User $user): string
    {
        return match (true) {
            $user->isRole('super_admin') => route('dashboard.admin'),
            $user->isRole('admin')       => route('dashboard.manager'),
            $user->isRole('employee')    => route('dashboard.employee'),
            $user->isRole('adv_user')    => route('dashboard.adv-user'),
            default                      => '/',
        };
    }
}
