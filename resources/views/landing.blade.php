<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESPA-Jo - Sistem Monitoring Suhu Server</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2E8B57;
            --primary-dark: #1f6b42;
            --secondary: #61C5C3;
            --accent: #e67e22;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --gradient: linear-gradient(135deg, #7ABF55 0%, #61C5C3 50%, #6DC18A 75%, #71C077 100%);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: #333; overflow-x: hidden; line-height: 1.6; }
        .navbar { background: white; padding: 15px 0; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
        .navbar-brand { font-weight: 800; color: var(--primary) !important; font-size: 28px; display: flex; align-items: center; }
        .navbar-brand img { width: 80px; margin-right: 10px; }
        .btn-login { background: var(--gradient); color: white; border-radius: 8px; padding: 8px 20px; font-weight: 600; border: none; box-shadow: 0 4px 15px rgba(46,139,87,0.3); }
        .hero { background: var(--gradient); padding: 100px 0 80px; position: relative; overflow: hidden; }
        .hero h1 { font-weight: 800; color: white; margin-bottom: 20px; font-size: 3rem; }
        .hero p { font-size: 1.2rem; color: white; margin-bottom: 30px; max-width: 600px; }
        .btn-hero { background-color: white; color: var(--primary); padding: 12px 30px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; margin-right: 15px; }
        .features { padding: 80px 0; background: var(--light); }
        .section-title { text-align: center; margin-bottom: 60px; }
        .section-title h2 { font-weight: 700; color: var(--primary); font-size: 2.5rem; }
        .feature-card { background: white; border-radius: 20px; padding: 40px 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.1); text-align: center; border-top: 5px solid var(--primary); transition: transform 0.3s; }
        .feature-card:hover { transform: translateY(-10px); }
        .feature-icon { width: 90px; height: 90px; margin: 0 auto 25px; background: var(--gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; }
        footer { background: var(--dark); color: white; padding: 70px 0 20px; }
        @media (max-width: 768px) { .hero h1 { font-size: 2rem; } .feature-card { margin-bottom: 20px; } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="{{ asset('assets/logo-rspm-jatim-prov.png') }}" alt="Logo">RESPA-Jo</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link" href="#">Beranda</a></li><li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li><li class="nav-item"><a class="btn btn-login ms-3" href="{{ route('login') }}">Login</a></li></ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6"><h1>RESPA-Jo - Sistem Monitoring Suhu Server</h1><p>Sistem monitoring real-time suhu ruang server untuk Rumah Sakit Paru Manguharjo Madiun.</p><a href="#features" class="btn btn-hero">Pelajari Lebih Lanjut</a><a href="{{ route('login') }}" class="btn btn-hero btn-hero-outline">Login</a></div>
                <div class="col-lg-6"><img src="{{ asset('assets/RUMAH SAKIT 1.png') }}" alt="Dashboard Monitoring" class="img-fluid"></div>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <div class="section-title"><h2>Fitur Utama RESPA-Jo</h2><p>Solusi lengkap untuk monitoring suhu ruangan berbasis IoT</p></div>
            <div class="row">
                <div class="col-md-4"><div class="feature-card"><div class="feature-icon"><i class="bi bi-thermometer-high"></i></div><h4>Monitoring Suhu Server</h4><p>Pemantauan suhu ruang server secara real-time dengan sensor IoT terintegrasi.</p></div></div>
                <div class="col-md-4"><div class="feature-card"><div class="feature-icon"><i class="bi bi-cloud-rain"></i></div><h4>Monitoring Kelembaban</h4><p>Pemantauan kelembaban ruangan untuk menjaga kondisi optimal perangkat server.</p></div></div>
                <div class="col-md-4"><div class="feature-card"><div class="feature-icon"><i class="bi bi-bell-fill"></i></div><h4>Notifikasi Real-time</h4><p>Peringatan instan via WhatsApp dan Telegram saat suhu melebihi batas normal.</p></div></div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container"><div class="text-center"><p>&copy; 2025 RESPA-Jo - Rumah Sakit Paru Manguharjo Madiun. All rights reserved.</p></div></div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>