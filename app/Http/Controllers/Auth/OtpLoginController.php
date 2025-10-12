<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class OtpLoginController extends Controller
{
    public function show()
    {
        return view('auth.otp.start');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
        ]);

        $identifier     = $data['identifier'];
        $identifier_type = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        Otp::updateOrCreate(
            ['identifier' => $identifier, 'identifier_type' => $identifier_type],
            [
                'code'       => $code,
                'expires_at' => now()->addMinutes(5),
                'isVerified' => false,
            ]
        );

        if ($identifier_type === 'email') {
            $subject = 'Password Reset Code for Your Account';
            Mail::to($identifier)->send(new OtpMail($code, $subject));
        }

        return redirect()->route('otp.verify.show', ['identifier' => $identifier])
            ->with('status', 'OTP sent');
    }

    public function showVerify(Request $request)
    {
        $identifier = $request->query('identifier');
        abort_if(! $identifier, 404);
        return view('auth.otp.verify', compact('identifier'));
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
            'code'       => ['required', 'digits:6'],
        ]);

        $otp = Otp::where('identifier', $data['identifier'])
            ->where('code', $data['code'])
            ->where('isVerified', false)
            ->latest()
            ->first();

        $fail = fn() => back()->withErrors(['code' => 'Invalid code or expired. Please request a new code.']);

        if (! $otp) {
            return $fail();
        }

        $otp->delete();

        if ($otp->isExpired()) {
            return $fail();
        }

        $user = User::where('email', $data['identifier'])
            ->orWhere('phone', $data['identifier'])->first();

        if ($user && $user->isRole('examinee')) {
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

            return redirect()->intended('/examinee')
                ->withCookie($tokenCookie ?? cookie()->forget(config('jwt.cookie', 'token')));
        }

        // first-time registration for examinee
        session(['otp_verified_identifier' => $data['identifier']]);
        return redirect()->route('examinee.register.show');
    }
}
