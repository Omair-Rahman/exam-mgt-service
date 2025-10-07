<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Throwable;

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
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        /** @var User|null $user */
        $user = User::where($field, $request->username)->first();

        if ($user && method_exists($user, 'isRole') && $user->isRole('examinee')) {
            Alert::error('OTP Required', 'Examinees must use the OTP login.');
            return back()->withInput(['username' => $request->username]);
        }

        if (Auth::attempt([$field => $request->username, 'password' => $request->password], $request->boolean('remember'))) {
            // Prevent session fixation
            $request->session()->regenerate();

            // OPTIONAL: Issue JWT for API calls (requires api guard = jwt)
            $tokenCookie = null;
            try {
                if (config('auth.guards.api.driver') === 'jwt') {
                    $token = JWTAuth::fromUser(Auth::user());

                    // Build cookie safely (secure only in production)
                    $minutes     = 60;
                    $secure      = app()->environment('production'); // false for localhost
                    $tokenCookie = cookie()->make(
                        name: config('jwt.cookie', 'token'),
                        value: $token,
                        minutes: $minutes,
                        path: '/',
                        domain: null,
                        secure: $secure,
                        httpOnly: true,
                        raw: false,
                        sameSite: 'lax'
                    );
                }
            } catch (Throwable $e) {
                // If JWT fails, continue with web session; optionally log $e
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
        // Try to logout API guard if present
        try {
            if (config('auth.guards.api.driver') === 'jwt') {
                JWTAuth::invalidate(JWTAuth::getToken());
            }
        } catch (Throwable $e) {
            // ignore; token may be missing/expired
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
            $user->isRole('super_admin') => route('dashboard.manager'),
            $user->isRole('admin')       => route('dashboard.manager'),
            $user->isRole('employee')    => route('dashboard.employee'),
            $user->isRole('adv_user')    => route('dashboard.adv-user'),
            default                      => '/dashboard',
        };
    }
}
