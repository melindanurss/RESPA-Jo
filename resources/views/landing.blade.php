<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESPA-Jo - Respirasi Sehat Pasien Aman Manguharjo</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"></noscript>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0054A6;
            --primary-blue-dark: #003d7a;
            --primary-blue-light: #e8f0fe;
            --secondary: #1a5276;
            --light: #f5f7fa;
            --dark: #1a2a3a;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --white: #ffffff;
            --gradient-blue: linear-gradient(135deg, #0054A6 0%, #003d7a 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --card-hover-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Animasi Global */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s ease;
        }
        
        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s ease;
        }
        
        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        .scale-in {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.6s ease;
        }
        
        .scale-in.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* Header & Navigation */
        .top-bar {
            background: var(--primary-blue-dark);
            color: white;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .top-bar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            transition: 0.3s;
        }
        
        .top-bar a:hover {
            color: var(--secondary);
        }
        
        .social-top a {
            margin-right: 12px;
            font-size: 16px;
        }
        
        .navbar {
            background: white;
            padding: 15px 0;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .navbar-scrolled {
            padding: 10px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            padding: 0;
            margin: 0;
            line-height: 1;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            height: 85px;
            width: auto;
            transition: all 0.3s ease;
            object-fit: contain;
        }
        
        .navbar-scrolled .navbar-brand img {
            height: 70px;
        }
        
        @media (min-width: 992px) {
            .navbar .container {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .navbar-brand {
                flex-shrink: 0;
            }
            .navbar-collapse {
                flex-grow: 0;
            }
        }
        
        .navbar-toggler {
            border: none;
            padding: 8px 12px;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px var(--primary-blue);
            outline: none;
        }
        
        .nav-link {
            color: var(--dark) !important;
            font-weight: 500;
            margin: 0 6px;
            transition: 0.3s;
            position: relative;
            padding: 8px 14px !important;
            border-radius: 8px;
            font-size: 15px;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary-blue) !important;
            background: var(--primary-blue-light);
        }
        
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: var(--primary-blue);
            transition: all 0.3s;
            transform: translateX(-50%);
        }
        
        .nav-link:hover:after, .nav-link.active:after {
            width: 60%;
        }
        
        .btn-login {
            background: var(--gradient-blue);
            color: white;
            border-radius: 10px;
            padding: 8px 24px;
            font-weight: 600;
            transition: 0.3s;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 84, 166, 0.25);
            margin-left: 8px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 84, 166, 0.35);
            color: white;
        }

        /* Slogan Banner */
        .slogan-banner {
            background: var(--gradient-blue);
            color: white;
            padding: 15px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .slogan-banner:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .slogan-text {
            font-size: 1.4rem;
            font-weight: 600;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            letter-spacing: 1px;
        }
        
        .slogan-text .heart {
            color: #ff6b6b;
            margin: 0 10px;
            animation: heartbeat 1.5s ease-in-out infinite both;
        }
        
        @keyframes heartbeat {
            from { transform: scale(1); transform-origin: center center; animation-timing-function: ease-out; }
            10% { transform: scale(0.91); animation-timing-function: ease-in; }
            17% { transform: scale(0.98); animation-timing-function: ease-out; }
            33% { transform: scale(0.87); animation-timing-function: ease-in; }
            45% { transform: scale(1); animation-timing-function: ease-out; }
        }

        /* HERO SECTION - UPDATE DENGAN WAVE BACKGROUND INTERAKTIF */
        .hero {
            background: linear-gradient(135deg, #0054A6 0%, #1a5276 50%, #003d7a 100%);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        /* Efek gelombang (wave) bergerak smooth */
        .hero .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }
        
        .hero .wave-bg svg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.15;
        }
        
        .hero .wave-bg .wave1 {
            animation: waveMove1 20s ease-in-out infinite;
        }
        
        .hero .wave-bg .wave2 {
            animation: waveMove2 18s ease-in-out infinite;
        }
        
        .hero .wave-bg .wave3 {
            animation: waveMove3 22s ease-in-out infinite;
        }
        
        @keyframes waveMove1 {
            0% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(-5%) translateY(-3%); }
            100% { transform: translateX(0) translateY(0); }
        }
        
        @keyframes waveMove2 {
            0% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(5%) translateY(2%); }
            100% { transform: translateX(0) translateY(0); }
        }
        
        @keyframes waveMove3 {
            0% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(-3%) translateY(4%); }
            100% { transform: translateX(0) translateY(0); }
        }
        
        /* Elemen dekoratif floating (mirip efek login page) */
        .hero .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }
        
        .hero .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: floatSmooth 12s infinite ease-in-out;
        }
        
        @keyframes floatSmooth {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0) rotate(360deg); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            font-size: 3.2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 1.2rem;
            color: white;
            margin-bottom: 30px;
            max-width: 600px;
            opacity: 0.95;
        }
        
        .btn-hero {
            background-color: white;
            color: var(--primary-blue-dark);
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 10px;
        }
        
        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: var(--primary-blue);
        }
        
        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 10px 28px;
        }
        
        .btn-hero-outline:hover {
            background: white;
            color: var(--primary-blue);
            transform: translateY(-2px);
        }
        
        .hero-image {
            position: relative;
            text-align: center;
            z-index: 2;
        }
        
        .hero-image-container {
            position: relative;
            display: inline-block;
            border-radius: 15px;
            overflow: hidden;
            border: none;
            outline: none;
            box-shadow: none;
        }
        
        .hero-image img {
            max-width: 100%;
            border: none;
            box-shadow: none;
            border-radius: 15px;
            opacity: 0.95;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
            transition: all 0.5s ease;
        }
        
        .hero-image img:hover {
            opacity: 1;
            transform: scale(1.02);
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.15));
        }

        /* Stats Section */
        .stats {
            padding: 80px 0;
            background: white;
            color: var(--dark);
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            background: var(--gradient-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--primary-blue);
        }
        
        .stat-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--dark);
        }

        /* About Section */
        .about {
            padding: 100px 0;
            background-color: var(--light);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-weight: 700;
            color: var(--primary-blue);
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
            font-size: 2.2rem;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background: var(--gradient-blue);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 10px;
        }
        
        .section-title p {
            color: #666;
            max-width: 700px;
            margin: 30px auto 0;
            font-size: 1.1rem;
        }
        
        .hospital-profile-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            margin-bottom: 50px;
            border-left: 6px solid var(--primary-blue);
        }
        
        .hospital-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .hospital-header h3 {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        
        .hospital-motto {
            font-style: italic;
            color: var(--secondary);
            font-weight: 500;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .hospital-description {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .profile-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin: 50px 0;
        }
        
        .highlight-card {
            background: var(--light);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            border-top: 4px solid var(--primary-blue);
            transition: transform 0.3s ease;
        }
        
        .highlight-card:hover {
            transform: translateY(-5px);
        }
        
        .highlight-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: var(--primary-blue-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 24px;
        }
        
        .highlight-card h4 {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 1.2rem;
        }
        
        .highlight-card p {
            color: #666;
            line-height: 1.6;
        }
        
        .vision-mission-section {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        
        .vision-mission-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        
        .vision-card, .mission-card {
            padding: 30px;
            border-radius: 15px;
        }
        
        .vision-card {
            background: var(--light);
            border-left: 4px solid var(--primary-blue);
        }
        
        .mission-card {
            background: var(--light);
            border: 2px solid var(--primary-blue);
        }
        
        .vision-card h3, .mission-card h3 {
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            color: var(--primary-blue);
        }
        
        .vision-card h3 i, .mission-card h3 i {
            color: var(--primary-blue);
            margin-right: 15px;
            font-size: 2rem;
        }
        
        .vision-card p {
            font-size: 1rem;
            line-height: 1.7;
            color: #555;
            text-align: justify;
        }
        
        .mission-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .mission-item:hover {
            transform: translateX(10px);
        }
        
        .mission-item i {
            color: var(--primary-blue);
            margin-right: 15px;
            margin-top: 2px;
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .mission-item div {
            color: #555;
            line-height: 1.6;
        }

        /* Maps Section */
        .maps-section {
            padding: 80px 0;
            background: var(--white);
        }
        
        .maps-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            height: 450px;
        }
        
        .maps-info {
            background: var(--light);
            padding: 30px;
            border-radius: 15px;
            height: 100%;
        }
        
        .maps-info h4 {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .maps-details {
            list-style: none;
            padding: 0;
        }
        
        .maps-details li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }
        
        .maps-details i {
            color: var(--primary-blue);
            margin-right: 12px;
            margin-top: 3px;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .btn-directions {
            background: var(--gradient-blue);
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-top: 20px;
            transition: 0.3s;
            border: none;
        }
        
        .btn-directions:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 84, 166, 0.3);
            color: white;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: var(--light);
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            height: 100%;
            text-align: center;
            border-top: 5px solid var(--primary-blue);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--primary-blue-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 32px;
            transition: 0.3s;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .feature-card h4 {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .feature-card p {
            color: #666;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 0;
        }

        /* Social Media Section */
        .social-media {
            padding: 80px 0;
            background: white;
        }
        
        .social-section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .social-section-title h2 {
            font-weight: 700;
            color: var(--primary-blue);
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
            font-size: 2.2rem;
        }
        
        .social-section-title h2:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background: var(--gradient-blue);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 10px;
        }
        
        .social-section-title p {
            color: #666;
            max-width: 700px;
            margin: 30px auto 0;
            font-size: 1.1rem;
        }
        
        .social-simple-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .social-simple-card {
            background: white;
            border-radius: 10px;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .social-simple-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        
        .social-simple-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .social-simple-icon.website { background: var(--gradient-blue); }
        .social-simple-icon.instagram { background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D); }
        .social-simple-icon.facebook { background: #1877F2; }
        .social-simple-icon.youtube { background: #CD201F; }
        .social-simple-icon.tiktok { background: #000000; }
        .social-simple-icon.whatsapp { background: #25D366; }
        
        .social-simple-card h4 {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
            font-size: 1.2rem;
        }
        
        .social-simple-card p {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
            flex-grow: 1;
        }
        
        .btn-social-simple {
            display: inline-block;
            padding: 10px 20px;
            background: var(--primary-blue);
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            border: none;
            font-size: 14px;
        }
        
        .btn-social-simple:hover {
            background: var(--primary-blue-dark);
            color: white;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 70px 0 20px;
        }
        
        .footer-logo {
            font-weight: 700;
            color: white;
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .footer-logo img {
            width: 120px;
            height: auto;
            margin-right: 10px;
        }
        
        .footer-about p {
            color: #bbb;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: 0.3s;
        }
        
        .social-links a:hover {
            background: var(--primary-blue);
            transform: translateY(-3px);
        }
        
        .footer-heading {
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .footer-heading:after {
            content: '';
            position: absolute;
            width: 40px;
            height: 3px;
            background: var(--primary-blue);
            bottom: 0;
            left: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: #bbb;
            text-decoration: none;
            transition: 0.3s;
            display: block;
            font-size: 14px;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .contact-info li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            font-size: 14px;
        }
        
        .contact-info i {
            margin-right: 10px;
            color: var(--primary-blue);
            margin-top: 3px;
            flex-shrink: 0;
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 50px;
            border-top: 1px solid #444;
            color: #bbb;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: white;
                padding: 20px;
                border-radius: 12px;
                margin-top: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            .nav-link {
                padding: 10px 15px !important;
                margin: 2px 0;
            }
            .btn-login {
                display: inline-block;
                margin-top: 10px;
                text-align: center;
                width: 100%;
            }
            .navbar-nav {
                gap: 5px;
            }
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 0.95rem;
            }
            .stats, .social-media { padding: 50px 0; }
            .about, .features { padding: 60px 0; }
            .maps-section { padding: 50px 0; }
            footer { padding: 50px 0 20px; }
            .stat-number { font-size: 1.5rem; }
            .stat-label { font-size: 0.75rem; }
            .section-title h2 { font-size: 1.8rem; }
            .top-bar { display: none; }
            .hero-image { margin-top: 30px; }
            .hero-image img { max-width: 90%; }
            .slogan-text { font-size: 1rem; }
            .navbar-brand img { height: 60px !important; }
            .hospital-profile-card { padding: 25px 15px; }
            .vision-mission-section { padding: 30px 20px; }
            .vision-mission-grid { grid-template-columns: 1fr; gap: 20px; }
            .profile-highlights { grid-template-columns: 1fr; gap: 15px; }
            .feature-grid { grid-template-columns: 1fr; }
            .social-simple-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .feature-card { padding: 25px 20px; }
            .social-simple-card { padding: 18px 14px; }
            .highlight-card { padding: 20px 15px; }
            .maps-container { height: 260px; }
            .footer-logo img { width: 80px; }
            .vision-card p { font-size: 0.9rem; }
        }

        @media (max-width: 480px) {
            .stat-number { font-size: 1.2rem; }
            .stat-icon { width: 50px; height: 50px; font-size: 20px; }
            .social-simple-grid { grid-template-columns: 1fr; }
            .navbar-brand img { height: 50px !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .fade-in, .slide-in-left, .slide-in-right, .scale-in {
                opacity: 1 !important;
                transform: none !important;
            }
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        @media (min-width: 769px) {
            .hero-image img {
                animation: float 6s ease-in-out infinite;
            }
        }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="tel:0351462427"><i class="bi bi-telephone"></i> IGD 24 Jam: (0351) 462427</a>
                    <a href="mailto:rspmmanguharjo@gmail.com"><i class="bi bi-envelope"></i> rspmmanguharjo@gmail.com</a>
                </div>
                <div class="social-top">
                    <a href="https://www.instagram.com/rspmanguharjo.prov.jatim/" target="_blank"><i class="bi bi-instagram"></i></a>
                    <a href="https://web.facebook.com/rspmanguharjo/?_rdc=1&_rdr" target="_blank"><i class="bi bi-facebook"></i></a>
                    <a href="http://www.youtube.com/@RSParuManguharjoProvJatim" target="_blank"><i class="bi bi-youtube"></i></a>
                    <a href="https://www.tiktok.com/@rspmanguharjo.prov.jatim?_r=1&_t=ZS-9145SfXKLOW" target="_blank"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header & Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/Logo_RESPA-Jo.png') }}" alt="RESPA-Jo - Respirasi Sehat Pasien Aman Manguharjo" width="auto" height="85" loading="eager">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#landing">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#visi">Visi & Misi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#maps">Lokasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#social">Media Sosial</a></li>
                    <li class="nav-item ms-lg-2"><a href="{{ route('login') }}" class="btn-login">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Slogan Banner -->
    <div class="slogan-banner">
        <div class="container">
            <div class="slogan-text">
                <i class="bi bi-heart"></i>
                MELAYANI SEPENUH HATI
                <i class="bi bi-heart"></i>
            </div>
        </div>
    </div>

    <!-- Hero Section dengan Background Gradasi + Gelombang Interaktif -->
    <section id="landing" class="hero">
        <!-- Floating elements (seperti login page) -->
        <div class="floating-elements">
            <div class="floating-element" style="width: 80px; height: 80px; top: 10%; left: 5%; animation-delay: 0s;"></div>
            <div class="floating-element" style="width: 60px; height: 60px; top: 70%; left: 90%; animation-delay: 2s;"></div>
            <div class="floating-element" style="width: 100px; height: 100px; top: 40%; left: 85%; animation-delay: 4s;"></div>
            <div class="floating-element" style="width: 50px; height: 50px; top: 80%; left: 10%; animation-delay: 6s;"></div>
            <div class="floating-element" style="width: 70px; height: 70px; top: 20%; left: 80%; animation-delay: 8s;"></div>
        </div>
        
        <!-- Wave background bergerak smooth -->
        <div class="wave-bg">
            <svg class="wave1" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="rgba(255,255,255,0.2)" d="M0,192L48,197.3C96,203,192,213,288,208C384,203,480,181,576,176C672,171,768,181,864,197.3C960,213,1056,235,1152,234.7C1248,235,1344,213,1392,202.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            <svg class="wave2" viewBox="0 0 1440 320" preserveAspectRatio="none" style="position:absolute; bottom:0; left:0;">
                <path fill="rgba(255,255,255,0.15)" d="M0,256L48,245.3C96,235,192,213,288,218.7C384,224,480,256,576,261.3C672,267,768,245,864,224C960,203,1056,181,1152,176C1248,171,1344,181,1392,186.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            <svg class="wave3" viewBox="0 0 1440 320" preserveAspectRatio="none" style="position:absolute; bottom:0; left:0;">
                <path fill="rgba(255,255,255,0.1)" d="M0,224L48,234.7C96,245,192,267,288,250.7C384,235,480,181,576,165.3C672,149,768,171,864,197.3C960,224,1056,256,1152,261.3C1248,267,1344,245,1392,234.7L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="slide-in-left">Respirasi Sehat Pasien Aman Manguharjo</h1>
                    <p class="slide-in-left">RESPA-Jo menghadirkan sistem pemantauan terintegrasi untuk suhu ruangan, kelembaban udara, dan kualitas lingkungan secara real-time di <strong>RSPM Madiun</strong>.</p>
                    <div class="mt-4 slide-in-left">
                        <a href="#about" class="btn btn-hero btn-hero-outline">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-lg-6 hero-image">
                    <div class="hero-image-container">
                        <img src="{{ asset('assets/RUMAH SAKIT 2.png') }}" alt="Dashboard Monitoring RESPA-Jo" class="img-fluid slide-in-right" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="row text-center g-3 justify-content-center">
                <div class="col-md-2 col-6 stat-item"><div class="stat-icon scale-in"><i class="bi bi-thermometer-half"></i></div><div class="stat-number">±0.5°C</div><div class="stat-label">Akurasi Suhu</div></div>
                <div class="col-md-2 col-6 stat-item"><div class="stat-icon scale-in"><i class="bi bi-droplet-half"></i></div><div class="stat-number">±3%</div><div class="stat-label">Akurasi Kelembaban</div></div>
                <div class="col-md-2 col-6 stat-item"><div class="stat-icon scale-in"><i class="bi bi-speedometer2"></i></div><div class="stat-number">±1 hPa</div><div class="stat-label">Akurasi Tekanan Udara</div></div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="hospital-profile-card fade-in">
                <div class="hospital-header">
                    <h3>Profil Rumah Sakit</h3>
                    <div class="hospital-motto">"Melayani dengan Sepenuh Hati"</div>
                    <p class="hospital-description">Sebagai rumah sakit paru terkemuka di Jawa Timur, Rumah Sakit Paru Manguharjo Madiun telah berkomitmen selama bertahun-tahun dalam memberikan pelayanan kesehatan terbaik khususnya di bidang penyakit paru dan pernapasan. Dengan fasilitas modern dan tenaga medis yang profesional, kami siap melayani masyarakat dengan standar tertinggi.</p>
                </div>
                <div class="profile-highlights">
                    <div class="highlight-card scale-in"><div class="highlight-icon"><i class="bi bi-building"></i></div><h4>Fasilitas Lengkap</h4><p>Memiliki ruang IGD 24 jam, ICU, ruang isolasi, laboratorium modern, dan farmasi lengkap untuk mendukung pelayanan kesehatan komprehensif.</p></div>
                    <div class="highlight-card scale-in"><div class="highlight-icon"><i class="bi bi-people"></i></div><h4>Tenaga Profesional</h4><p>Didukung oleh dokter spesialis paru, perawat terlatih, dan tenaga medis profesional yang berpengalaman dalam menangani kasus pernapasan.</p></div>
                    <div class="highlight-card scale-in"><div class="highlight-icon"><i class="bi bi-heart-pulse"></i></div><h4>Layanan Unggulan</h4><p>Spesialisasi dalam penanganan TB, asma, pneumonia, COPD, dan berbagai penyakit pernapasan lainnya dengan pendekatan holistik.</p></div>
                    <div class="highlight-card scale-in"><div class="highlight-icon"><i class="bi bi-geo-alt"></i></div><h4>Akses Mudah</h4><p>Berlokasi strategis di pusat kota Madiun dengan akses transportasi yang mudah dan area parkir yang luas untuk kenyamanan pasien.</p></div>
                    <div class="highlight-card scale-in"><div class="highlight-icon"><i class="bi bi-shield-check"></i></div><h4>Akreditasi Paripurna</h4><p>Telah meraih akreditasi paripurna dari Komisi Akreditasi Rumah Sakit (KARS) sebagai bukti komitmen terhadap kualitas pelayanan kesehatan.</p></div>
                    <div class="highlight-card scale-in"><div class="highlight-icon"><i class="bi bi-capsule"></i></div><h4>Penanganan Komprehensif</h4><p>Menyediakan layanan dari pencegahan, diagnosis, pengobatan, hingga rehabilitasi untuk berbagai penyakit paru dan pernapasan.</p></div>
                </div>
            </div>

            <div class="vision-mission-section fade-in">
                <div id="visi" class="vision-mission-section fade-in"></div>
                <div class="vision-mission-grid">
                    <div class="vision-card scale-in"><h3><i class="bi bi-eye"></i> Visi RESPA-Jo</h3><p>Menjadi sistem monitoring lingkungan ruangan berbasis Internet of Things (IoT) yang inovatif, responsif, dan terpercaya dalam mendukung terciptanya lingkungan perawatan pasien yang sehat, nyaman, aman, dan terpantau secara real-time melalui pemantauan suhu, kelembaban, dan tekanan udara secara digital. RESPA-Jo hadir sebagai solusi monitoring modern yang mampu membantu tenaga kesehatan dalam mengawasi kondisi lingkungan ruangan secara lebih efektif, efisien, dan terintegrasi guna mendukung peningkatan kualitas pelayanan kesehatan di Rumah Sakit Paru Manguharjo. Selain itu, RESPA-Jo juga diharapkan mampu mendukung transformasi teknologi kesehatan berbasis IoT melalui penerapan sistem deteksi perubahan kondisi lingkungan dan notifikasi otomatis untuk meningkatkan respon terhadap perubahan kondisi ruangan pasien secara cepat dan akurat.</p></div>
                    <div class="mission-card scale-in"><h3><i class="bi bi-bullseye"></i> Misi RESPA-Jo</h3>
                        <div class="mission-item"><i class="bi bi-check-circle"></i><div>Memantau suhu, kelembaban, dan tekanan udara ruangan secara real-time berbasis IoT.</div></div>
                        <div class="mission-item"><i class="bi bi-check-circle"></i><div>Mendeteksi perubahan kondisi lingkungan menggunakan algoritma State Change Detection.</div></div>
                        <div class="mission-item"><i class="bi bi-check-circle"></i><div>Memberikan notifikasi otomatis terhadap perubahan kondisi ruangan.</div></div>
                        <div class="mission-item"><i class="bi bi-check-circle"></i><div>Meningkatkan efisiensi monitoring melalui sistem digital yang mudah diakses.</div></div>
                        <div class="mission-item"><i class="bi bi-check-circle"></i><div>Mendukung transformasi teknologi kesehatan berbasis IoT di Rumah Sakit Paru Manguharjo.</div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Maps Section -->
    <section id="maps" class="maps-section">
        <div class="container">
            <div class="section-title"><h2 class="fade-in">Lokasi Rumah Sakit</h2><p class="fade-in">Temukan lokasi Rumah Sakit Paru Mangunharjo Madiun dengan mudah</p></div>
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="maps-container fade-in" style="background:#e8f0fe;display:flex;align-items:center;justify-content:center;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.207697479377!2d111.51677407401791!3d-7.629999999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79be3896aba6a5%3A0x6a75b6c5b3c5b3c5!2sRumah%20Sakit%20Paru%20Manguharjo%20Madiun!5e0!3m2!1sid!2sid!4v1712345678901!5m2!1sid!2sid" width="100%" height="100%" style="border:0;min-height:450px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi RSPM Madiun"></iframe>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="maps-info fade-in">
                        <h4>Informasi Lokasi</h4>
                        <ul class="maps-details">
                            <li><i class="bi bi-geo-alt-fill"></i><div><strong>Alamat Lengkap:</strong><br>Jl. Yos Sudarso No. 108-112<br>Manguharjo, Kota Madiun<br>Jawa Timur 63151</div></li>
                            <li><i class="bi bi-clock-fill"></i><div><strong>Jam Operasional:</strong><br>IGD: 24 Jam Setiap Hari<br>Poli: 07.00 - 14.00 WIB</div></li>
                            <li><i class="bi bi-telephone-fill"></i><div><strong>Telepon:</strong><br>IGD: (0351) 462427<br>Kantor: (0351) 464816</div></li>
                        </ul>
                        <a href="https://maps.google.com/maps?q=Rumah+Sakit+Paru+Manguharjo+Madiun" target="_blank" class="btn btn-directions"><i class="bi bi-geo-alt me-2"></i>Dapatkan Petunjuk Arah</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-title"><h2 class="fade-in">Fitur Utama RESPA-Jo</h2></div>
            <div class="feature-grid">
                <div class="feature-card scale-in"><div class="feature-icon"><i class="bi bi-thermometer-sun"></i></div><h4>Monitoring Suhu Udara</h4><p>Sistem mampu memantau suhu ruangan secara langsung (real-time) menggunakan sensor BME280 untuk membantu menjaga kenyamanan dan kestabilan kondisi ruangan pasien.</p></div>
                <div class="feature-card scale-in"><div class="feature-icon"><i class="bi bi-droplet-fill"></i></div><h4>Monitoring Kelembaban Udara</h4><p>Sistem dapat mendeteksi tingkat kelembaban udara secara otomatis sehingga kondisi ruangan tetap nyaman dan tidak terlalu lembab maupun terlalu kering.</p></div>
                <div class="feature-card scale-in"><div class="feature-icon"><i class="bi bi-speedometer2"></i></div><h4>Monitoring Tekanan Udara</h4><p>Sistem memantau tekanan udara ruangan secara digital untuk memberikan informasi kondisi lingkungan yang lebih lengkap dan akurat.</p></div>
            </div>
        </div>
    </section>

    <!-- Social Media Section -->
    <section id="social" class="social-media">
        <div class="container">
            <div class="social-section-title"><h2 class="fade-in">Media Sosial</h2><p class="fade-in">Ikuti perkembangan terbaru Rumah Sakit Paru Manguharjo Madiun melalui media sosial kami</p></div>
            <div class="social-simple-grid">
                <div class="social-simple-card scale-in"><div class="social-simple-icon website"><i class="bi bi-globe"></i></div><h4>Website</h4><p>Informasi lengkap rumah sakit</p><a href="https://rspmanguharjo.jatimprov.go.id/" target="_blank" class="btn-social-simple">Kunjungi</a></div>
                <div class="social-simple-card scale-in"><div class="social-simple-icon instagram"><i class="bi bi-instagram"></i></div><h4>Instagram</h4><p>Update kegiatan terbaru</p><a href="https://www.instagram.com/rspmanguharjo.prov.jatim/" target="_blank" class="btn-social-simple">Follow</a></div>
                <div class="social-simple-card scale-in"><div class="social-simple-icon facebook"><i class="bi bi-facebook"></i></div><h4>Facebook</h4><p>Komunitas & berita</p><a href="https://web.facebook.com/rspmanguharjo/?_rdc=1&_rdr" target="_blank" class="btn-social-simple">Like</a></div>
                <div class="social-simple-card scale-in"><div class="social-simple-icon youtube"><i class="bi bi-youtube"></i></div><h4>YouTube</h4><p>Video edukasi & kegiatan</p><a href="http://www.youtube.com/@RSParuManguharjoProvJatim" target="_blank" class="btn-social-simple">Subscribe</a></div>
                <div class="social-simple-card scale-in"><div class="social-simple-icon tiktok"><i class="bi bi-tiktok"></i></div><h4>TikTok</h4><p>Konten kreatif & informatif</p><a href="https://www.tiktok.com/@rspmanguharjo.prov.jatim?_r=1&_t=ZS-9145SfXKLOW" target="_blank" class="btn-social-simple">Follow</a></div>
                <div class="social-simple-card scale-in"><div class="social-simple-icon whatsapp"><i class="bi bi-whatsapp"></i></div><h4>WhatsApp</h4><p>Konsultasi & informasi</p><a href="https://wa.me/6285176021876" target="_blank" class="btn-social-simple">Chat</a></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-about">
                        <div class="footer-logo"><img src="{{ asset('assets/LOGO RS & PROV JATIM.png') }}" alt="Logo Rumah Sakit" loading="lazy" decoding="async">RESPA-Jo</div>
                        <p>Menghadirkan sistem monitoring untuk suhu, kelembaban, dan tekanan udara secara real-time di RSPM Madiun.</p>
                        <div class="social-links">
                            <a href="https://web.facebook.com/rspmanguharjo/?_rdc=1&_rdr" target="_blank"><i class="bi bi-facebook"></i></a>
                            <a href="https://www.instagram.com/rspmanguharjo.prov.jatim/" target="_blank"><i class="bi bi-instagram"></i></a>
                            <a href="http://www.youtube.com/@RSParuManguharjoProvJatim" target="_blank"><i class="bi bi-youtube"></i></a>
                            <a href="https://www.tiktok.com/@rspmanguharjo.prov.jatim?_r=1&_t=ZS-9145SfXKLOW" target="_blank"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-heading">Menu</h5>
                    <ul class="footer-links list-unstyled"><li><a href="#landing">Beranda</a></li><li><a href="#about">Profil</a></li><li><a href="#maps">Lokasi</a></li><li><a href="#features">Fitur</a></li><li><a href="#social">Media Sosial</a></li><li><a href="{{ route('login') }}">Login</a></li></ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Kontak Kami</h5>
                    <ul class="contact-info list-unstyled"><li><i class="bi bi-geo-alt-fill"></i><span>Jl. Yos Sudarso No. 108-112<br>Kota Madiun, Jawa Timur</span></li><li><i class="bi bi-telephone-fill"></i><span>IGD: (0351) 462427<br>Kantor: (0351) 464816</span></li><li><i class="bi bi-envelope-fill"></i><span>rspmmanguharjo@gmail.com</span></li></ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Jam Operasional</h5>
                    <ul class="footer-links list-unstyled"><li><strong>IGD:</strong> 24 Jam</li><li><strong>Poli Umum:</strong> 07.00 - 14.00</li><li><strong>Poli Spesialis:</strong> 08.00 - 12.00</li><li><strong>Administrasi:</strong> 07.00 - 14.00</li></ul>
                </div>
            </div>
            <div class="copyright"><p>&copy; 2025 RESPA-Jo - Rumah Sakit Paru Manguharjo Madiun. All rights reserved.</p></div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) document.querySelector('.navbar').classList.add('navbar-scrolled');
            else document.querySelector('.navbar').classList.remove('navbar-scrolled');
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
        }, observerOptions);

        document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right, .scale-in').forEach(el => observer.observe(el));

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.hero h1')?.classList.add('slide-in-left');
            document.querySelector('.hero p')?.classList.add('slide-in-left');
            document.querySelector('.hero .mt-4')?.classList.add('slide-in-left');
            document.querySelector('.hero-image img')?.classList.add('slide-in-right');
            document.querySelectorAll('.stat-icon').forEach(icon => icon.classList.add('scale-in'));
            document.querySelectorAll('.section-title h2, .section-title p').forEach(el => el.classList.add('fade-in'));
            document.querySelectorAll('.highlight-card, .feature-card, .social-simple-card').forEach(card => card.classList.add('scale-in'));
            document.querySelectorAll('.vision-card, .mission-card').forEach(card => card.classList.add('scale-in'));
            document.querySelectorAll('.maps-info').forEach(el => el.classList.add('fade-in'));
        });
    </script>
</body>
</html>