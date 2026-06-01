<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="sidebar" id="sidebar">
    <div class="pt-3">
        <div class="text-center mb-4">
            <div class="mb-3">
                <img src="{{ asset('assets/logo-rspm-jatim-prov.png') }}" alt="Logo Rumah Sakit"
                     style="width: 200px; height: 100px; object-fit: contain; background: none; border-radius: 10px;">
            </div>
            <h6 class="fw-bold" style="color: #0054A6;">Rumah Sakit Paru Manguharjo Madiun</h6>
            <small class="text-muted">RESPA-Jo</small>
        </div>

        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}" id="dashboardLink">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            <!-- Sensor DHT22 (Suhu + Kelembaban) -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('monitoring.suhu') ? 'active' : '' }}"
                   href="{{ url('/monitoring/suhu') }}" id="monitoringDHT22Link">
                    <i class="fas fa-temperature-low"></i> Sensor DHT22
                </a>
            </li>

            <!-- Sensor BME280 (Suhu + Kelembaban + Tekanan Udara) -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('monitoring.bme280') ? 'active' : '' }}"
                   href="{{ url('/monitoring/bme280') }}" id="monitoringBME280Link">
                    <i class="fas fa-microchip"></i> Sensor BME280
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    /* Sidebar Styles - Selaras dengan Gradasi Biru Landing, Login, Header */
    .sidebar {
        background: white !important;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    /* Active menu item - menggunakan gradasi biru seperti header */
    .sidebar .nav-link.active {
        background: linear-gradient(135deg, #0054A6 0%, #003d7a 100%) !important;
        color: white !important;
        border-radius: 10px;
        margin: 0 12px;
        padding: 10px 16px;
        box-shadow: 0 4px 12px rgba(0, 84, 166, 0.25);
    }
    
    /* Non-active menu item */
    .sidebar .nav-link {
        color: #4a5568;
        padding: 10px 16px;
        margin: 4px 12px;
        border-radius: 10px;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    /* Hover effect untuk menu (tetap menggunakan gradasi biru, bukan hijau) */
    .sidebar .nav-link:hover:not(.active) {
        background: linear-gradient(135deg, rgba(0, 84, 166, 0.1) 0%, rgba(0, 61, 122, 0.1) 100%);
        color: #0054A6 !important;
        transform: translateX(5px);
    }
    
    /* Icon styling */
    .sidebar .nav-link i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }
    
    /* Active icon */
    .sidebar .nav-link.active i {
        color: white;
    }
    
    /* Non-active icon */
    .sidebar .nav-link:not(.active) i {
        color: #0054A6;
    }
    
    /* Judul sidebar dengan warna biru gradasi */
    .sidebar h6.fw-bold {
        background: linear-gradient(135deg, #0054A6 0%, #003d7a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Overlay untuk mobile */
    .sidebar-overlay {
        background: rgba(0, 0, 0, 0.5);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sidebar .nav-link {
            margin: 4px 8px;
            padding: 8px 12px;
        }
        
        .sidebar .nav-link.active {
            margin: 4px 8px;
        }
    }
</style>