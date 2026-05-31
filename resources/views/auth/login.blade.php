<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. Sipirok Indah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        body {
            background: #d1d5db;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 2rem;
            width: 100%;
            max-width: 360px;
        }
        .input-field {
            width: 100%;
            padding: 0.625rem 0.75rem 0.625rem 2.25rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .input-field:focus {
            border-color: #2c5e4e;
            box-shadow: 0 0 0 3px rgba(44,94,78,0.1);
        }
        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 0.625rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            width: 1rem;
            height: 1rem;
        }
        .input-action {
            position: absolute;
            right: 0.625rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .input-action:hover { color: #2c5e4e; }
        .btn-primary {
            width: 100%;
            background: #2c5e4e;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.7rem 1rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .btn-primary:hover { background: #1f4a3d; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,94,78,0.3); }
        .btn-primary:disabled { opacity: 0.7; transform: none; }
        label.field-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 0.375rem;
        }
        .form-group { margin-bottom: 1rem; }
        .alert-success {
            background: #eaf4f1;
            border: 1px solid rgba(44,94,78,0.2);
            color: #1f4a3d;
            border-radius: 12px;
            padding: 0.75rem;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 12px;
            padding: 0.75rem;
            font-size: 0.75rem;
            margin-bottom: 1rem;
            transition: opacity 0.5s;
        }
        .error-text { color: #dc2626; font-size: 0.7rem; margin-top: 0.25rem; }
        .divider { height: 1px; background: #f3f4f6; margin: 1.25rem 0; }
        .footer-link {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-link:hover { color: #2c5e4e; }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
            cursor: pointer;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <div class="login-card">

        {{-- Header --}}
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: white; border: 1px solid #f3f4f6; box-shadow: 0 1px 4px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; overflow: hidden;">
                <img src="{{ asset('images/Logo 1.jpg') }}"
                     alt="Logo"
                     style="width: 100%; height: 100%; object-fit: contain; padding: 4px;"
                     onerror="this.src='https://placehold.co/56x56?text=SP'">
            </div>
            <h1 style="font-size: 1rem; font-weight: 700; color: #2c5e4e; margin: 0 0 2px;">PT. Sipirok Indah</h1>
            <p style="font-size: 0.75rem; color: #9ca3af; margin: 0;">Sistem Manajemen Kebun Sawit</p>
        </div>

        {{-- Session Status --}}
        @if(session('status'))
        <div class="alert-success">
            <svg style="width:14px;height:14px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('status') }}
        </div>
        @endif

        {{-- Error --}}
        @if($errors->any())
        <div class="alert-error" id="error-message">
            <div style="display:flex;align-items:center;gap:6px;font-weight:600;margin-bottom:4px;">
                <svg style="width:14px;height:14px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Terjadi Kesalahan
            </div>
            <ul style="padding-left:1.25rem;margin:0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- No HP --}}
            <div class="form-group">
                <label class="field-label" for="no_hp">Username / No HP</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <input
                        id="no_hp" type="text" name="no_hp"
                        value="{{ old('no_hp') }}"
                        required autofocus autocomplete="tel"
                        placeholder="Masukkan Username / No HP"
                        oninput="validateNoHP(this)"
                        class="input-field @error('no_hp') border-red-400 @enderror">
                </div>
                @error('no_hp')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="field-label" for="password">Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <input
                        id="password" type="password" name="password"
                        required autocomplete="current-password"
                        placeholder="Masukkan password Anda"
                        class="input-field" style="padding-right: 2.5rem;"
                        @error('password') style="border-color: #f87171; padding-right: 2.5rem;" @enderror>
                    <button type="button" class="input-action" onclick="togglePassword()">
                        <svg id="eyeIcon" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            {{-- Remember --}}
            <label class="remember-label">
                <input type="checkbox" name="remember"
                    style="width:14px;height:14px;accent-color:#2c5e4e;cursor:pointer;">
                Ingat saya di perangkat ini
            </label>

            {{-- Submit --}}
            <button type="submit" id="submitBtn" class="btn-primary">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                <span id="submitText">Masuk</span>
            </button>
        </form>

        <div class="divider"></div>

        <div style="text-align:center;">
            <a href="{{ url('/') }}" class="footer-link">
                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Halaman Utama
            </a>
        </div>

    </div>

    <p style="position:fixed;bottom:1rem;left:0;right:0;text-align:center;font-size:0.7rem;color:rgba(255,255,255,0.25);margin:0;">
        © {{ date('Y') }} PT. Sipirok Indah
    </p>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        function validateNoHP(input) {
            const value = input.value.trim();
            if (value.length >= 3) {
                input.style.borderColor = '#2c5e4e';
            } else if (value.length > 0) {
                input.style.borderColor = '#f87171';
            } else {
                input.style.borderColor = '#e5e7eb';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const noHP = document.getElementById('no_hp').value.trim();
            const password = document.getElementById('password').value.trim();
            if (!noHP || !password) {
                e.preventDefault();
                if (!noHP) {
                    document.getElementById('no_hp').classList.add('shake');
                    setTimeout(() => document.getElementById('no_hp').classList.remove('shake'), 500);
                }
                if (!password) {
                    document.getElementById('password').classList.add('shake');
                    setTimeout(() => document.getElementById('password').classList.remove('shake'), 500);
                }
                return false;
            }
            const submitBtn = document.getElementById('submitBtn');
            document.getElementById('submitText').textContent = 'Memproses...';
            submitBtn.disabled = true;
            return true;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                    setTimeout(() => errorMessage.style.display = 'none', 500);
                }, 5000);
            }
            const noHPInput = document.getElementById('no_hp');
            if (!noHPInput.value) noHPInput.focus();
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });
    </script>
</body>
</html>