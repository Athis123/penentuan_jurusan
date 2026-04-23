<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('auth.authenticate') }}">
        @csrf

        <!-- Username -->
        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-with-icon">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </span>
                <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username"
                    value="{{ old('username') }}" required autofocus>
            </div>
            <x-input-error :messages="$errors->get('username')" class="input-error-message" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-with-icon">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM9 8V6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9z" />
                    </svg>
                </span>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required
                    autocomplete="current-password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="input-error-message" />
        </div>

        <!-- Show Password & Forgot Password -->
        <div class="remember-forgot">
            <div class="remember-me">
                <input type="checkbox" id="show_password" onclick="togglePasswordVisibility()">
                <label for="show_password">Tampilkan Password</label>
            </div>

            {{-- Anda bisa mengaktifkan ini jika sudah punya fitur lupa password --}}
            {{-- @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-password">Lupa Password?</a>
            @endif --}}
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary">
            Masuk
        </button>

    </form>
</x-guest-layout>
<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('password');
        const checkbox = document.getElementById('show_password');
        input.type = checkbox.checked ? 'text' : 'password';
    }
</script>