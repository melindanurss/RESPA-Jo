<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul') - RESPA-Jo</title>
    <title>RESPA-Jo - Sistem Monitoring Suhu Server</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary: #2E8B57;
            --primary-dark: #1f6b42;
            --primary-light: #7ABF55;
            --secondary: #61C5C3;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --white: #ffffff;
            --border: #dee2e6;
            --gradient: linear-gradient(135deg, #7ABF55 0%, #61C5C3 50%, #6DC18A 75%, #71C077 100%);
            --gradient-dark: linear-gradient(135deg, #2E8B57 0%, #1a5276 100%);
            --gradient-light: linear-gradient(135deg, rgba(122, 191, 85, 0.1) 0%, rgba(97, 197, 195, 0.1) 50%, rgba(109, 193, 138, 0.1) 75%, rgba(113, 192, 119, 0.1) 100%);
        }
        
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fb 0%, #e4f0f5 100%);
            color: #495057;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar-custom {
            background: var(--gradient);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            color: white;
            padding: 0.5rem 1rem;
            height: 70px;
            min-height: 70px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-nav,
        .navbar-custom .nav-link,
        .navbar-custom .user-dropdown {
            height: 100%;
            display: flex;
            align-items: center;
        }
        
        .navbar-custom .container-fluid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            flex-wrap: nowrap;
        }
        
        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: 800;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        
        .navbar-custom .nav-link {
            color: white !important;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
        }
        
        .navbar-custom .nav-link:hover {
            transform: translateY(-2px);
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .header-logo-icon {
            font-size: 1.8rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 8px;
            border-radius: 8px;
        }
        
        .user-dropdown:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        
        .app-wrapper {
            display: flex;
            margin-top: 70px;
        }

        .sidebar {
            background-color: var(--white);
            color: var(--dark);
            height: calc(100vh - 70px);
            position: fixed;
            overflow-y: auto;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
            z-index: 100;
            top: 70px;
            width: 250px;
        }
        
        .sidebar .nav-link {
            color: var(--gray);
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
            border-radius: 0 8px 8px 0;
            margin: 2px 0;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #2E8B57;
            background: var(--gradient-light);
            border-left: 3px solid #2E8B57;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            min-height: calc(100vh - 70px);
        }

        .card-custom {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-normal {
            background: #d4edda;
            color: #155724;
        }
        
        .status-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-critical {
            background: #f8d7da;
            color: #721c24;
        }

        .status-offline {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 70px;
                left: -260px;
                height: calc(100vh - 70px);
                z-index: 1050;
                transition: left 0.3s ease;
            }
            .sidebar.show {
                left: 0;
            }
            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
    @yield('head')
</head>
<body>
    @include('layout.header')
    <div class="app-wrapper">
        @include('layout.sidebar')
        <main class="main-content">
            @yield('Content')
        </main>
    </div>
    @yield('Modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');
            if (sidebar && toggle) {
                toggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                    if (overlay) overlay.classList.toggle('show');
                });
                if (overlay) overlay.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
        });
    </script>
    @yield('script')
</body>
</html>