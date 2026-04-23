<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AINA MOBILINDO') }}</title>

    <style>
        /* === PALET WARNA BIRU BARU === */
        :root {
            --primary: #667eea;
            /* Warna biru utama */
            --primary-light: #899bff;
            --primary-dark: #5a67d8;
            --secondary: #a3bffa;
            --accent: #dbeafe;
            --light: #f4f5f7;
            --dark: #2d3748;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text: #1a202c;
            --text-light: #4a5568;
        }

        .dark-mode {
            --primary: #899bff;
            --primary-light: #a3bffa;
            --primary-dark: #667eea;
            --secondary: #4a5568;
            --accent: #2d3748;
            --light: #1a202c;
            --dark: #e2e8f0;
            --card-bg: rgba(26, 32, 44, 0.95);
            --text: #e2e8f0;
            --text-light: #a0aec0;
        }

        /* ============================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color 0.3s, color 0.3s;
        }

        body {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            min-height: 100vh;
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            width: 100%;
        }

        .auth-container {
            display: flex;
            background: var(--card-bg);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            min-height: 650px;
        }

        /* Banner kiri HANYA menggunakan gradien biru, sesuai permintaan. */
        .auth-banner {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .auth-banner::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -100px;
            right: -100px;
        }

        .auth-banner::after {
            content: "";
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
            bottom: -80px;
            left: -80px;
        }

        .logo {
            margin-bottom: 30px;
            z-index: 1;
        }

        .logo h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .banner-text {
            max-width: 450px;
            z-index: 1;
        }

        .banner-text h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .banner-text p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.9;
        }

        .auth-content {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .auth-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .auth-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 0.95rem;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
            transition: all 0.3s;
            color: var(--text);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: var(--primary);
        }

        .remember-me label {
            margin-bottom: 0;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            box-shadow: 0 6px 8px rgba(102, 126, 234, 0.4);
            transform: translateY(-2px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
            color: var(--text-light);
            font-size: 1rem;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .auth-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-banner {
                padding: 40px 20px;
            }

            .logo h1 {
                font-size: 2rem;
            }

            .banner-text h2 {
                font-size: 1.8rem;
            }

            .auth-content {
                padding: 40px 30px;
            }
        }

        .input-error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-banner">
                <div class="logo">
                    <h1>AINA MOBILINDO</h1>
                </div>
                <div class="banner-text">
                    <p>Sistem ini menggunakan Metode MOORA untuk merangking alternatif mobil dengan cara menyeimbangkan
                        total kriteria keuntungan (manfaat) dan kriteria biaya (beban) guna menghasilkan keputusan yang
                        paling optimal.</p>
                </div>
            </div>

            <div class="auth-content">
                <div class="auth-header">
                    <h2>Masuk ke Akun Anda</h2>
                    <p>Gunakan username dan password Anda.</p>
                </div>

                {{ $slot }}

            </div>
        </div>
    </div>
</body>

</html>