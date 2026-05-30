<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul') - JoMonitor</title>
    <title>Dashboard IoT - Monitoring Suhu Server & Kontrol Infus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-light: #5dade2;
            --primary-dark: #2980b9;
            --secondary: #2ecc71;
            --secondary-light: #58d68d;
            --secondary-dark: #27ae60;
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
        
        /* HEADER STYLES - PERBAIKAN */
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
            z-index: 2;
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
            font-weight: 700;
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

        /* hanya icon-only (notif) */
        .navbar-nav .nav-item:not(.user-menu) .nav-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
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
        
        .header-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-left: 8px;
            font-weight: 500;
        }
        
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 8px; /* KONTROL DI SINI */
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
        
        .navbar-nav {
            display: flex;
            align-items: center;
            flex-direction: row;
            margin: 0;
            gap: 0;
            flex-wrap: nowrap;
        }

        .navbar .dropdown-toggle::after {
            display: none !important;
        }
        
        .nav-item {
            position: relative;
        }

        .navbar-nav {
            gap: 12px;
        }

        .navbar-nav .nav-item {
            margin-left: 0;
        }
        
        .dropdown-menu {
            background-color: var(--white);
            border: 1px solid var(--border);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 8px;
            min-width: 300px;
        }

        .navbar .dropdown-menu {
            position: absolute !important;
            top: 100%;
            right: 0;
            left: auto;
            z-index: 4000;
        }

        /* admin dropdown jangan pakai padding bootstrap */
        .navbar-nav .user-menu > .nav-link {
            padding: 0;
        }

        /* === HARD RESPONSIVE FIX === */
        .dashboard-grid,
        .quick-actions {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
        }
        
        /* SIDEBAR STYLES */
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
            margin-left: 250px;
            padding: 20px;
            min-height: calc(100vh - 70px);
            margin-top: 0;
            position: relative;
            z-index: 1;
        }

        /* Responsive */
        @media (min-width: 992px) {
            .navbar-custom .container-fluid {
                align-items: center;
            }

            .user-dropdown {
                white-space: nowrap;
            }
        }

        @media (max-width: 1200px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 991.98px) {
            .navbar-custom .navbar-brand {
                font-size: 1.2rem;
            }
            
            .header-logo-icon {
                width: 40px;
                height: 40px;
                font-size: 1.5rem;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .system-health {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 576px) {
            .navbar-custom {
                padding: 0.5rem;
            }
            
            .header-logo {
                gap: 8px;
            }
            
            .header-logo-icon {
                width: 35px;
                height: 35px;
                font-size: 1.3rem;
                padding: 8px;
            }
            
            .navbar-brand span {
                font-size: 1.1rem;
            }
            
            .header-badge {
                display: none;
            }
            
            .user-dropdown div:last-child {
                display: none;
            }

            .system-health .health-item {
                flex: 1 1 100%;
            }

            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .chart-actions {
                width: 100%;
                display: flex;
                gap: 6px;
            }

            .chart-actions button {
                flex: 1;
            }

            .chart-container {
                height: 220px;
            }
        }

        /* Kode CSS untuk konten dashboard yang sudah ada */
        .card-custom {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
            color: var(--dark);
            overflow: hidden;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header-custom {
            background: var(--gradient-light);
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0 !important;
            color: #2E8B57;
        }
        
        .card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s;
            color: var(--dark);
            position: relative;
            overflow: visible;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--gray);
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-light);
            color: #2E8B57;
        }
        
        .card-icon i {
            font-size: 24px;
        }
        
        .card-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark);
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .card-footer {
            font-size: 14px;
            color: var(--gray);
            display: flex;
            align-items: center;
        }
        
        .card-footer i {
            margin-right: 5px;
        }
        
        .positive {
            color: #2E8B57;
        }
        
        .negative {
            color: var(--danger);
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-normal {
            background-color: #2E8B57;
        }
        
        .status-warning {
            background-color: var(--warning);
        }
        
        .status-danger {
            background-color: var(--danger);
        }
        
        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-normal {
            background: rgba(46, 204, 113, 0.1);
            color: #2E8B57;
        }
        
        .status-warning {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }
        
        .status-critical {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }
        
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .chart-card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: none;
            position: relative;
            overflow: visible;
        }
        
        .chart-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .chart-actions {
            display: flex;
            gap: 10px;
        }
        
        .chart-actions button {
            background: var(--gradient-light);
            border: none;
            color: #2E8B57;
            padding: 5px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }
        
        .chart-actions button:hover {
            background: var(--gradient);
            color: var(--white);
        }
        
        .chart-container {
            width: 100%;
            max-width: 100%;
            height: 260px;
        }
        
        .data-table-container {
            background-color: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: none;
            margin-bottom: 30px;
            position: relative;
            overflow: visible;
        }
        
        .data-table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .table-actions {
            display: flex;
            gap: 10px;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            background: var(--gray-light);
            border: 1px solid var(--border);
            padding: 8px 15px 8px 35px;
            border-radius: 6px;
            color: var(--dark);
            width: 200px;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            border-color: #2E8B57;
            box-shadow: 0 0 0 2px rgba(46, 139, 87, 0.2);
        }
        
        .search-box i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
        
        .table-actions button {
            background: var(--gradient-light);
            border: 1px solid var(--border);
            color: #2E8B57;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .table-actions button:hover {
            background: var(--gradient);
            color: var(--white);
            border-color: transparent;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            color: var(--dark);
        }
        
        th {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
            font-weight: 500;
            color: var(--gray);
            background-color: var(--gradient-light);
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
        }
        
        .list-group-item {
            background-color: var(--white);
            border: 1px solid var(--border);
            color: var(--dark);
            transition: all 0.3s;
        }
        
        .list-group-item:hover {
            background-color: var(--gradient-light);
        }
        
        .alert {
            background-color: var(--white);
            border: none;
            color: var(--dark);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }
        
        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
        }
        
        .alert-warning {
            border-left: 4px solid var(--warning);
        }
        
        .alert-warning::before {
            background-color: var(--warning);
        }
        
        .alert-danger {
            border-left: 4px solid var(--danger);
        }
        
        .alert-danger::before {
            background-color: var(--danger);
        }
        
        .btn-outline-secondary {
            border-color: var(--border);
            color: var(--gray);
        }
        
        .btn-outline-secondary:hover {
            background: var(--gradient);
            border-color: transparent;
            color: var(--white);
        }
        
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .pagination-info {
            font-size: 14px;
            color: var(--gray);
        }
        
        .pagination-controls {
            display: flex;
            gap: 10px;
        }
        
        .pagination-controls button {
            background: var(--gray-light);
            border: 1px solid var(--border);
            color: var(--gray);
            width: 35px;
            height: 35px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .pagination-controls button:hover {
            background: var(--gradient);
            color: var(--white);
            border-color: transparent;
        }
        
        .pagination-controls button.active {
            background: var(--gradient);
            color: var(--white);
            border-color: transparent;
        }
        
        .badge {
            font-weight: 500;
            border-radius: 6px;
        }
        
        .bg-danger {
            background: var(--gradient) !important;
        }
        
        .gauge-container {
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .action-btn {
            background: var(--white);
            border: none;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: var(--gradient-light);
        }
        
        .action-btn i {
            font-size: 24px;
            margin-bottom: 8px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .action-btn span {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--gray);
        }
        
        .system-health {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        
        .system-health .health-item {
            flex: 1 1 50%;
        }
        
        .health-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .health-label {
            font-size: 12px;
            color: var(--gray);
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
            margin-top: 5px;
        }
        
        .progress-bar {
            background: var(--gradient);
        }

        /* STYLE BARU UNTUK KOTAK SEJAJAR */
        .status-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .status-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .status-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .status-card-header {
            background: var(--gradient-light);
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0 !important;
            color: #2E8B57;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-card-body {
            padding: 0;
        }

        .server-list, .infus-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .server-item, .infus-item {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border);
            transition: background-color 0.3s;
        }

        .server-item:hover, .infus-item:hover {
            background-color: var(--gradient-light);
        }

        .server-item:last-child, .infus-item:last-child {
            border-bottom: none;
        }

        .server-info, .infus-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .server-details, .infus-details {
            flex: 1;
        }

        .server-name, .infus-patient {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .server-location, .infus-room {
            font-size: 12px;
            color: var(--gray);
        }

        .server-stats, .infus-stats {
            text-align: right;
        }

        .server-temp {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .infus-remaining {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .infus-rate {
            font-size: 12px;
            color: var(--gray);
        }

        @media (max-width: 768px) {
            .status-container {
                grid-template-columns: 1fr;
            }
            
            .server-list, .infus-list {
                max-height: 300px;
            }

            .charts-container {
                grid-template-columns: 1fr !important;
            }
        }

        /* === CORE LAYOUT FIX === */
        .app-wrapper {
            display: flex;
            margin-top: 70px;
        }

        /* Desktop */
        .sidebar {
            width: 250px;
            flex-shrink: 0;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            min-height: calc(100vh - 70px);
        }

        /* === MOBILE BEHAVIOR === */
        @media (max-width: 991.98px) {
            .app-wrapper {
                margin-top: 70px;
            }

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
                width: 100%;
            }

            .navbar-collapse {
                position: static;
            }

            .navbar .dropdown-menu {
                position: absolute;
                right: 0;
                left: auto;
            }
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 991.98px) {
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.4);
                z-index: 1049;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
    @livewireStyles
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

            if (!sidebar || !toggle) return;

            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        });
    </script>
    @yield('script')
    @livewireScripts
</body>
</html> 