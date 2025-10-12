<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class ExamineeRegistrationController extends Controller
{
    public function show()
    {
        $identifier = session('otp_verified_identifier');
        abort_if(!$identifier, 403);

        return view('auth.otp.register', compact('identifier'));
    }

    public function store(Request $request)
    {
        $identifier = session('otp_verified_identifier');
        abort_if(!$identifier, 403);

        $data = $request->validate([
            'name'  => 'required|string|max:120',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        // Ensure identifier is set on either email or phone
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = $data['email'] ?: $identifier;
        } else {
            $data['phone'] = $data['phone'] ?: $identifier;
        }

        // Create examinee (password is null per your rule)
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'] ?? null,
            'phone'    => $data['phone'] ?? null,
            'password' => null,
            'role'     => 'examinee',
        ]);

        Auth::login($user);

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

        session()->forget('otp_verified_identifier');

        return redirect()->intended('dashboard.examinee')
            ->withCookie($tokenCookie ?? cookie()->forget(config('jwt.cookie', 'token')));
    }
}
