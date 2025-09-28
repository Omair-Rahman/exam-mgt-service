<form method="POST" action="{{ route('otp.send') }}">@csrf
  <input name="identifier" placeholder="Email or phone" value="{{ old('identifier') }}">
  <button>Send OTP</button>
</form>
