<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RESPA-Jo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #2E8B57; --secondary: #61C5C3; --gradient: linear-gradient(135deg, #7ABF55 0%, #61C5C3 50%, #6DC18A 100%); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #FFFFFF; font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; background-image: linear-gradient(135deg, rgba(122,191,85,0.05) 0%, rgba(97,197,195,0.05) 100%); }
        .container-fluid { width: 100%; min-height: 100vh; display: flex; }
        .left-panel { flex: 1; background: var(--gradient); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; color: white; position: relative; overflow: hidden; }
        .left-panel::before { content: ''; position: absolute; width: 200%; height: 200%; background: rgba(255,255,255,0.1); border-radius: 40%; animation: wave 15s infinite linear; }
        @keyframes wave { 0% { transform: translate(-50%, -50%) rotate(0deg); } 100% { transform: translate(-50%, -50%) rotate(360deg); } }
        .welcome-text { font-size: 2.5rem; font-weight: 700; margin-bottom: 20px; text-align: center; z-index: 1; }
        .sub-text { font-size: 1.2rem; text-align: center; max-width: 80%; margin-bottom: 30px; z-index: 1; }
        .right-panel { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px; }
        .login-container { width: 100%; max-width: 420px; background: white; padding: 40px 30px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: relative; overflow: hidden; }
        .login-container::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: var(--gradient); }
        .logo-img { text-align: center; margin-bottom: 30px; }
        .logo-img img { max-width: 150px; }
        .hospital-name { font-weight: 700; color: var(--primary); text-align: center; margin-bottom: 5px; }
        .slogan { color: #555; font-size: 14px; text-align: center; margin-bottom: 25px; font-style: italic; }
        .form-control { padding: 12px 15px; border-radius: 8px; border: 1px solid #ddd; }
        .form-control:focus { border-color: var(--secondary); box-shadow: 0 0 0 0.2rem rgba(97,197,195,0.25); }
        .btn-login { background: var(--primary); border: none; border-radius: 8px; padding: 12px; font-weight: 600; width: 100%; transition: all 0.3s; }
        .btn-login:hover { background: #1f6b42; transform: translateY(-2px); }
        @media (max-width: 992px) { .left-panel { display: none; } .right-panel { width: 100%; } }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="left-panel">
            <h1 class="welcome-text">RESPA-Jo</h1>
            <p class="sub-text">Sistem monitoring terintegrasi untuk suhu ruang server dengan teknologi IoT</p>
        </div>
        <div class="right-panel">
            <div class="login-container">
                <div class="logo-img"><img src="{{ asset('assets/logo-rspm-jatim-prov.png') }}" alt="Logo"></div>
                <h4 class="hospital-name">RUMAH SAKIT PARU MANGUHARJO MADIUN</h4>
                <p class="slogan">"Melayani Sepenuh Hati"</p>
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
                    <div class="mb-3"><label for="username" class="form-label">Username</label><input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" autofocus></div>
                    <div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password"></div>
                    <button type="submit" class="btn btn-primary btn-login">Login</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @if(session('swal'))
    <script>Swal.fire({ icon: "{{ session('swal.icon') }}", title: "{{ session('swal.title') }}", text: "{{ session('swal.text') }}", timer: 2500, showConfirmButton: false });</script>
    @endif
</body>
</html>