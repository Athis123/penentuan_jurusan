<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPK MOORA SMK GRAFIKA') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --secondary: #1e40af;
            --accent: #60a5fa;
            --light: #f8fafc;
            --dark: #0f172a;
            --text: #1e293b;
            --text-light: #64748b;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background:
                linear-gradient(rgba(15, 23, 42, 0.82),
                    rgba(37, 99, 235, 0.85)),
                url('{{ asset('logo/bg-sekolah.jpg') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 1250px;
        }

        .auth-container {
            display: flex;
            min-height: 720px;
            border-radius: 30px;
            overflow: hidden;
            backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        /* =======================
           LEFT SIDE
        ======================= */

        .auth-banner {
            flex: 1;
            padding: 70px 55px;
            color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-banner::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -120px;
            left: -120px;
        }

        .auth-banner::after {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -100px;
            right: -80px;
        }

        .logo-area {
            position: relative;
            z-index: 2;
            margin-bottom: 35px;
        }

        .logo-area img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin-bottom: 20px;
            background: white;
            padding: 10px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        .logo-area h1 {
            font-size: 2.3rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 15px;
        }

        .logo-area p {
            font-size: 1rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
            max-width: 520px;
        }

        .feature-box {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-top: 40px;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 18px;
            backdrop-filter: blur(10px);
        }

        .feature-item h3 {
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .feature-item p {
            font-size: 0.9rem;
            opacity: 0.85;
            line-height: 1.5;
        }

        /* =======================
           RIGHT SIDE
        ======================= */

        .auth-content {
            width: 480px;
            background: rgba(255, 255, 255, 0.96);
            padding: 60px 45px;
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
            color: var(--dark);
            margin-bottom: 10px;
        }

        .auth-header p {
            color: var(--text-light);
            font-size: 0.98rem;
            line-height: 1.7;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text);
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            height: 56px;
            border-radius: 16px;
            border: 1px solid #dbeafe;
            background: #f8fafc;
            padding: 0 18px 0 50px;
            font-size: 0.98rem;
            font-family: inherit;
            transition: 0.3s ease;
            color: var(--text);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 0.92rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input {
            accent-color: var(--primary);
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            height: 58px;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg,
                    var(--primary),
                    var(--secondary));
            color: white;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(37, 99, 235, 0.35);
        }

        .auth-footer {
            margin-top: 30px;
            text-align: center;
            color: var(--text-light);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .input-error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 8px;
        }

        /* =======================
           RESPONSIVE
        ======================= */

        @media (max-width: 992px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-content {
                width: 100%;
            }

            .auth-banner {
                padding: 50px 30px;
            }

            .feature-box {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .auth-content {
                padding: 40px 25px;
            }

            .logo-area h1 {
                font-size: 1.8rem;
            }

            .auth-header h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="auth-container">

            <!-- LEFT -->
            <div class="auth-banner">

                <div class="logo-area">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">

                    <h1>
                        Sistem Pendukung Keputusan Penentuan Jurusan Siswa
                    </h1>

                    <p>
                        Sistem berbasis web menggunakan metode MOORA untuk membantu
                        menentukan jurusan terbaik bagi siswa SMK secara cepat,
                        objektif, dan akurat di SMK Grafika Yayasan Lektur.
                    </p>
                </div>

                <div class="feature-box">

                    <div class="feature-item">
                        <h3>📊 Metode MOORA</h3>
                        <p>
                            Perhitungan keputusan menggunakan metode yang
                            akurat dan objektif.
                        </p>
                    </div>

                    <div class="feature-item">
                        <h3>🎯 Penentuan Jurusan</h3>
                        <p>
                            Membantu siswa mendapatkan jurusan sesuai
                            kemampuan dan minat.
                        </p>
                    </div>

                    <div class="feature-item">
                        <h3>⚡ Sistem Modern</h3>
                        <p>
                            Tampilan modern, responsif, dan mudah digunakan
                            pada berbagai perangkat.
                        </p>
                    </div>

                    <div class="feature-item">
                        <h3>🏫 SMK Grafika</h3>
                        <p>
                            Dikembangkan untuk mendukung proses seleksi
                            jurusan di sekolah.
                        </p>
                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div class="auth-content">

                <div class="auth-header">
                    <h2>Login Sistem</h2>
                </div>

                {{ $slot }}

                <div class="auth-footer">
                    © {{ date('Y') }}
                    SMK Grafika Yayasan Lektur <br>
                    Sistem Pendukung Keputusan Metode MOORA
                </div>

            </div>

        </div>
    </div>

</body>

</html>
