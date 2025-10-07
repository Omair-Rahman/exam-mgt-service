<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Your OTP Code</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f4f4f7; padding:30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:#4F46E5; padding:20px; text-align:center; color:#ffffff; font-size:24px; font-weight:bold;">
                            {{ config('app.name') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px; color:#333333;">
                            <h2 style="margin-top:0; color:#111827; font-size:22px;">Hello!</h2>
                            <p style="font-size:16px; line-height:1.6; margin:16px 0;">
                                Your one-time password (OTP):
                            </p>

                            <p style="font-size:28px; font-weight:bold; color:#4F46E5; text-align:center; margin:20px 0;">
                                {{ $code }}
                            </p>

                            <p style="font-size:14px; color:#555555; line-height:1.6;">
                                Please use this code to complete your login. This code will expire in 
                                <strong>{{ $expires_in_minutes ?? 5 }} minutes</strong>.
                            </p>

                            <p style="margin-top:30px; font-size:14px; color:#999999; text-align:center;">
                                If you did not request this code, please ignore this email.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f9fafb; padding:20px; text-align:center; font-size:12px; color:#999999;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
