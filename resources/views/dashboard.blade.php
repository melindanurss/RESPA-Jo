@extends('layout.app')

@section('judul', 'Dashboard Monitoring Terpadu')

@section('head')
<style>
    /* ========== GLOBAL STYLES ========== */
    :root {
        --primary-gradient: linear-gradient(135deg, #7ABF55 0%, #61C5C3 100%);
        --warning-color: #e74c3c;
        --normal-color: #2ecc71;
        --card-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        --card-hover-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* ========== DASHBOARD GRID ========== */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1200px) {
        .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 768px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }
    
    /* ========== CARDS ========== */
    .card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        border: none;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .card-title {
        font-size: 0.95rem;
        font-weight: 500;
        color: #6c757d;
        margin: 0;
    }
    
    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(122, 191, 85, 0.1) 0%, rgba(97, 197, 195, 0.1) 100%);
        color: #2E8B57;
    }
    
    .card-icon i { font-size: 1.5rem; }
    
    .card-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 10px;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.2;
    }
    
    .card-footer {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    /* ========== STATUS BADGES ========== */
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
        border: 1px solid #c3e6cb;
    }
    
    .status-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .status-critical {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .status-offline {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 1px solid #dee2e6;
    }
    
    /* ========== STATUS CONTAINER ========== */
    .status-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .status-container { grid-template-columns: 1fr; }
    }
    
    .status-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: none;
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }
    
    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }
    
    .status-card-header {
        background: linear-gradient(135deg, rgba(122, 191, 85, 0.1) 0%, rgba(97, 197, 195, 0.1) 100%);
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        padding: 15px 20px;
        border-radius: 12px 12px 0 0 !important;
        color: #2E8B57;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .status-card-body { padding: 0; }
    
    .server-list, .infus-list { max-height: 400px; overflow-y: auto; }
    
    .server-item, .infus-item {
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
        transition: background-color 0.3s;
    }
    
    .server-item:hover, .infus-item:hover {
        background-color: rgba(122, 191, 85, 0.05);
    }
    
    .server-item:last-child, .infus-item:last-child { border-bottom: none; }
    
    .server-info, .infus-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .server-details, .infus-details { flex: 1; }
    
    .server-name, .infus-patient {
        font-weight: 600;
        margin-bottom: 5px;
        color: #343a40;
    }
    
    .server-location, .infus-room {
        font-size: 12px;
        color: #6c757d;
    }
    
    .server-stats, .infus-stats { text-align: right; }
    
    .server-temp, .infus-remaining {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .infus-rate { font-size: 12px; color: #6c757d; }
    
    /* ========== CHARTS ========== */
    .realtime-charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .realtime-charts-grid { grid-template-columns: 1fr; }
    }
    
    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: var(--card-shadow);
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .chart-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
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
        color: #343a40;
    }
    
    .chart-actions {
        display: flex;
        gap: 10px;
    }
    
    .chart-actions button {
        background: linear-gradient(135deg, rgba(122, 191, 85, 0.1) 0%, rgba(97, 197, 195, 0.1) 100%);
        border: none;
        color: #2E8B57;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
    }
    
    .chart-actions button:hover {
        background: var(--primary-gradient);
        color: white;
    }
    
    .chart-container {
        width: 100%;
        max-width: 100%;
        height: 260px;
    }
    
    .chart-footer {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    @media (max-width: 768px) {
        .chart-footer {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
    }
    
    /* ========== CURRENT VALUES CARDS ========== */
    .values-cards-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .values-cards-grid { grid-template-columns: 1fr; }
    }
    
    .values-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        border: none;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .values-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }
    
    .values-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-hover-shadow);
    }
    
    .values-card-header {
        background: linear-gradient(135deg, rgba(122, 191, 85, 0.1) 0%, rgba(97, 197, 195, 0.1) 100%);
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .values-card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .last-update {
        font-size: 0.75rem;
        color: #6c757d;
        display: flex;
        align-items: center;
    }
    
    .values-card-body { padding: 20px; }
    
    .current-reading {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 5px;
        margin-bottom: 20px;
    }
    
    .reading-value {
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }
    
    @media (max-width: 768px) {
        .reading-value { font-size: 2rem; }
    }
    
    .reading-unit {
        font-size: 1rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .reading-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .detail-item { text-align: center; }
    
    .detail-label {
        display: block;
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .detail-value {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .status-indicator-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-radius: 8px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    
    .status-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .status-indicator-card.normal .status-icon {
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }
    
    .status-indicator-card.warning .status-icon {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .status-content { flex: 1; }
    
    .status-title {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 3px;
    }
    
    .status-value {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .status-indicator-card.normal .status-value { color: #2ecc71; }
    .status-indicator-card.warning .status-value { color: #e74c3c; }
    
    /* ========== DATA TABLES ========== */
    .data-table-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .data-table-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }
    
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
    
    .table-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #343a40;
        margin: 0;
    }
    
    .table-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .search-box { position: relative; }
    
    .search-box input {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px 15px 8px 35px;
        border-radius: 6px;
        color: #343a40;
        width: 200px;
        transition: all 0.3s;
    }
    
    @media (max-width: 768px) {
        .search-box input { width: 100%; }
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
        color: #6c757d;
    }
    
    .table-actions button {
        background: linear-gradient(135deg, rgba(122, 191, 85, 0.1) 0%, rgba(97, 197, 195, 0.1) 100%);
        border: 1px solid #dee2e6;
        color: #2E8B57;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .table-actions button:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }
    
    /* ========== TABLES ========== */
    .table-responsive { overflow-x: auto; }
    
    table {
        width: 100%;
        border-collapse: collapse;
        color: #343a40;
    }
    
    th {
        text-align: left;
        padding: 12px 15px;
        border-bottom: 1px solid #dee2e6;
        font-weight: 500;
        color: #6c757d;
        background-color: #f8f9fa;
    }
    
    td {
        padding: 12px 15px;
        border-bottom: 1px solid #dee2e6;
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
        color: #f39c12;
    }
    
    .status-critical {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }
    
    /* ========== PAGINATION ========== */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }
    
    @media (max-width: 768px) {
        .pagination {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
    }
    
    .pagination-info {
        font-size: 14px;
        color: #6c757d;
    }
    
    .pagination-controls {
        display: flex;
        gap: 10px;
    }
    
    .pagination-controls button {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #6c757d;
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
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }
    
    .pagination-controls button.active {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }
    
    /* ========== ALERTS ========== */
    .alert-container {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }
    
    @media (max-width: 768px) {
        .alert-container {
            left: 10px;
            right: 10px;
            max-width: none;
        }
    }
    
    .alert {
        margin-bottom: 10px;
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* ========== STATUS INDICATORS ========== */
    .current-value {
        display: flex;
        align-items: baseline;
        gap: 5px;
    }
    
    .value-label {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .value-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .value-unit {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .threshold-info {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-left: 10px;
        background: #f8f9fa;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #6c757d;
    }
    
    .status-summary {
    display: flex;
    gap: 8px;
    align-items: center;
    }

    .status-summary .badge {
    font-size: 0.75rem;
    padding: 4px 8px;
    }   

    .status-indicator .status-dot.normal {
        background: #2ecc71;
        box-shadow: 0 0 8px rgba(46, 204, 113, 0.5);
    }
    
    .status-indicator .status-dot.warning {
        background: #e74c3c;
        box-shadow: 0 0 8px rgba(231, 76, 60, 0.5);
        animation: pulse 2s infinite;
    }
    
    /* ========== LOADING ========== */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #3498db;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('Content')
<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <div>
        <h1 class="h3 fw-bold text-dark">Dashboard Monitoring Terpadu</h1>
        <p class="text-muted">Sistem monitoring real-time suhu ruangan dan infus pasien</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button id="btnOpenDevices" type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDevice">
                <i class="fas fa-microchip me-1"></i> Device
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshBtn" onclick="refreshDashboardData()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>
</div>

@if(($infusMonitoringData['criticalCount'] ?? 0) > 0)
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-circle me-2 text-warning"></i>
    <strong>Peringatan!</strong> {{ $infusMonitoringData['criticalCount'] }} infus dalam kondisi kritis.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Dashboard Grid -->
<div class="dashboard-grid mb-4">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Device Terpantau</div>
            <div class="card-icon">
                <i class="fas fa-microchip"></i>
            </div>
        </div>
        <div class="card-value" id="totalDevices">{{ $totalDevices ?? 0 }}</div>
        <div class="card-footer">
            <i class="fas fa-info-circle text-primary"></i>
            <span>Total device keseluruhan</span>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Ruangan Terpantau</div>
            <div class="card-icon">
                <i class="fas fa-door-open me-1"></i>
            </div>
        </div>
        <div class="card-value" id="roomMonitorings">{{ $roomMonitorings ?? 0 }}</div>
        <div class="card-footer">
            <i class="fas fa-info-circle text-primary"></i>
            <span>Total ruangan yang sedang dimonitoring</span>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Pasien Terpantau</div>
            <div class="card-icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
        <div class="card-value" id="patientMonitorings">{{ $patientMonitorings ?? 0 }}</div>
        <div class="card-footer">
            <i class="fas fa-info-circle text-primary"></i>
            <span>Total pasien yang sedang dimonitoring</span>
        </div>
    </div>
</div>

<!-- Status Container -->
<div class="status-container mb-4">
    <!-- Status Server -->
    <div class="status-card">
        <div class="status-card-header">
            <span><i class="fas fa-server me-2"></i> Status Monitoring Suhu</span>
            <div class="status-summary">
                <span class="badge bg-warning me-2">{{ $serverStatusData['warningCount'] ?? 0 }} Warning</span>
                <span class="badge bg-success">{{ $serverStatusData['normalCount'] ?? 0 }} Normal</span>
            </div>
        </div>
        <div class="status-card-body">
            <div class="server-list" id="serverStatusList">
                @forelse(($serverStatusData['servers'] ?? []) as $server)
                <div class="server-item">
                    <div class="server-info">
                        <div class="server-details">
                            <div class="server-name">{{ $server['name'] }}</div>
                            <div class="server-location">{{ $server['location'] }}</div>
                        </div>
                        <div class="server-stats">
                            <div class="server-temp {{ $server['status'] === 'warning' ? 'text-warning' : 'text-success' }}">
                                {{ $server['temperature'] }}°C
                            </div>
                            <div class="server-status">
                                @if($server['status'] === 'normal')
                                    <span class="status status-normal">Normal</span>
                                @else
                                    <span class="status status-warning">Warning</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="server-item text-center text-muted py-4">
                    <i class="fas fa-server fa-2x mb-2"></i>
                    <p>Tidak ada data server</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Status Infus -->
    <div class="status-card">
        <div class="status-card-header">
            <span><i class="fas fa-tint me-2"></i> Status Monitoring Infus</span>
            <span class="badge bg-danger">{{ $infusMonitoringData['criticalCount'] ?? 0 }} Kritis</span>
        </div>
        <div class="status-card-body">
            <div class="infus-list" id="infusStatusList">
                @forelse(($infusMonitoringData['infus'] ?? []) as $infus)
                <div class="infus-item">
                    <div class="infus-info">
                        <div class="infus-details">
                            <div class="infus-patient">{{ $infus['nama_pasien'] }}</div>
                            <div class="infus-room">{{ $infus['ruangan'] }}</div>
                        </div>
                        <div class="infus-stats">
                            <div class="infus-remaining {{ $infus['status'] === 'critical' ? 'text-danger' : ($infus['status'] === 'warning' ? 'text-warning' : 'text-success') }}">
                                {{ $infus['sisa_cairan'] }}
                            </div>
                            <div class="infus-rate">{{ $infus['kecepatan'] }}</div>
                            <div class="infus-status">
                                @if($infus['status'] === 'normal')
                                    <span class="status status-normal">Normal</span>
                                @elseif($infus['status'] === 'warning')
                                    <span class="status status-warning">Perhatian</span>
                                @else
                                    <span class="status status-critical">Kritis</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="infus-item text-center text-muted py-4">
                    <i class="fas fa-tint fa-2x mb-2"></i>
                    <p>Tidak ada data infus</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Real-time Charts Grid -->
<div class="realtime-charts-grid mb-4">
    <!-- Temperature Chart -->
    <div class="chart-card">
        <div class="chart-header flex-wrap gap-2">
            <div class="chart-title">
                <i class="fas fa-thermometer-half me-2"></i> Suhu
                <span class="status-indicator" id="tempStatusIndicator">
                    <span class="status-dot"></span>
                    <span class="status-text">Loading...</span>
                </span>
            </div>
            <div class="chart-actions d-flex align-items-center flex-wrap gap-1">
                <select id="tempDeviceSelect" class="form-select form-select-sm d-inline-block w-auto me-1" style="font-size: 11px; padding: 2px 24px 2px 8px; height: 28px; border-radius: 6px; border: 1px solid #dee2e6;" onchange="onDeviceChartChange('temp', this.value)">
                    <option value="all">Memuat ruangan...</option>
                </select>
                <button onclick="changeChartRange('temperature', '1h')" class="active">1 Jam</button>
                <button onclick="changeChartRange('temperature', '6h')">6 Jam</button>
                <button onclick="changeChartRange('temperature', '24h')">24 Jam</button>
                <button onclick="refreshChart('temperature')">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="temperatureChart"></canvas>
        </div>
        <div class="chart-footer">
            <div class="current-value">
                <span class="value-label">Suhu Terkini:</span>
                <span class="value-number" id="currentTempValue">--</span>
                <span class="value-unit">°C</span>
            </div>
            <div class="threshold-info">
                <span class="threshold-normal">
                    <i class="fas fa-circle me-1" style="color: #2ecc71;"></i>
                    Normal: ≤ 28°C
                </span>
                <span class="threshold-warning ms-3">
                    <i class="fas fa-circle me-1" style="color: #e74c3c;"></i>
                    Warning: > 28°C
                </span>
            </div>
        </div>
    </div>
    
    <!-- Humidity Chart -->
    <div class="chart-card">
        <div class="chart-header flex-wrap gap-2">
            <div class="chart-title">
                <i class="fas fa-cloud-rain"></i> Kelembaban
                <span class="status-indicator" id="humidityStatusIndicator">
                    <span class="status-dot"></span>
                    <span class="status-text">Loading...</span>
                </span>
            </div>
            <div class="chart-actions d-flex align-items-center flex-wrap gap-1">
                <select id="humDeviceSelect" class="form-select form-select-sm d-inline-block w-auto me-1" style="font-size: 11px; padding: 2px 24px 2px 8px; height: 28px; border-radius: 6px; border: 1px solid #dee2e6;" onchange="onDeviceChartChange('hum', this.value)">
                    <option value="all">Memuat ruangan...</option>
                </select>
                <button onclick="changeChartRange('humidity', '1h')" class="active">1 Jam</button>
                <button onclick="changeChartRange('humidity', '6h')">6 Jam</button>
                <button onclick="changeChartRange('humidity', '24h')">24 Jam</button>
                <button onclick="refreshChart('humidity')">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="humidityChart"></canvas>
        </div>
        <div class="chart-footer">
            <div class="current-value">
                <span class="value-label">Kelembaban Terkini:</span>
                <span class="value-number" id="currentHumidityValue">--</span>
                <span class="value-unit">%</span>
            </div>
            <div class="threshold-info">
                <span class="threshold-normal">
                    <i class="fas fa-circle me-1" style="color: #2ecc71;"></i>
                    Normal: ≤ 60%
                </span>
                <span class="threshold-warning ms-3">
                    <i class="fas fa-circle me-1" style="color: #e74c3c;"></i>
                    Warning: > 60%
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Current Values Cards -->
<div class="values-cards-grid mb-4">
    <div class="values-card">
        <div class="values-card-header">
            <h5><i class="fas fa-thermometer-three-quarters me-2"></i> Status Suhu</h5>
            <div class="last-update" id="tempLastUpdate">
                <i class="fas fa-clock me-1"></i>Loading...
            </div>
        </div>
        <div class="values-card-body">
            <div class="current-reading">
                <div class="reading-value" id="currentTemperature">--</div>
                <div class="reading-unit">°C</div>
            </div>
            <div class="reading-details">
                <div class="detail-item">
                    <span class="detail-label">Min</span>
                    <span class="detail-value" id="tempMin">--</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Avg</span>
                    <span class="detail-value" id="tempAvg">--</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Max</span>
                    <span class="detail-value" id="tempMax">--</span>
                </div>
            </div>
            <div class="status-indicator-card" id="tempStatusCard">
                <div class="status-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="status-content">
                    <div class="status-title">Status</div>
                    <div class="status-value">Loading...</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="values-card">
        <div class="values-card-header">
            <h5><i class="fas fa-cloud-rain"></i> Status Kelembaban</h5>
            <div class="last-update" id="humidityLastUpdate">
                <i class="fas fa-clock me-1"></i>Loading...
            </div>
        </div>
        <div class="values-card-body">
            <div class="current-reading">
                <div class="reading-value" id="currentHumidity">--</div>
                <div class="reading-unit">%</div>
            </div>
            <div class="reading-details">
                <div class="detail-item">
                    <span class="detail-label">Min</span>
                    <span class="detail-value" id="humidityMin">--</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Avg</span>
                    <span class="detail-value" id="humidityAvg">--</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Max</span>
                    <span class="detail-value" id="humidityMax">--</span>
                </div>
            </div>
            <div class="status-indicator-card" id="humidityStatusCard">
                <div class="status-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="status-content">
                    <div class="status-title">Status</div>
                    <div class="status-value">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monitoring Infus Aktif -->
<div class="data-table-container">
    <div class="table-header">
        <div class="table-title">Monitoring Infus Aktif</div>
        <div class="table-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari pasien..." id="searchInfus" onkeyup="filterTable('infusTable')">
            </div>
            <button onclick="refreshDeviceStatus()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table id="infusTable">
            <thead>
                <tr>
                    <th>ID INFUS</th>
                    <th>NAMA PASIEN</th>
                    <th>JENIS CAIRAN</th>
                    <th>KECEPATAN</th>
                    <th>SISA CAIRAN</th>
                    <th>WAKTU PERKIRAAN</th>
                    <th>STATUS</th>
                    <th>RUANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($infusMonitoringData['infus'] ?? []) as $infus)
                <tr>
                    <td>{{ $infus['id'] }}</td>
                    <td>{{ $infus['nama_pasien'] }}</td>
                    <td>{{ $infus['jenis_cairan'] }}</td>
                    <td>{{ $infus['kecepatan'] }}</td>
                    <td>{{ $infus['sisa_cairan'] }}</td>
                    <td>{{ $infus['waktu_perkiraan'] }}</td>
                    <td>
                        @if($infus['status'] === 'normal')
                            <span class="status status-normal">Normal</span>
                        @elseif($infus['status'] === 'warning')
                            <span class="status status-warning">Perhatian</span>
                        @else
                            <span class="status status-critical">Kritis</span>
                        @endif
                    </td>
                    <td>{{ $infus['ruangan'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-tint fa-2x mb-3"></i>
                        <p>Tidak ada data infus aktif</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        <div class="pagination-info">
            Menampilkan {{ count($infusMonitoringData['infus'] ?? []) }} data
        </div>
    </div>
</div>

<!-- Status Suhu per Device -->
<div class="data-table-container">
    <div class="table-header">
        <div class="table-title">
            <i class="fas fa-thermometer-half me-2"></i> Status Suhu per Device
        </div>
        <div class="table-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari device..." id="searchDeviceStatus" onkeyup="filterDeviceStatusTable()">
            </div>
            <button onclick="refreshDeviceStatus()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table id="deviceStatusTable">
            <thead>
                <tr>
                    <th>DEVICE</th>
                    <th>ID RUANG</th>
                    <th>NAMA RUANG</th>
                    <th>SUHU (°C)</th>
                    <th>KELEMBABAN (%)</th>
                    <th>STATUS</th>
                    <th>UPDATE TERAKHIR</th>
                </tr>
            </thead>
            <tbody id="deviceStatusBody">
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-spinner fa-spin me-2"></i>Memuat status device...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        <div class="pagination-info" id="deviceStatusInfo">
            Memuat data...
        </div>
        <div class="pagination-controls" id="deviceStatusPagination">
            <!-- Pagination akan di-generate oleh JavaScript -->
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
</div>

<!-- Alert Container -->
<div id="alertContainer" class="alert-container"></div>
@endsection

@section('Modal')
<!-- Modal Device -->
<div class="modal fade" id="modalDevice" tabindex="-1" aria-labelledby="modalDeviceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-start gap-2">
                <div>
                    <h5 class="modal-title" id="modalDeviceLabel">
                        <i class="fas fa-microchip me-2"></i> Daftar Device
                    </h5>
                    <small class="text-muted">Device yang digunakan untuk monitoring</small>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <button id="btnShowCreate" type="button" class="btn btn-sm btn-success">+ Tambah Device</button>
                </div>
            </div>
            <div class="modal-body">
                <div id="devicesAlert" class="alert-container"></div>
                <div id="deviceFormWrap" class="mb-3" style="display:none;">
                    <div class="card p-3">
                        <form id="deviceForm">
                            <input type="hidden" id="deviceFormMode" value="create">
                            <input type="hidden" id="deviceId" value="">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Device Key *</label>
                                    <input id="deviceKey" class="form-control" required maxlength="255" />
                                    <small class="text-muted">Unique identifier untuk device</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipe Device *</label>
                                    <select id="deviceType" class="form-select" required>
                                        <option value="suhu">Suhu</option>
                                        <option value="infus">Infus</option>
                                    </select>
                                </div>
                                <div class="col-12 d-flex align-items-end justify-content-end gap-2 mt-3">
                                    <button id="btnCancelForm" type="button" class="btn btn-sm btn-outline-secondary">Batal</button>
                                    <button id="btnSubmitForm" type="submit" class="btn btn-sm btn-primary">Simpan Device</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px">#</th>
                                <th>Device Key</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th style="width:180px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="devicesTbody">
                            <tr><td colspan="5" class="text-center">Loading device...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <small class="text-muted me-auto">Total: <span id="totalDevicesModal">0</span> device</small>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============================================
    // GLOBAL CONFIGURATION
    // ============================================
    const API_BASE = '{{ url("/") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    
    // Chart instances
    let temperatureChart = null;
    let humidityChart = null;
    
    // Current chart ranges
    let currentTempRange = '1h';
    let currentHumidityRange = '1h';
    let currentChartDevice = 'all';
    let isDeviceSelectPopulated = false;
    
    // Device status variables
    let deviceStatusData = [];
    let currentDevicePage = 1;
    let devicePerPage = 10;
    let deviceTotalPages = 1;
    
    // Auto-refresh interval
    let autoRefreshInterval = null;
    
    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard Monitoring System Initializing...');
        
        // Initialize components
        initializeDeviceModal();
        initializeCharts();
        
        // Load initial data
        refreshDashboardData();
        
        // Start auto-refresh (every 30 seconds)
        startAutoRefresh();
        
        console.log('Dashboard initialized successfully!');
    });
    
    // ============================================
    // DASHBOARD FUNCTIONS
    // ============================================
    async function refreshDashboardData() {
        showLoading();
        
        try {
            // Refresh stats
            await updateDashboardStats();
            
            // Refresh charts
            await Promise.all([
                loadTemperatureChart(),
                loadHumidityChart()
            ]);
            
            // Refresh status data
            await Promise.all([
                updateServerStatus(),
                updateInfusStatus(),
                loadDeviceStatus() // Load device status table
            ]);
            
            showAlert('success', 'Dashboard berhasil diperbarui');
            
        } catch (error) {
            console.error('Error refreshing dashboard:', error);
            showAlert('error', 'Gagal memperbarui dashboard: ' + error.message);
        } finally {
            hideLoading();
        }
    }
    
    async function updateDashboardStats() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/stats`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    const data = result.data;
                    
                    // Update card values
                    document.getElementById('totalDevices').textContent = data.total_devices || 0;
                    document.getElementById('activeDevices').textContent = data.active_devices || 0;
                    document.getElementById('roomMonitorings').textContent = data.room_monitorings || 0;
                    document.getElementById('patientMonitorings').textContent = data.patient_monitorings || 0;
                    
                    return true;
                }
            }
            return false;
        } catch (error) {
            console.error('Error updating stats:', error);
            return false;
        }
    }
    
    async function updateServerStatus() {
    try {
        const response = await fetch(`${API_BASE}/api/dashboard/server-status`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            if (result.success) {
                // Update server status list
                const servers = result.data.servers || [];
                const listElement = document.getElementById('serverStatusList');
                
                if (servers.length > 0) {
                    listElement.innerHTML = servers.map(server => `
                        <div class="server-item">
                            <div class="server-info">
                                <div class="server-details">
                                    <div class="server-name">${escapeHtml(server.name)}</div>
                                    <div class="server-location">${escapeHtml(server.location)}</div>
                                </div>
                                <div class="server-stats">
                                    <div class="server-temp ${server.status === 'warning' ? 'text-warning' : 'text-success'}">
                                        ${server.temperature}°C
                                    </div>
                                    <div class="server-status">
                                        <span class="status status-${server.status}">
                                            ${server.status === 'normal' ? 'Normal' : 'Warning'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
                
                // Update summary badges
                const headerElement = document.querySelector('.status-card-header .status-summary');
                if (headerElement) {
                    headerElement.innerHTML = `
                        <span class="badge bg-warning me-2">${result.data.warningCount || 0} Warning</span>
                        <span class="badge bg-success">${result.data.normalCount || 0} Normal</span>
                    `;
                }
            }
        }
    } catch (error) {
        console.error('Error updating server status:', error);
    }
}
    
    async function updateInfusStatus() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/infus-status`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    // Update infusion status list
                    const infus = result.data.infus || [];
                    const listElement = document.getElementById('infusStatusList');
                    
                    if (infus.length > 0) {
                        listElement.innerHTML = infus.map(infus => `
                            <div class="infus-item">
                                <div class="infus-info">
                                    <div class="infus-details">
                                        <div class="infus-patient">${escapeHtml(infus.nama_pasien)}</div>
                                        <div class="infus-room">${escapeHtml(infus.ruangan)}</div>
                                    </div>
                                    <div class="infus-stats">
                                        <div class="infus-remaining ${infus.status === 'critical' ? 'text-danger' : (infus.status === 'warning' ? 'text-warning' : 'text-success')}">
                                            ${infus.sisa_cairan}
                                        </div>
                                        <div class="infus-rate">${infus.kecepatan}</div>
                                        <div class="infus-status">
                                            <span class="status status-${infus.status}">
                                                ${infus.status === 'normal' ? 'Normal' : (infus.status === 'warning' ? 'Perhatian' : 'Kritis')}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                }
            }
        } catch (error) {
            console.error('Error updating infusion status:', error);
        }
    }
    
    // ============================================
    // DEVICE STATUS TABLE FUNCTIONS
    // ============================================
    async function loadDeviceStatus() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/temperature-devices-status`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    deviceStatusData = result.data || [];
                    updateDeviceStatusTable(deviceStatusData);
                    updateDeviceStatusInfo(result);
                    populateChartDeviceSelectors(deviceStatusData);
                    return;
                }
            }
            
            // Fallback jika API gagal
            updateDeviceStatusTable(getSampleDeviceStatusData());
            
        } catch (error) {
            console.error('Error loading device status:', error);
            updateDeviceStatusTable(getSampleDeviceStatusData());
        }
    }
    
    function updateDeviceStatusTable(data) {
        const tbody = document.getElementById('deviceStatusBody');
        if (!tbody) return;
        
        if (!data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-microchip-slash me-2"></i>
                        <div>Tidak ada device suhu aktif</div>
                        <small class="text-muted mt-2">Tambahkan device di menu Device</small>
                    </td>
                </tr>
            `;
            return;
        }
        
        // Apply pagination
        const startIndex = (currentDevicePage - 1) * devicePerPage;
        const endIndex = startIndex + devicePerPage;
        const paginatedData = data.slice(startIndex, endIndex);
        
        tbody.innerHTML = paginatedData.map(device => {
            const statusBadgeClass = device.status === 'Normal' ? 'status-normal' : 
                                   device.status === 'Warning' ? 'status-warning' : 
                                   device.status === 'Critical' ? 'status-critical' : 'status-offline';
            
            const statusText = device.status_display || device.status;
            const isOnline = device.is_online !== false;
            const onlineIcon = isOnline ? 
                '<i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i>' : 
                '<i class="fas fa-circle text-secondary me-1" style="font-size: 8px;"></i>';
            
            return `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            ${onlineIcon}
                            <i class="fas fa-microchip text-primary me-2"></i>
                            <div>
                                <div class="fw-bold">${escapeHtml(device.device_name)}</div>
                                <small class="text-muted">${escapeHtml(device.device_key)}</small>
                            </div>
                        </div>
                    </td>
                    <td><code>${escapeHtml(device.room_id)}</code></td>
                    <td>${escapeHtml(device.room_name)}</td>
                    <td class="fw-bold ${device.temperature_status === 'Critical' ? 'text-danger' : 
                        device.temperature_status === 'Warning' ? 'text-warning' : 'text-success'}">
                        ${device.temperature}
                    </td>
                    <td class="${device.humidity_status === 'Critical' ? 'text-danger' : 
                        device.humidity_status === 'Warning' ? 'text-warning' : 'text-success'}">
                        ${device.humidity}
                    </td>
                    <td>
                        <span class="status-badge ${statusBadgeClass}">
                            ${escapeHtml(statusText)}
                        </span>
                    </td>
                    <td class="timestamp">
                        <i class="fas fa-clock me-1 text-muted"></i>
                        ${escapeHtml(device.time_ago)}
                        <br>
                        <small class="text-muted">${escapeHtml(device.last_update)}</small>
                    </td>
                </tr>
            `;
        }).join('');
        
        updateDeviceStatusPagination(data.length);
    }
    
    function updateDeviceStatusInfo(result) {
        const infoElement = document.getElementById('deviceStatusInfo');
        if (!infoElement || !result) return;
        
        const onlineCount = result.online_count || 0;
        const offlineCount = result.offline_count || 0;
        const total = result.total || deviceStatusData.length;
        
        infoElement.innerHTML = `
            Menampilkan ${deviceStatusData.length} device 
            <span class="text-success">(${onlineCount} online)</span>
            <span class="text-secondary">(${offlineCount} offline)</span>
            <small class="text-muted ms-2">${new Date().toLocaleTimeString('id-ID')}</small>
        `;
    }
    
    function updateDeviceStatusPagination(totalItems) {
        deviceTotalPages = Math.ceil(totalItems / devicePerPage);
        const controlsElement = document.getElementById('deviceStatusPagination');
        
        if (!controlsElement || deviceTotalPages <= 1) {
            controlsElement.innerHTML = '';
            return;
        }
        
        controlsElement.innerHTML = '';
        
        // Previous button
        const prevButton = document.createElement('button');
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.disabled = currentDevicePage === 1;
        prevButton.onclick = () => {
            if (currentDevicePage > 1) {
                currentDevicePage--;
                updateDeviceStatusTable(deviceStatusData);
            }
        };
        controlsElement.appendChild(prevButton);
        
        // Page numbers
        const startPage = Math.max(1, currentDevicePage - 2);
        const endPage = Math.min(deviceTotalPages, currentDevicePage + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.className = i === currentDevicePage ? 'active' : '';
            pageButton.onclick = () => {
                currentDevicePage = i;
                updateDeviceStatusTable(deviceStatusData);
            };
            controlsElement.appendChild(pageButton);
        }
        
        // Next button
        const nextButton = document.createElement('button');
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.disabled = currentDevicePage === deviceTotalPages;
        nextButton.onclick = () => {
            if (currentDevicePage < deviceTotalPages) {
                currentDevicePage++;
                updateDeviceStatusTable(deviceStatusData);
            }
        };
        controlsElement.appendChild(nextButton);
    }
    
    function filterDeviceStatusTable() {
        const searchInput = document.getElementById('searchDeviceStatus');
        const searchTerm = searchInput.value.toLowerCase();
        
        if (!searchTerm) {
            updateDeviceStatusTable(deviceStatusData);
            return;
        }
        
        const filteredData = deviceStatusData.filter(device => 
            device.device_name.toLowerCase().includes(searchTerm) ||
            device.device_key.toLowerCase().includes(searchTerm) ||
            device.room_name.toLowerCase().includes(searchTerm) ||
            device.room_id.toLowerCase().includes(searchTerm)
        );
        
        currentDevicePage = 1;
        updateDeviceStatusTable(filteredData);
        
        const infoElement = document.getElementById('deviceStatusInfo');
        if (infoElement) {
            infoElement.innerHTML = `
                Menampilkan ${filteredData.length} dari ${deviceStatusData.length} device
                <small class="text-muted ms-2">(filter: "${searchTerm}")</small>
            `;
        }
    }
    
    function filterDeviceStatusBy(status) {
        let filteredData = deviceStatusData;
        
        if (status !== 'all') {
            filteredData = deviceStatusData.filter(device => device.status === status);
        }
        
        currentDevicePage = 1;
        updateDeviceStatusTable(filteredData);
        
        const infoElement = document.getElementById('deviceStatusInfo');
        if (infoElement) {
            const statusText = status === 'all' ? 'Semua' : 
                              status === 'Normal' ? 'Normal' :
                              status === 'Warning' ? 'Warning' :
                              status === 'Critical' ? 'Kritis' : 'Offline';
            
            infoElement.innerHTML = `
                Menampilkan ${filteredData.length} device (Status: ${statusText})
            `;
        }
    }
    
    function refreshDeviceStatus() {
        loadDeviceStatus();
        showAlert('info', 'Status device diperbarui');
    }
    
    function getSampleDeviceStatusData() {
        return [
            {
                device_key: 'nodemcu1',
                device_name: 'NodeMCU 1',
                room_id: 'SVR',
                room_name: 'Server Room',
                temperature: '26.5',
                humidity: '55.2',
                status: 'Normal',
                status_display: 'Normal',
                status_class: 'status-normal',
                temperature_status: 'Normal',
                humidity_status: 'Normal',
                last_update: new Date().toLocaleDateString('id-ID'),
                time_ago: 'Baru saja',
                is_online: true
            },
            {
                device_key: 'wemos1',
                device_name: 'Wemos D1',
                room_id: 'ICU',
                room_name: 'ICU Room',
                temperature: '29.8',
                humidity: '62.5',
                status: 'Warning',
                status_display: 'Warning',
                status_class: 'status-warning',
                temperature_status: 'Warning',
                humidity_status: 'Warning',
                last_update: new Date().toLocaleDateString('id-ID'),
                time_ago: '5 menit lalu',
                is_online: true
            }
        ];
    }
    
    function populateChartDeviceSelectors(devices) {
        if (isDeviceSelectPopulated || !devices || devices.length === 0) return;
        
        const tempSelect = document.getElementById('tempDeviceSelect');
        const humSelect = document.getElementById('humDeviceSelect');
        if (!tempSelect || !humSelect) return;
        
        let optionsHtml = '<option value="all">Semua Ruangan</option>';
        devices.forEach(d => {
            const label = d.room_name && d.room_name !== 'Belum ditetapkan' 
                ? `${d.room_name} (${d.device_name || d.device_key})`
                : d.device_name || d.device_key;
            optionsHtml += `<option value="${d.device_key}">${escapeHtml(label)}</option>`;
        });
        
        tempSelect.innerHTML = optionsHtml;
        humSelect.innerHTML = optionsHtml;
        
        // Find first active/online device to default to
        const firstActive = devices.find(d => d.is_online || d.status !== 'offline');
        if (firstActive) {
            currentChartDevice = firstActive.device_key;
            tempSelect.value = currentChartDevice;
            humSelect.value = currentChartDevice;
            
            // Reload the charts for this device
            loadTemperatureChart();
            loadHumidityChart();
        }
        
        isDeviceSelectPopulated = true;
    }
    
    function onDeviceChartChange(selectId, value) {
        currentChartDevice = value;
        
        // Sync selects
        const tempSelect = document.getElementById('tempDeviceSelect');
        const humSelect = document.getElementById('humDeviceSelect');
        if (tempSelect) tempSelect.value = value;
        if (humSelect) humSelect.value = value;
        
        // Reload
        loadTemperatureChart();
        loadHumidityChart();
    }
    
    // ============================================
    // CHART FUNCTIONS
    // ============================================
    function initializeCharts() {
        // Initialize Chart.js defaults
        Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
        Chart.defaults.color = '#6c757d';
        
        // Load initial charts
        loadTemperatureChart();
        loadHumidityChart();
    }
    
    async function loadTemperatureChart() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/temperature-realtime?range=${currentTempRange}&device=${currentChartDevice}`, {
                headers: { 'Accept': 'application/json' }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    renderTemperatureChart(result.data);
                    updateTemperatureStatus(result.data);
                    return;
                }
            }
            
            // Fallback to sample data
            renderTemperatureChart(getSampleTemperatureData());
            
        } catch (error) {
            console.error('Error loading temperature chart:', error);
            renderTemperatureChart(getSampleTemperatureData());
        }
    }
    
    async function loadHumidityChart() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/humidity-realtime?range=${currentHumidityRange}&device=${currentChartDevice}`, {
                headers: { 'Accept': 'application/json' }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    renderHumidityChart(result.data);
                    updateHumidityStatus(result.data);
                    return;
                }
            }
            
            // Fallback to sample data
            renderHumidityChart(getSampleHumidityData());
            
        } catch (error) {
            console.error('Error loading humidity chart:', error);
            renderHumidityChart(getSampleHumidityData());
        }
    }
    
    function renderTemperatureChart(chartData) {
        const ctx = document.getElementById('temperatureChart').getContext('2d');
        
        // Destroy existing chart
        if (temperatureChart) {
            temperatureChart.destroy();
        }
        
        // Create gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(231, 76, 60, 0.2)');
        gradient.addColorStop(1, 'rgba(231, 76, 60, 0.05)');
        
        temperatureChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Suhu (°C)',
                    data: chartData.values,
                    borderColor: '#e74c3c',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: function(context) {
                        const value = context.dataset.data[context.dataIndex];
                        return value > 28 ? '#e74c3c' : '#2ecc71';
                    },
                    pointRadius: 3,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `Suhu: ${context.parsed.y}°C`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            maxRotation: 0,
                            font: { size: 10 }
                        }
                    },
                    y: {
                        beginAtZero: false,
                        min: function(context) {
                            const minValue = Math.min(...context.chart.data.datasets[0].data);
                            return Math.max(0, Math.floor(minValue) - 2);
                        },
                        max: function(context) {
                            const maxValue = Math.max(...context.chart.data.datasets[0].data);
                            return Math.ceil(maxValue) + 2;
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: function(value) {
                                return value + '°C';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Suhu (°C)'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                }
            }
        });
    }
    
    function renderHumidityChart(chartData) {
        const ctx = document.getElementById('humidityChart').getContext('2d');
        
        // Destroy existing chart
        if (humidityChart) {
            humidityChart.destroy();
        }
        
        // Create gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(52, 152, 219, 0.2)');
        gradient.addColorStop(1, 'rgba(52, 152, 219, 0.05)');
        
        humidityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Kelembaban (%)',
                    data: chartData.values,
                    borderColor: '#3498db',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: function(context) {
                        const value = context.dataset.data[context.dataIndex];
                        return value > 60 ? '#e74c3c' : '#2ecc71';
                    },
                    pointRadius: 3,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `Kelembaban: ${context.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            maxRotation: 0,
                            font: { size: 10 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Kelembaban (%)'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                }
            }
        });
    }
    
    function updateTemperatureStatus(data) {
        const currentTemp = data.current || data.values[data.values.length - 1];
        const isWarning = currentTemp > 28;
        
        // Update UI elements
        document.getElementById('currentTempValue').textContent = currentTemp.toFixed(1);
        document.getElementById('currentTemperature').textContent = currentTemp.toFixed(1);
        
        // Update status indicator
        const indicator = document.getElementById('tempStatusIndicator');
        const dot = indicator.querySelector('.status-dot');
        const text = indicator.querySelector('.status-text');
        
        dot.className = 'status-dot ' + (isWarning ? 'warning' : 'normal');
        text.textContent = isWarning ? 'WARNING' : 'NORMAL';
        text.style.color = isWarning ? '#e74c3c' : '#2ecc71';
        
        // Update status card
        const card = document.getElementById('tempStatusCard');
        card.className = 'status-indicator-card ' + (isWarning ? 'warning' : 'normal');
        
        const icon = card.querySelector('.status-icon i');
        const value = card.querySelector('.status-value');
        
        icon.className = isWarning ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
        value.textContent = isWarning ? 'WARNING' : 'NORMAL';
        
        // Update min/avg/max
        document.getElementById('tempMin').textContent = data.min.toFixed(1);
        document.getElementById('tempAvg').textContent = data.avg.toFixed(1);
        document.getElementById('tempMax').textContent = data.max.toFixed(1);
        
        // Update last update time
        document.getElementById('tempLastUpdate').innerHTML = 
            `<i class="fas fa-clock me-1"></i>${new Date().toLocaleTimeString('id-ID')}`;
    }
    
    function updateHumidityStatus(data) {
        const currentHumidity = data.current || data.values[data.values.length - 1];
        const isWarning = currentHumidity > 60;
        
        // Update UI elements
        document.getElementById('currentHumidityValue').textContent = currentHumidity.toFixed(1);
        document.getElementById('currentHumidity').textContent = currentHumidity.toFixed(1);
        
        // Update status indicator
        const indicator = document.getElementById('humidityStatusIndicator');
        const dot = indicator.querySelector('.status-dot');
        const text = indicator.querySelector('.status-text');
        
        dot.className = 'status-dot ' + (isWarning ? 'warning' : 'normal');
        text.textContent = isWarning ? 'WARNING' : 'NORMAL';
        text.style.color = isWarning ? '#e74c3c' : '#2ecc71';
        
                // Update status card
        const card = document.getElementById('humidityStatusCard');
        card.className = 'status-indicator-card ' + (isWarning ? 'warning' : 'normal');
        
        const icon = card.querySelector('.status-icon i');
        const value = card.querySelector('.status-value');
        
        icon.className = isWarning ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
        value.textContent = isWarning ? 'WARNING' : 'NORMAL';
        
        // Update min/avg/max
        document.getElementById('humidityMin').textContent = data.min.toFixed(1);
        document.getElementById('humidityAvg').textContent = data.avg.toFixed(1);
        document.getElementById('humidityMax').textContent = data.max.toFixed(1);
        
        // Update last update time
        document.getElementById('humidityLastUpdate').innerHTML = 
            `<i class="fas fa-clock me-1"></i>${new Date().toLocaleTimeString('id-ID')}`;
    }
    
    function changeChartRange(type, range) {
        if (type === 'temperature') {
            currentTempRange = range;
            loadTemperatureChart();
        } else {
            currentHumidityRange = range;
            loadHumidityChart();
        }
        
        // Update active button
        const buttons = event.target.parentElement.querySelectorAll('button');
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
    }
    
    function refreshChart(type) {
        if (type === 'temperature') {
            loadTemperatureChart();
        } else {
            loadHumidityChart();
        }
        showAlert('info', `Grafik ${type === 'temperature' ? 'suhu' : 'kelembaban'} diperbarui`);
    }
    
    // ============================================
    // DEVICE MANAGEMENT
    // ============================================
    function initializeDeviceModal() {
        const modal = document.getElementById('modalDevice');
        const btnShowCreate = document.getElementById('btnShowCreate');
        const btnCancelForm = document.getElementById('btnCancelForm');
        const deviceForm = document.getElementById('deviceForm');
        const tbody = document.getElementById('devicesTbody');
        
        if (!modal) return;
        
        // Modal show event
        modal.addEventListener('show.bs.modal', function() {
            loadDevices();
            hideDeviceForm();
            clearDeviceAlert();
        });
        
        // Show create form
        if (btnShowCreate) {
            btnShowCreate.addEventListener('click', function() {
                showDeviceForm('create');
            });
        }
        
        // Cancel form
        if (btnCancelForm) {
            btnCancelForm.addEventListener('click', function() {
                hideDeviceForm();
            });
        }
        
        // Form submit
        if (deviceForm) {
            deviceForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveDevice();
            });
        }
        
        // Event delegation for edit/delete buttons
        if (tbody) {
            tbody.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.btn-edit');
                const deleteBtn = e.target.closest('.btn-delete');
                
                if (editBtn) {
                    const deviceId = editBtn.dataset.id;
                    editDevice(deviceId);
                }
                
                if (deleteBtn) {
                    const deviceId = deleteBtn.dataset.id;
                    deleteDevice(deviceId);
                }
            });
        }
    }
    
    async function loadDevices() {
        const tbody = document.getElementById('devicesTbody');
        const totalSpan = document.getElementById('totalDevicesModal');
        
        if (!tbody) return;
        
        try {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Loading...</td></tr>';
            
            const response = await fetch(`${API_BASE}/device`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const devices = await response.json();
            
            if (!devices.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Tidak ada device</td></tr>';
                if (totalSpan) totalSpan.textContent = '0';
                return;
            }
            
            tbody.innerHTML = devices.map((device, index) => {
                const statusClass = device.is_active ? 'bg-success' : 'bg-secondary';
                const statusText = device.is_active ? 'Aktif' : 'Nonaktif';
                
                return `
                    <tr data-id="${device.id}">
                        <td>${index + 1}</td>
                        <td>${escapeHtml(device.device_key)}</td>
                        <td>${escapeHtml(device.device_type)}</td>
                        <td>
                            <span class="badge ${statusClass}">${statusText}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${device.id}">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${device.id}">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            if (totalSpan) totalSpan.textContent = devices.length;
            
        } catch (error) {
            console.error('Error loading devices:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>';
        }
    }
    
    function showDeviceForm(mode = 'create', deviceData = null) {
        const formWrap = document.getElementById('deviceFormWrap');
        const formMode = document.getElementById('deviceFormMode');
        const deviceIdInput = document.getElementById('deviceId');
        const deviceKeyInput = document.getElementById('deviceKey');
        const deviceTypeInput = document.getElementById('deviceType');
        
        if (!formWrap) return;
        
        formWrap.style.display = 'block';
        formMode.value = mode;
        
        if (mode === 'create') {
            deviceIdInput.value = '';
            deviceKeyInput.value = '';
        } else if (mode === 'edit' && deviceData) {
            deviceIdInput.value = deviceData.id;
            deviceKeyInput.value = deviceData.device_key || '';
            deviceTypeInput.value = deviceData.device_type || 'suhu';
        }
        
        deviceKeyInput.focus();
    }
    
    function hideDeviceForm() {
        const formWrap = document.getElementById('deviceFormWrap');
        const form = document.getElementById('deviceForm');
        
        if (formWrap) formWrap.style.display = 'none';
        if (form) form.reset();
    }
    
    async function saveDevice() {
        const formMode = document.getElementById('deviceFormMode').value;
        const deviceId = document.getElementById('deviceId').value;
        const deviceKey = document.getElementById('deviceKey').value.trim();
        const deviceType = document.getElementById('deviceType').value;
        
        if (!deviceKey) {
            showDeviceAlert('warning', 'Device Key wajib diisi!');
            return;
        }
        
        const payload = {
            device_key: deviceKey,
            device_type: deviceType,
            is_active: true
        };
        
        let url = `${API_BASE}/device`;
        let method = 'POST';
        
        if (formMode === 'edit' && deviceId) {
            url = `${API_BASE}/device/${deviceId}`;
            method = 'PUT';
        }
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(payload)
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Gagal menyimpan device');
            }
            
            // Reload devices and update dashboard
            await loadDevices();
            await updateDashboardStats();
            
            hideDeviceForm();
            showDeviceAlert('success', 'Device berhasil disimpan!');
            
        } catch (error) {
            console.error('Error saving device:', error);
            showDeviceAlert('danger', error.message || 'Gagal menyimpan device');
        }
    }
    
    async function editDevice(deviceId) {
        try {
            const response = await fetch(`${API_BASE}/device/${deviceId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const result = await response.json();
            const device = result.device || result;
            
            showDeviceForm('edit', device);
            
        } catch (error) {
            console.error('Error loading device:', error);
            showDeviceAlert('danger', 'Gagal memuat data device');
        }
    }
    
    async function deleteDevice(deviceId) {
        if (!confirm('Apakah Anda yakin ingin menghapus device ini?')) {
            return;
        }
        
        try {
            const response = await fetch(`${API_BASE}/device/${deviceId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (!response.ok) {
                const result = await response.json();
                throw new Error(result.message || 'Gagal menghapus device');
            }
            
            // Remove from table and update dashboard
            await loadDevices();
            await updateDashboardStats();
            
            showDeviceAlert('success', 'Device berhasil dihapus!');
            
        } catch (error) {
            console.error('Error deleting device:', error);
            showDeviceAlert('danger', error.message || 'Gagal menghapus device');
        }
    }
    
    function showDeviceAlert(type, message) {
        const alertBox = document.getElementById('devicesAlert');
        if (!alertBox) return;
        
        alertBox.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${escapeHtml(message)}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            </div>
        `;
    }
    
    function clearDeviceAlert() {
        const alertBox = document.getElementById('devicesAlert');
        if (alertBox) alertBox.innerHTML = '';
    }
    
    // ============================================
    // TABLE FUNCTIONS
    // ============================================
    function filterTable(tableId) {
        const searchInput = document.getElementById(`search${tableId === 'infusTable' ? 'Infus' : 'Device'}`);
        const searchTerm = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        
        let visibleCount = 0;
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        
        // Update pagination info
        const infoElement = document.querySelector(`#${tableId}`).closest('.data-table-container').querySelector('.pagination-info');
        if (infoElement) {
            infoElement.textContent = `Menampilkan ${visibleCount} data`;
        }
    }
    
    function filterByStatus(tableId, status) {
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        
        rows.forEach(row => {
            const statusElement = row.querySelector('.status');
            const isVisible = status === 'all' || 
                (statusElement && statusElement.classList.contains(`status-${status}`));
            
            row.style.display = isVisible ? '' : 'none';
        });
    }
    
    function exportData(type) {
        const url = type === 'suhu' ? 
            `${API_BASE}/export/suhu` : 
            `${API_BASE}/export/infus`;
        
        window.open(url, '_blank');
    }
    
    // ============================================
    // UTILITY FUNCTIONS
    // ============================================
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function showAlert(type, message) {
        const container = document.getElementById('alertContainer');
        if (!container) return;
        
        const alertClass = {
            'success': 'alert-success',
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'info': 'alert-info'
        }[type] || 'alert-info';
        
        const icon = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        }[type] || 'fa-info-circle';
        
        const alertId = 'alert-' + Date.now();
        const alertHtml = `
            <div id="${alertId}" class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${icon} me-2"></i>
                ${escapeHtml(message)}
                <button type="button" class="btn-close" onclick="document.getElementById('${alertId}').remove()"></button>
            </div>
        `;
        
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            const alertElement = document.getElementById(alertId);
            if (alertElement) alertElement.remove();
        }, 5000);
    }
    
    function showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.style.display = 'flex';
    }
    
    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.style.display = 'none';
    }
    
    function startAutoRefresh() {
        // Clear existing interval
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        
        // Set new interval (30 seconds)
        autoRefreshInterval = setInterval(() => {
            refreshDashboardData();
        }, 30000);
    }
    
    function getSampleTemperatureData() {
        const now = new Date();
        const labels = [];
        const values = [];
        
        // Generate sample data for last hour
        for (let i = 59; i >= 0; i--) {
            const time = new Date(now.getTime() - i * 60000);
            labels.push(time.getMinutes().toString().padStart(2, '0'));
            
            // Random temperature between 25-30
            const temp = 25 + Math.random() * 5;
            values.push(parseFloat(temp.toFixed(1)));
        }
        
        const currentTemp = values[values.length - 1];
        
        return {
            labels: labels,
            values: values,
            current: currentTemp,
            min: Math.min(...values),
            max: Math.max(...values),
            avg: values.reduce((a, b) => a + b, 0) / values.length
        };
    }
    
    function getSampleHumidityData() {
        const now = new Date();
        const labels = [];
        const values = [];
        
        // Generate sample data for last hour
        for (let i = 59; i >= 0; i--) {
            const time = new Date(now.getTime() - i * 60000);
            labels.push(time.getMinutes().toString().padStart(2, '0'));
            
            // Random humidity between 50-70
            const humidity = 50 + Math.random() * 20;
            values.push(parseFloat(humidity.toFixed(1)));
        }
        
        const currentHumidity = values[values.length - 1];
        
        return {
            labels: labels,
            values: values,
            current: currentHumidity,
            min: Math.min(...values),
            max: Math.max(...values),
            avg: values.reduce((a, b) => a + b, 0) / values.length
        };
    }
    
    // ============================================
    // AUTO REFRESH DEVICE STATUS
    // ============================================
    // Tambahkan device status refresh ke auto-refresh
    let deviceStatusInterval = null;
    
    function startAutoRefresh() {
        // Clear existing intervals
        if (autoRefreshInterval) clearInterval(autoRefreshInterval);
        if (deviceStatusInterval) clearInterval(deviceStatusInterval);
        
        // Set dashboard refresh interval (30 seconds)
        autoRefreshInterval = setInterval(() => {
            refreshDashboardData();
        }, 30000);
        
        // Set device status refresh interval (60 seconds)
        deviceStatusInterval = setInterval(() => {
            refreshDeviceStatus();
        }, 60000);
    }
    
    // ============================================
    // EXPORT FUNCTIONS TO GLOBAL SCOPE
    // ============================================
    window.refreshDashboardData = refreshDashboardData;
    window.changeChartRange = changeChartRange;
    window.refreshChart = refreshChart;
    window.filterTable = filterTable;
    window.filterByStatus = filterByStatus;
    window.exportData = exportData;
    window.refreshDeviceStatus = refreshDeviceStatus;
    window.filterDeviceStatusTable = filterDeviceStatusTable;
    window.filterDeviceStatusBy = filterDeviceStatusBy;
</script>
@endsection