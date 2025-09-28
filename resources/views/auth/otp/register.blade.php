<form method="POST" action="{{ route('examinee.register.store') }}">@csrf
  <input name="name" placeholder="Full name" value="{{ old('name') }}">
  <input name="email" placeholder="Email (optional)" value="{{ old('email') }}">
  <input name="phone" placeholder="Phone (optional)" value="{{ old('phone') }}">
  <button>Create account</button>
</form>
