<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="sidebar" id="sidebar">
    <div class="pt-3">
        <div class="text-center mb-4">
            <div class="mb-3">
                <img src="{{ asset('assets/logo-rspm-jatim-prov.png') }}" alt="Logo Rumah Sakit" 
                     style="width: 100px; height: 100px; object-fit: contain; background: #f8f9fa; border-radius: 10px;">
            </div>
            <h6 class="fw-bold">Rumah Sakit Paru Manguharjo Madiun</h6>
            <small class="text-muted">IoT Monitoring System</small>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}" id="dashboardLink">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('monitoring.server') ? 'active' : '' }}" 
                   href="{{ route('monitoring.server') }}" id="monitoringServerLink">
                    <i class="fas fa-thermometer-half"></i> Monitoring Suhu
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('monitoring.infus') ? 'active' : '' }}" 
                   href="{{ route('monitoring.infus') }}" id="monitoringInfusLink">
                    <i class="fas fa-tint"></i> Monitoring Infus
                </a>
            </li>
        </ul>
    </div>
</div>