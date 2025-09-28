<form method="POST" action="{{ route('otp.verify') }}">@csrf
  <input type="hidden" name="identifier" value="{{ $identifier }}">
  <input name="code" placeholder="6-digit code">
  <button>Verify</button>
</form>
