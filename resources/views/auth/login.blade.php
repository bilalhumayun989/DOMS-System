<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Kravio / DOMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);">

    <div class="w-full max-w-md" x-data="{ showPass: false }">

        {{-- Main Login Card --}}
        <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xl shadow-slate-200/50 space-y-6">
            
            {{-- Logo & Header --}}
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-900 text-white font-black text-xl shadow-md mb-2">
                    K
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kravio / DOMS</h2>
                <p class="text-xs font-semibold text-slate-400">Delivery Order Management System</p>
            </div>

            {{-- Demo Hint Banner --}}
            <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-xl bg-blue-600 text-white flex items-center justify-center flex-shrink-0 text-xs">
                        🔑
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-blue-900">Demo Account Credentials</div>
                        <div class="text-[10px] font-mono text-blue-700">admin@gmail.com · 12345678</div>
                    </div>
                </div>
                <button type="button" @click="document.getElementById('email').value='admin@gmail.com'; document.getElementById('password').value='12345678';"
                        class="px-2.5 py-1 rounded-lg bg-blue-600 text-white text-[10px] font-bold hover:bg-blue-700 transition-colors shadow-sm">
                    Fill Form
                </button>
            </div>

            {{-- Validation Error Alerts --}}
            @if(session('success'))
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700 text-center">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Email Input --}}
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-bold text-slate-700">Email Address</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email', 'admin@gmail.com') }}" required
                               class="w-full bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 rounded-xl pl-9 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                               placeholder="admin@gmail.com">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                </div>

                {{-- Password Input --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                        <a href="#" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800">Forgot?</a>
                    </div>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" id="password" name="password" value="12345678" required
                               class="w-full bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 rounded-xl pl-9 pr-10 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                               placeholder="••••••••">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showPass">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="showPass" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.962 8.962 0 012.122-.163c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-3.32-3.882a3 3 0 00-4.243-4.243m4.243 4.243L3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                        <span class="text-xs font-semibold text-slate-600">Remember me</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-lg shadow-slate-900/10 transition-all duration-200">
                    Sign In to Dashboard →
                </button>
            </form>

            {{-- Footer Text --}}
            <div class="text-center pt-2">
                <p class="text-[11px] font-semibold text-slate-400">DOMS v1.0 Delivery Operations &amp; Management</p>
            </div>

        </div>

    </div>

</body>
</html>
