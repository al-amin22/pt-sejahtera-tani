<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT Borona Petani Sajahtera</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
        }

        .login-left {
            background: linear-gradient(to right, #2c7744, #5aaf6f);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .company-logo {
            width: 120px;
            height: 120px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 50px;
            color: #2c7744;
        }

        .login-right {
            padding: 40px;
        }

        .form-control:focus {
            border-color: #5aaf6f;
            box-shadow: 0 0 0 0.25rem rgba(90, 175, 111, 0.25);
        }

        .btn-primary {
            background-color: #2c7744;
            border-color: #2c7744;
        }

        .btn-primary:hover {
            background-color: #246236;
            border-color: #246236;
        }

        .forgot-link {
            color: #5aaf6f;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #246236;
        }

        @media (max-width: 768px) {
            .login-left {
                padding: 30px 20px;
            }

            .login-right {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="row g-0">
            <!-- Bagian Kiri dengan Informasi Perusahaan -->
            <div class="col-md-6 login-left">
                <div class="company-logo">
                    <i class="bi bi-tree-fill"></i>
                </div>
                <h2 class="mb-3">PT Borona Petani Sajahtera</h2>
                <p>Selamat datang di sistem manajemen pertanian terintegrasi kami. Masuk untuk mengakses dashboard dan fitur lengkap.</p>
                <div class="mt-4">
                    <div class="d-flex justify-content-center">
                        <div class="px-3"><i class="bi bi-shield-check fs-1"></i></div>
                        <div class="px-3"><i class="bi bi-graph-up fs-1"></i></div>
                        <div class="px-3"><i class="bi bi-cart-check fs-1"></i></div>
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan dengan Form Login -->
            <div class="col-md-6 login-right">
                <h3 class="mb-4 text-center">Masuk ke Akun Anda</h3>

                <!-- Session Status -->
                <div class="alert alert-info" role="alert">
                    Status sesi akan muncul di sini
                </div>

                <form method="POST" action="#">
                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus autocomplete="email">
                        <div class="form-text text-danger">Pesan error email</div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                        <div class="form-text text-danger">Pesan error password</div>
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">Ingat saya</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="#" class="forgot-link">Lupa password?</a>
                        <button type="submit" class="btn btn-primary px-4">Masuk</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p>Belum punya akun? <a href="#" class="forgot-link">Hubungi Administrator</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
