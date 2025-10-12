<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class PasswordResetOtpController extends Controller
{
    public function show()
    {
        return view('auth.passwords.forgot_otp');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'identifier_type' => ['required', 'in:email,phone'],
            'identifier'      => ['required', 'string', 'max:191'],
        ]);

        $identifier_type = $data['identifier_type'];
        $identifier      = trim($data['identifier']);

        $userExists = User::query()
            ->when($identifier_type === 'email', fn($q) => $q->where('email', $identifier))
            ->when($identifier_type === 'phone', fn($q) => $q->where('phone', $identifier))
            ->exists();

        if ($userExists) {
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
                Mail::to($identifier)->send(new OtpMail($code));
            } else {
                logger()->info("SMS to {$identifier}: OTP {$code}");
            }
        }

        return redirect()
            ->route('password.reset.otp', ['identifier_type' => $identifier_type, 'identifier' => $identifier])
            ->with('status', 'If the account exists, an OTP has been sent.');
    }

    public function showVerify(Request $request)
    {
        $identifier_type = $request->query('identifier_type');
        $identifier      = $request->query('identifier');
        abort_unless(in_array($identifier_type, ['email', 'phone']), 404);

        return view('auth.passwords.reset_otp', compact('identifier_type', 'identifier'));
    }

    public function verifyAndReset(Request $request)
    {
        $data = $request->validate([
            'identifier'      => ['required', 'string', 'max:191'],
            'code'            => ['required', 'digits:6'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
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

        // Reset user password
        $user = User::when(
            $data['identifier_type'] === 'email',
            fn($q) => $q->where('email', $data['identifier']),
            fn($q) => $q->where('phone', $data['identifier'])
        )->first();

        if ($user) {
            $user->password = Hash::make($data['password']);
            $user->save();
        }

        return redirect()->route('login')->with('success', 'Password changed successfully. Please log in.');
    }
}
