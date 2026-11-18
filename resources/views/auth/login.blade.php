<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#071426">
    <title>Login - NawaTara InternTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen overflow-hidden bg-[#071426] font-sans antialiased text-slate-100">
    <main class="grid h-full w-full grid-cols-1 lg:grid-cols-12">
        <section
            class="relative isolate hidden min-h-0 flex-col justify-between overflow-hidden bg-cover bg-center px-7 py-8 sm:px-12 sm:py-10 lg:col-span-7 lg:flex lg:px-16 lg:py-12"
            style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2000&auto=format&fit=crop');"
            aria-label="NawaTara InternTrack"
        >
            <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#071426]/95 via-[#0b1c35]/80 to-[#071426]/55"></div>
            <div class="absolute inset-0 -z-10 bg-gradient-to-t from-[#071426]/90 via-transparent to-[#071426]/20"></div>
            <div class="absolute -right-24 top-1/4 -z-10 h-80 w-80 rounded-full bg-blue-400/10 blur-3xl"></div>

            <a href="{{ url('/') }}" class="inline-flex w-fit items-center gap-3 rounded-xl focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue-300">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/15 bg-white/10 text-blue-300 shadow-lg shadow-black/10 backdrop-blur-sm">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9l8-5 8 5v10M8 19v-6h8v6M2 19h20" />
                    </svg>
                </span>
                <span class="text-sm font-semibold tracking-wide text-white">NAWATARA <span class="text-blue-300">INTERNTRACK</span></span>
            </a>

            <div class="max-w-2xl py-8 lg:py-0">
                <div class="mb-4 flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.24em] text-blue-200">
                    <span class="h-px w-9 bg-blue-300"></span>
                    Internship management platform
                </div>
                <h1 class="max-w-2xl text-3xl font-semibold leading-tight tracking-tight text-white sm:text-4xl">
                    Tumbuh, berproses,
                    <span class="block text-blue-300">dan berdampak.</span>
                </h1>
                <p class="mt-4 max-w-xl text-sm leading-6 text-slate-200 sm:text-base">
                    Satu ruang untuk mengelola tugas, memantau progres, dan membangun pengalaman magang yang lebih bermakna.
                </p>
            </div>

            <p class="hidden text-xs tracking-wide text-slate-300/75 lg:block">
                © {{ date('Y') }} NawaTara InternTrack
            </p>
        </section>

        <section class="relative flex min-h-0 flex-col items-center justify-center overflow-hidden border-l border-white/5 bg-gradient-to-br from-[#101d31] via-[#0d192b] to-[#0a1525] px-5 py-5 sm:px-8 lg:col-span-5 lg:px-10 lg:py-8 xl:px-14">
            <div class="pointer-events-none absolute -left-32 top-1/4 h-80 w-80 rounded-full bg-blue-500/[0.07] blur-3xl"></div>
            <a href="{{ url('/') }}" class="relative mb-4 inline-flex shrink-0 items-center gap-2.5 rounded-xl text-xs font-semibold tracking-wide text-slate-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue-300 lg:hidden">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-blue-300 shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9l8-5 8 5v10M8 19v-6h8v6M2 19h20" />
                    </svg>
                </span>
                NAWATARA <span class="text-blue-300">INTERNTRACK</span>
            </a>
            <div class="relative w-full max-w-md rounded-3xl border border-slate-700/80 bg-slate-900/75 p-5 shadow-2xl shadow-black/20 backdrop-blur-xl sm:p-7">
                <div class="mb-6">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-blue-300">Portal akses</p>
                    <h2 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">Selamat datang</h2>
                    <p class="mt-2 text-sm leading-5 text-slate-400">Masuk dengan akun NawaTara Anda untuk melanjutkan.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-rose-400/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-200" role="alert" aria-live="polite">
                        <p class="mb-1 font-semibold">Login belum berhasil</p>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form id="login-form" action="{{ route('login.post') }}" method="POST" novalidate class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-200">Email atau Username</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6" />
                                </svg>
                            </span>
                            <span id="email-error-icon" class="pointer-events-none absolute inset-y-0 right-0 hidden items-center pr-4 text-rose-300" aria-hidden="true">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" d="M12 8v5m0 3h.01" />
                                </svg>
                            </span>
                            <input
                                id="email"
                                type="text"
                                name="email"
                                value="{{ old('email') }}"
                                autocomplete="username"
                                required
                                autofocus
                                placeholder="nama@nawatara.com"
                                aria-describedby="email-error"
                                class="block w-full rounded-xl border border-slate-700 bg-slate-800/60 py-3 pl-12 pr-12 text-sm text-white shadow-inner shadow-black/10 outline-none transition placeholder:text-slate-500 hover:border-slate-600 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10"
                            >
                        </div>
                        <p id="email-error" class="mt-2 hidden text-xs font-medium leading-5 text-rose-300" role="alert"></p>
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-200">Password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <rect x="4" y="10" width="16" height="11" rx="2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 1 1 8 0v3m-4 5v2" />
                                </svg>
                            </span>
                            <span id="password-error-icon" class="pointer-events-none absolute inset-y-0 right-0 hidden items-center pr-4 text-rose-300" aria-hidden="true">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" d="M12 8v5m0 3h.01" />
                                </svg>
                            </span>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                autocomplete="current-password"
                                required
                                placeholder="Masukkan password"
                                aria-describedby="password-error"
                                class="block w-full rounded-xl border border-slate-700 bg-slate-800/60 py-3 pl-12 pr-12 text-sm text-white shadow-inner shadow-black/10 outline-none transition placeholder:text-slate-500 hover:border-slate-600 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/10"
                            >
                        </div>
                        <p id="password-error" class="mt-2 hidden text-xs font-medium leading-5 text-rose-300" role="alert"></p>
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember" class="inline-flex cursor-pointer items-center gap-2.5 text-sm text-slate-300">
                            <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-blue-400 focus:ring-2 focus:ring-blue-400/30"
                            >
                            Ingat saya
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/30 transition duration-200 hover:-translate-y-0.5 hover:from-blue-500 hover:to-blue-500 hover:shadow-xl hover:shadow-blue-950/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-300 active:translate-y-0"
                    >
                        Masuk ke akun
                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                        </svg>
                    </button>
                </form>

                <div class="mt-5 border-t border-slate-700/80 pt-4 text-center">
                    <p class="text-xs leading-5 text-slate-400">Dengan masuk, Anda menyetujui kebijakan akses dan penggunaan platform NawaTara InternTrack.</p>
                </div>
            </div>
        </section>
    </main>
    <script>
        const loginForm = document.getElementById('login-form');
        const loginFields = {
            email: {
                input: document.getElementById('email'),
                message: document.getElementById('email-error'),
                icon: document.getElementById('email-error-icon'),
                text: 'Email atau username wajib diisi.'
            },
            password: {
                input: document.getElementById('password'),
                message: document.getElementById('password-error'),
                icon: document.getElementById('password-error-icon'),
                text: 'Password wajib diisi.'
            }
        };
        const setFieldError = (field, message = '') => {
            const hasError = message !== '';
            field.input.classList.toggle('border-rose-400', hasError);
            field.input.classList.toggle('ring-2', hasError);
            field.input.classList.toggle('ring-rose-500/20', hasError);
            field.input.classList.toggle('border-slate-700', !hasError);
            field.input.classList.toggle('focus:border-blue-400', !hasError);
            field.input.classList.toggle('focus:ring-blue-400/10', !hasError);
            field.input.setAttribute('aria-invalid', hasError ? 'true' : 'false');
            field.message.textContent = message;
            field.message.classList.toggle('hidden', !hasError);
            field.icon.classList.toggle('hidden', !hasError);
            field.icon.classList.toggle('flex', hasError);
        };

        loginForm.addEventListener('submit', (event) => {
            let firstInvalidField = null;

            Object.values(loginFields).forEach((field) => {
                const isEmpty = field.input.value.trim() === '';
                setFieldError(field, isEmpty ? field.text : '');
                if (isEmpty && firstInvalidField === null) {
                    firstInvalidField = field.input;
                }
            });

            if (firstInvalidField) {
                event.preventDefault();
                firstInvalidField.focus();
            }
        });

        Object.values(loginFields).forEach((field) => {
            field.input.addEventListener('input', () => {
                if (field.input.value.trim() !== '') {
                    setFieldError(field);
                }
            });
        });
    </script>
</body>
</html>
