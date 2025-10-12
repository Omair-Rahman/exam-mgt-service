{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign in</title>

    {{-- Tailwind via CDN for quick styling (fine for prototyping) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Optional: Inter font --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        html,
        body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        }

        .glass {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
    </style>
</head>

<body class="min-h-full bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100">
    <div class="flex min-h-full items-center justify-center p-6">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <div
                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/20 ring-1 ring-indigo-400/30">
                    <!-- Simple icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-300" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16.5 10.5V6.75A4.5 4.5 0 0 0 12 2.25v0a4.5 4.5 0 0 0-4.5 4.5V10.5M5.25 21.75h13.5a1.5 1.5 0 0 0 1.5-1.5v-6.75a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v6.75a1.5 1.5 0 0 0 1.5 1.5Z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-semibold tracking-tight text-white">Welcome back</h1>
                <p class="mt-1 text-sm text-slate-300">Sign in to your account</p>
            </div>

            {{-- Flash status (e.g., "Password reset link sent") --}}
            @if (session('status'))
                <div
                    class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3">
                    <ul class="list-inside list-disc text-rose-200/90 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="glass rounded-2xl p-6 shadow-2xl">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="username" class="mb-1 block text-sm text-slate-200">Email or phone</label>
                        <input id="username" name="username" type="text" inputmode="email" autocomplete="username"
                            placeholder="you@example.com or 01XXXXXXXXX" value="{{ old('username') }}"
                            class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-slate-100 placeholder-slate-400 outline-none ring-indigo-400/30 focus:border-indigo-400/40 focus:ring-4"
                            required />
                    </div>

                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <label for="password" class="block text-sm text-slate-200">Password</label>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 pr-12 text-slate-100 placeholder-slate-400 outline-none ring-indigo-400/30 focus:border-indigo-400/40 focus:ring-4"
                                required />
                            <button type="button" aria-label="Show password"
                                class="absolute inset-y-0 right-0 my-auto mr-2 inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-300 hover:bg-white/10"
                                onclick="const p=document.getElementById('password'); p.type=p.type==='password'?'text':'password'; this.setAttribute('aria-label', p.type==='password'?'Show password':'Hide password');">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.64 0 8.576 3.01 9.964 7.178.07.214.07.43 0 .644C20.576 16.49 16.64 19.5 12 19.5c-4.64 0-8.576-3.01-9.964-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-300 select-none">
                            <input type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-400/50">
                            Remember me
                        </label>
                        <a href="{{ route('password.forgot') }}"
                            class="text-sm text-indigo-300 hover:text-indigo-200">Forgot Password?</a>
                    </div>

                    <button type="submit"
                        class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-500 px-4 py-2.5 font-medium text-white ring-1 ring-indigo-400/50 transition hover:bg-indigo-400 hover:shadow-lg hover:shadow-indigo-500/20 focus:outline-none focus:ring-4 focus:ring-indigo-400/40">
                        <span>Sign in</span>
                        <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M5 12h14M13 5l7 7-7 7" />
                        </svg>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="my-6 flex items-center gap-3 text-xs text-slate-400">
                    <div class="h-px flex-1 bg-white/10"></div>
                    <span>or</span>
                    <div class="h-px flex-1 bg-white/10"></div>
                </div>

                {{-- Social/example buttons (optional) --}}
                <div class="grid grid-cols-1 gap-3">
                    <button class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-slate-200 hover:bg-white/10">
                        <a href="{{ route('otp.show') }}">Examinee? Sign in with OTP
                    </button>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-slate-400">
                By continuing you agree to our <a href="#"
                    class="underline decoration-white/30 hover:text-slate-200">Terms</a> and <a href="#"
                    class="underline decoration-white/30 hover:text-slate-200">Privacy Policy</a>.
            </p>
        </div>
    </div>
</body>

</html>
