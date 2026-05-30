<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RESPA-Jo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ========== WARNA DIUBAH MENJADI BIRU GRADASI SEPERTI LANDING ========== */
        :root {
            --primary-blue: #0054A6;
            --primary-blue-dark: #003d7a;
            --primary-blue-light: #e8f0fe;
            --secondary: #1a5276;
            --light-bg: #f8f9fa;
            --gradient: linear-gradient(135deg, #0054A6 0%, #003d7a 100%);
            --gradient-light: linear-gradient(135deg, rgba(0, 84, 166, 0.1) 0%, rgba(0, 61, 122, 0.1) 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-image: linear-gradient(135deg, rgba(0, 84, 166, 0.05) 0%, rgba(0, 61, 122, 0.05) 100%);
            overflow-x: hidden;
        }
        
        .container-fluid {
            width: 100%;
            min-height: 100vh;
            display: flex;
        }
        
        .left-panel {
            flex: 1;
            background: var(--gradient);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .left-panel::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 40%;
            animation: wave 15s infinite linear;
        }
        
        @keyframes wave {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
        
        .welcome-text {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            z-index: 1;
            animation: fadeInDown 1s ease;
        }
        
        .sub-text {
            font-size: 1rem;
            text-align: center;
            max-width: 80%;
            margin-bottom: 30px;
            z-index: 1;
            animation: fadeInUp 1s ease 0.3s both;
        }
        
        .system-features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            z-index: 1;
            animation: fadeIn 1s ease 0.6s both;
        }
        
        .feature {
            background: rgba(255, 255, 255, 0.15);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            width: 130px;
            backdrop-filter: blur(5px);
            transition: transform 0.3s ease;
        }
        
        .feature:hover {
            transform: translateY(-5px);
        }
        
        .feature i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .right-panel {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            animation: slideInRight 1s ease;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-img {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .logo-img img {
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 5px 15px rgba(0, 84, 166, 0.3);
            }
            50% {
                box-shadow: 0 5px 25px rgba(0, 84, 166, 0.6);
            }
            100% {
                box-shadow: 0 5px 15px rgba(0, 84, 166, 0.3);
            }
        }
        
        .hospital-name {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 18px;
            line-height: 1.3;
            margin-bottom: 5px;
        }
        
        .slogan {
            color: #555;
            font-size: 14px;
            margin-bottom: 15px;
            font-style: italic;
        }
        
        .system-name {
            font-weight: 600;
            color: var(--primary-blue);
            font-size: 16px;
            margin-bottom: 25px;
            padding: 8px 15px;
            background-color: rgba(0, 84, 166, 0.1);
            border-radius: 8px;
            display: inline-block;
        }
        
        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 84, 166, 0.25);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        
        .password-container {
            position: relative;
            margin-bottom: 5px;
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .forgot-password a {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .btn-login {
            background-color: var(--primary-blue);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: var(--primary-blue-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 84, 166, 0.3);
        }
        
        .support-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }
        
        .support-link a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }
        
        .support-link a:hover {
            text-decoration: underline;
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: 0;
        }
        
        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1;
        }
        
        .header-logo-icon {
            font-size: 3.0rem;
            padding: 10px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
        }
        
        .header-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-left: 8px;
            font-weight: 500;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
            100% {
                transform: translateY(0) rotate(360deg);
            }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Style tambahan untuk modal */
        .admin-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gradient-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .contact-item {
            padding: 12px;
            border-radius: 8px;
            background: var(--gradient-light);
            transition: transform 0.3s;
            margin-bottom: 10px;
        }

        .contact-item:hover {
            transform: translateY(-2px);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .captcha-code {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            letter-spacing: 2px;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef) !important;
            user-select: none;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Modal header gradasi biru */
        .modal-header {
            background: var(--gradient) !important;
            color: white;
        }
        
        @media (max-width: 992px) {
            .container-fluid {
                height: auto;
                min-height: unset;
            }
            
            .left-panel {
                display: none;
            }

            .right-panel {
                width: 100%;
                padding: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .login-container {
                margin: 20px;
                padding: 30px 20px;
            }
            
            .hospital-name {
                font-size: 16px;
            }
            
            .system-name {
                font-size: 14px;
            }
            
            .feature {
                width: 100px;
                padding: 10px;
                font-size: 12px;
            }

            .contact-item {
                padding: 10px;
            }
            
            .contact-icon {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Panel Kiri dengan Gradasi Biru -->
        <div class="left-panel">
            <div class="floating-elements">
                <div class="floating-element" style="width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s;"></div>
                <div class="floating-element" style="width: 60px; height: 60px; top: 70%; left: 80%; animation-delay: 2s;"></div>
                <div class="floating-element" style="width: 100px; height: 100px; top: 40%; left: 70%; animation-delay: 4s;"></div>
                <div class="floating-element" style="width: 50px; height: 50px; top: 80%; left: 20%; animation-delay: 6s;"></div>
                <div class="floating-element" style="width: 70px; height: 70px; top: 20%; left: 60%; animation-delay: 8s;"></div>
            </div>
            
            <div class="header-logo">
                <div class="header-logo-icon">
                    <img src="{{ asset('assets/Letter_RESPA-Jo.png') }}" alt="RESPA-Jo - Respirasi Sehat Pasien Aman Manguharjo" width="auto" height="85" loading="eager">
                </div>
            </div>
            <h1 class="welcome-text">Selamat Datang di Respirasi Sehat Pasien Aman Manguharjo!</h1>
            <p class="sub-text">Sistem Monitoring Terintegrasi Real-Time untuk Suhu, Kelembaban, & Tekanan Udara Ruangan Berbasis IoT</p>
            
            <div class="system-features">
                <div class="feature">
                    <i class="bi bi-thermometer-sun"></i>
                    <p>Suhu</p>
                </div>
                <div class="feature">
                    <i class="bi bi-droplet-fill"></i>
                    <p>Kelembaban</p>
                </div>
                <div class="feature">
                    <i class="bi bi-speedometer2"></i>
                    <p>Tekanan Udara</p>
                </div>
            </div>
        </div>
        
        <!-- Panel Kanan dengan Form Login -->
        <div class="right-panel">
            <div class="login-container">
                <div class="logo">
                    <div class="logo-img">
                        <!-- Logo Rumah Sakit -->
                        <img src="{{ asset('assets/logo-rspm-jatim-prov.png') }}" alt="Logo Rumah Sakit">
                    </div>
                    <h4 class="hospital-name">RUMAH SAKIT PARU <br> MANGUHARJO MADIUN</h4>
                    <p class="slogan">"Melayani Sepenuh Hati"</p>
                </div>

                <!-- Form Login -->
                <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="username" name="username" 
                                value="{{ old('username') }}" placeholder="Masukkan username" autofocus>
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                        </div>
                    </div>

                    <div class="password-container">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login" id="loginButton">
                        <span id="loginText">Login</span>
                        <div id="loginSpinner" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                    <div class="support-link">
                        Belum punya akun? <a href="#" id="contactAdminLink">Hubungi administrator IT</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Forgot Password -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--gradient); color: white;">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">
                        <i class="fas fa-key me-2"></i>Reset Password
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Masukkan email Anda untuk mereset password. Link reset akan dikirim ke email tersebut.
                    </div>
                    
                    <form id="forgotPasswordForm">
                        <div class="mb-3">
                            <label for="resetEmail" class="form-label">Email Terdaftar</label>
                            <input type="email" class="form-control" id="resetEmail" placeholder="nama@rspm-madiun.go.id" required>
                            <div class="form-text">Pastikan email yang dimasukkan sesuai dengan akun Anda</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Verifikasi</label>
                            <div class="row g-2">
                                <div class="col-8">
                                    <input type="text" class="form-control" id="captchaInput" placeholder="Masukkan kode verifikasi" required>
                                </div>
                                <div class="col-4">
                                    <div class="captcha-code bg-light border rounded p-2 text-center fw-bold" id="captchaDisplay">
                                        A1B2
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="resetPasswordBtn" onclick="resetPassword()">
                        <span id="resetText">Reset Password</span>
                        <div id="resetSpinner" class="spinner-border spinner-border-sm text-light" role="status" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hubungi Administrator -->
    <div class="modal fade" id="contactAdminModal" tabindex="-1" aria-labelledby="contactAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--gradient); color: white;">
                    <h5 class="modal-title" id="contactAdminModalLabel">
                        <i class="fas fa-user-cog me-2"></i>Hubungi Administrator IT
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="admin-avatar mx-auto mb-3">
                            <i class="fas fa-headset fa-2x text-primary"></i>
                        </div>
                        <h6>Tim IT Support RSP Manguharjo Madiun</h6>
                        <p class="text-muted">Siap membantu Anda 24/7</p>
                    </div>
                    
                    <div class="contact-info">
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-phone-alt text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Telepon</small>
                                    <strong>(0351) 123456</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <strong>it.support@rspm-madiun.go.id</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Jam Operasional</small>
                                    <strong>Senin - Minggu: 24 Jam</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Lokasi</small>
                                    <strong>Gedung IT Center Lt. 2</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Informasi:</strong> Untuk membuat akun baru, harap hubungi administrator IT dengan menyertakan:
                        <ul class="mb-0 mt-2">
                            <li>Nama lengkap dan NIP</li>
                            <li>Departemen/Bagian</li>
                            <li>Jabatan</li>
                            <li>Email institusi</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="contactAdmin()">
                        <i class="fas fa-phone me-1"></i> Hubungi Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @if(session('swal'))
    <script>
    Swal.fire({
        icon: "{{ session('swal.icon') }}",
        title: "{{ session('swal.title') }}",
        text: "{{ session('swal.text') }}",
        timer: 2500,
        showConfirmButton: false
    });
    </script>
    @endif

    @if($errors->any())
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Validasi gagal',
        text: @json($errors->first()),
    });
    </script>
    @endif

    <script>
        function swalAlert({icon = 'info', title = '', text = '', timer = null}) {
            return Swal.fire({
                icon,
                title,
                text,
                timer,
                showConfirmButton: !timer,
                confirmButtonColor: '#0054A6'
            });
        }

        // =============================================
        // LOGIN PAGE FUNCTIONALITY
        // =============================================

        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loginText = document.getElementById('loginText');
            const loginSpinner = document.getElementById('loginSpinner');

            loginForm.addEventListener('submit', function (e) {
                const username = document.getElementById('username').value.trim();
                const password = document.getElementById('password').value.trim();

                if (!username || !password) {
                    e.preventDefault();
                    swalAlert({
                        icon: 'warning',
                        title: 'Data tidak lengkap',
                        text: 'Username dan password wajib diisi'
                    });
                    return;
                }

                document.getElementById('loginText').textContent = 'Memproses...';
                document.getElementById('loginSpinner').style.display = 'inline-block';
                document.getElementById('loginButton').disabled = true;
            });
            
            // Forgot Password Link
            const forgotPasswordLink = document.getElementById('forgotPasswordLink');
            if (forgotPasswordLink) {
                forgotPasswordLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    showForgotPasswordModal();
                });
            }

            // Support Link (Hubungi Administrator)
            const contactAdminLink = document.getElementById('contactAdminLink');
            if (contactAdminLink) {
                contactAdminLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    showContactAdminModal();
                });
            }

            // Generate CAPTCHA untuk forgot password
            generateCaptcha();
        });

        // Tampilkan modal Forgot Password
        function showForgotPasswordModal() {
            generateCaptcha();
            const forgotPasswordModal = new bootstrap.Modal(document.getElementById('forgotPasswordModal'));
            forgotPasswordModal.show();
        }

        // Tampilkan modal Hubungi Administrator
        function showContactAdminModal() {
            const contactAdminModal = new bootstrap.Modal(document.getElementById('contactAdminModal'));
            contactAdminModal.show();
        }

        // Generate CAPTCHA code
        function generateCaptcha() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let captcha = '';
            for (let i = 0; i < 4; i++) {
                captcha += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            
            const captchaDisplay = document.getElementById('captchaDisplay');
            if (captchaDisplay) {
                captchaDisplay.textContent = captcha;
            }
            return captcha;
        }

        // Fungsi reset password
        function resetPassword() {
            const email = document.getElementById('resetEmail').value.trim();
            const captchaInput = document.getElementById('captchaInput').value.trim();
            const captchaDisplay = document.getElementById('captchaDisplay').textContent;

            if (!email) {
                swalAlert({ icon: 'warning', title: 'Email kosong', text: 'Masukkan email terlebih dahulu' });
                return;
            }

            if (!captchaInput) {
                swalAlert({ icon: 'warning', title: 'Verifikasi kosong', text: 'Masukkan kode verifikasi' });
                return;
            }

            if (captchaInput !== captchaDisplay) {
                swalAlert({ icon: 'error', title: 'Kode salah', text: 'Kode verifikasi tidak sesuai' });
                generateCaptcha();
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                swalAlert({ icon: 'error', title: 'Email tidak valid', text: 'Format email salah' });
                return;
            }

            swalAlert({
                icon: 'success',
                title: 'Berhasil',
                text: `Link reset dikirim ke ${email}`,
                timer: 3000
            });

            bootstrap.Modal.getInstance(
                document.getElementById('forgotPasswordModal')
            ).hide();

            document.getElementById('forgotPasswordForm').reset();
            generateCaptcha();
        }

        // Fungsi hubungi administrator
        function contactAdmin() {
            Swal.fire({
                icon: 'question',
                title: 'Hubungi IT Support',
                text: 'Telepon (0351) 123456?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0054A6'
            }).then(result => {
                if (result.isConfirmed) {
                    swalAlert({
                        icon: 'info',
                        title: 'Menghubungkan',
                        text: 'Mengarahkan ke aplikasi telepon...',
                        timer: 2000
                    });

                    setTimeout(() => {
                        bootstrap.Modal.getInstance(
                            document.getElementById('contactAdminModal')
                        ).hide();
                    }, 2000);
                }
            });
        }
    </script>
</body>
</html>