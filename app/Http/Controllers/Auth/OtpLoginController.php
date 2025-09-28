<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpLoginController extends Controller
{
    public function show()
    {
        return view('auth.otp.start');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier     = $data['identifier'];
        $identifierType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $code = (string) random_int(100000, 999999);

        Otp::create([
            'identifier'      => $identifier,
            'identifier_type' => $identifierType,
            'code'            => $code,
            'isVerified'      => false,
            'expires_at'      => now()->addMinutes(5),
        ]);

        // if ($identifierType === 'email') {
        //     Mail::to($identifier)->send(new OtpMail($code));
        // }

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
            'identifier' => 'required|string',
            'code'       => 'required|digits:6',
        ]);

        $otp = Otp::where('identifier', $data['identifier'])
            ->where('isVerified', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otp || $otp->code !== $data['code']) {
            return back()->withErrors(['code' => 'Invalid or expired OTP']);
        }

        // mark consumed
        $otp->update(['isVerified' => true]);

        // if user exists & is examinee => login
        $user = User::where('email', $data['identifier'])
            ->orWhere('phone', $data['identifier'])->first();

        if ($user && $user->isRole('examinee')) {
            Auth::login($user);

            $token = auth('api')->login($user);
            return redirect()->route('dashboard')
                ->cookie(cookie()->make('token', $token, 60, null, null, true, true, false, 'Lax'));
        }

        // otherwise go to first-time registration
        session(['otp_verified_identifier' => $data['identifier']]);
        return redirect()->route('examinee.register.show');
    }
}
