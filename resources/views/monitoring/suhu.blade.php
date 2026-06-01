@extends('layout.app')

@section('judul', 'Monitoring Suhu')

@section('head')
<style>
    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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
        background: linear-gradient(135deg, #7ABF55 0%, #61C5C3 50%, #6DC18A 100%);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
    
    .card-icon i {
        font-size: 1.5rem;
    }
    
    .card-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #7ABF55 0%, #61C5C3 100%);
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

    /* Status Badges - Hanya dua status */
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

    /* Combined Data Section */
    .data-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .data-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #7ABF55 0%, #61C5C3 50%, #6DC18A 100%);
    }

    .section-header {
        padding: 25px 25px 0 25px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 5px 0;
    }

    .section-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin: 0;
    }

    .filter-wrapper {
        padding: 0 25px 25px 25px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 20px;
    }

    /* Filter Grid */
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-group {
        margin-bottom: 0;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #2c3e50;
        display: block;
    }

    .filter-select, .filter-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 13px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .filter-select:focus, .filter-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        background: white;
        outline: none;
    }

    /* Date Range */
    .date-range-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 10px;
    }

    .datetime-group {
        display: grid;
        grid-template-columns: 1fr 80px;
        gap: 8px;
    }

    .datetime-input-group {
        position: relative;
    }

    .datetime-input-group .datetime-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #7f8c8d;
        pointer-events: none;
    }

    /* Quick Filters */
    .quick-filters {
        margin-top: 15px;
    }

    .quick-filters-label {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #2c3e50;
        display: block;
    }

    .quick-filter-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .quick-filter-buttons button {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
    }

    /* Action Buttons */
    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-apply {
        background: linear-gradient(135deg, #7ABF55 0%, #61C5C3 100%);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-apply:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(122, 191, 85, 0.2);
    }

    .btn-reset {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        color: #6c757d;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-reset:hover {
        background: #e9ecef;
        border-color: #ced4da;
    }

    /* Table Section */
    .table-wrapper {
        padding: 0 25px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 300px;
    }

    .search-box input {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 10px 15px 10px 40px;
        border-radius: 8px;
        color: #2c3e50;
        width: 100%;
        transition: all 0.3s;
        font-size: 14px;
    }

    .search-box input:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        background: white;
        outline: none;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #7f8c8d;
    }

    .table-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .table-content {
        padding: 0 25px 25px 25px;
    }

    /* Table Styling */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    .table {
        margin-bottom: 0;
        width: 100%;
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        font-size: 13px;
        color: #2c3e50;
        padding: 12px 16px;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 12px 16px;
        font-size: 13px;
        vertical-align: middle;
        border-top: 1px solid #f0f0f0;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .timestamp {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #7f8c8d;
    }

    /* Pagination */
    .pagination-container {
        padding: 20px 25px;
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
    }

    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 12px;
    }

    .pagination-controls {
        display: flex;
        gap: 5px;
    }

    .pagination-controls button {
        padding: 6px 12px;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 6px;
        font-size: 12px;
        transition: all 0.3s ease;
        min-width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination-controls button.active {
        background: linear-gradient(135deg, #7ABF55 0%, #61C5C3 100%);
        color: white;
        border-color: transparent;
    }

    .pagination-controls button:hover:not(.active) {
        background: #f8f9fa;
        border-color: #ced4da;
    }

    /* Alert Styling */
    .alert-container {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
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

    /* Loading Spinner */
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

    /* Export Modal Styling */
    .export-stat-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        transition: all 0.3s ease;
        background: white;
    }

    .export-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .export-stat-card .value {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .export-stat-card .label {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Loading indicator for export */
    .export-loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(0,0,0,.1);
        border-radius: 50%;
        border-top-color: #3498db;
        animation: spin 1s ease-in-out infinite;
        margin-right: 10px;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .section-header,
        .filter-wrapper,
        .table-wrapper,
        .table-content,
        .pagination-container {
            padding: 20px 15px;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .date-range-group {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .pagination {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .quick-filter-buttons {
            justify-content: center;
        }
        
        .alert-container {
            left: 10px;
            right: 10px;
            max-width: none;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('Content')
<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <div>
        <h1 class="h3 fw-bold text-dark">Monitoring Suhu Ruangan</h1>
        <p class="text-muted">Sistem monitoring real-time suhu ruangan</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button id="btnOpenDevices" type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDeviceSuhu">
                <i class="fas fa-microchip me-1"></i> Device
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalRuangan">
                <i class="fas fa-door-open me-1"></i> Ruangan
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshAllData()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showExportModal()">
                <i class="fas fa-download me-1"></i> Ekspor
            </button>
        </div>
    </div>
</div>

<!-- Alert Notifications Container -->
<div id="alertContainer" class="alert-container"></div>

<!-- Dashboard Grid -->
<div class="dashboard-grid mb-4">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Device Terpantau</div>
            <div class="card-icon">
                <i class="fas fa-microchip"></i>
            </div>
        </div>
        <div class="card-value" id="totalDevices">0</div>
        <div class="card-footer">
            <i class="fas fa-info-circle text-primary"></i>
            <span>Total device suhu aktif</span>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Status Normal</div>
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="card-value" id="normalDevices">0</div>
        <div class="card-footer">
            <i class="fas fa-info-circle text-success"></i>
            <span>Suhu & kelembaban dalam batas</span>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Status Warning</div>
            <div class="card-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="card-value" id="warningDevices">0</div>
        <div class="card-footer">
            <i class="fas fa-info-circle text-warning"></i>
            <span>Suhu atau kelembaban tidak ideal</span>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Total Data</div>
            <div class="card-icon">
                <i class="fas fa-database"></i>
            </div>
        </div>
        <div class="card-value" id="totalData">0</div>
        <div class="card-footer">
            <i class="fas fa-info-circle text-info"></i>
            <span id="totalDataInfo">Total data monitoring</span>
        </div>
    </div>
</div>

<!-- Status Suhu per Device -->
<div class="section-title">Status Suhu per Device</div>
<div class="table-responsive mb-4">
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th>Device</th>
                <th>ID Ruang</th>
                <th>Nama Ruang</th>
                <th>Suhu (°C)</th>
                <th>Kelembaban (%)</th>
                <th>Status</th>
                <th>Update Terakhir</th>
            </tr>
        </thead>
        <tbody id="deviceStatusBody">
            <tr>
                <td colspan="7" class="text-center text-muted">
                    <i class="fas fa-spinner fa-spin me-2"></i>Memuat data...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Combined Data Section -->
<div class="data-section">
    <!-- Header -->
    <div class="section-header">
        <h3 class="section-title">Riwayat Log Suhu</h3>
        <p class="section-subtitle">Monitoring dan filter data suhu ruangan secara real-time</p>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-wrapper">
        <div class="filter-grid">
            <!-- Filter Device -->
            <div class="filter-group">
                <label class="filter-label">Device</label>
                <select class="filter-select" id="filterDevice">
                    <option value="Semua">Semua Device</option>
                </select>
            </div>
            
            <!-- Filter Ruangan -->
            <div class="filter-group">
                <label class="filter-label">Ruangan</label>
                <select class="filter-select" id="filterRuangan">
                    <option value="Semua">Semua Ruangan</option>
                </select>
            </div>
            
            <!-- Filter Status Suhu -->
            <div class="filter-group">
                <label class="filter-label">Status Suhu</label>
                <select class="filter-select" id="filterStatus">
                    <option value="Semua">Semua Status</option>
                    <option value="Normal">Normal</option>
                    <option value="Warning">Warning</option>
                </select>
            </div>
            
            <!-- Filter Status Kelembaban -->
            <div class="filter-group">
                <label class="filter-label">Status Kelembaban</label>
                <select class="filter-select" id="filterKelembaban">
                    <option value="Semua">Semua Status</option>
                    <option value="Normal">Normal</option>
                    <option value="Warning">Warning</option>
                </select>
            </div>
        </div>
        
        <!-- Date Range -->
        <div class="date-range-group">
            <div class="filter-group">
                <label class="filter-label">Dari Tanggal & Jam</label>
                <div class="datetime-group">
                    <div class="datetime-input-group">
                        <input type="text" class="filter-input date-picker" placeholder="dd/mm/yyyy" id="filterDariTanggal">
                        <i class="fas fa-calendar datetime-icon"></i>
                    </div>
                    <div class="datetime-input-group">
                        <input type="text" class="filter-input time-input" placeholder="00:00" id="filterDariJam">
                        <i class="fas fa-clock datetime-icon"></i>
                    </div>
                </div>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Sampai Tanggal & Jam</label>
                <div class="datetime-group">
                    <div class="datetime-input-group">
                        <input type="text" class="filter-input date-picker" placeholder="dd/mm/yyyy" id="filterSampaiTanggal">
                        <i class="fas fa-calendar datetime-icon"></i>
                    </div>
                    <div class="datetime-input-group">
                        <input type="text" class="filter-input time-input" placeholder="23:59" id="filterSampaiJam">
                        <i class="fas fa-clock datetime-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Filters -->
        <div class="quick-filters">
            <label class="quick-filters-label">Filter Cepat:</label>
            <div class="quick-filter-buttons">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDateRange('today')">
                    Hari Ini
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDateRange('yesterday')">
                    Kemarin
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDateRange('week')">
                    7 Hari Terakhir
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDateRange('month')">
                    Bulan Ini
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDateRange('last_month')">
                    Bulan Lalu
                </button>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="filter-actions">
            <button class="btn-reset" onclick="resetFilters()">
                <i class="fas fa-redo"></i> Reset Filter
            </button>
            <button class="btn-apply" onclick="loadMonitoringData()">
                <i class="fas fa-filter"></i> Terapkan Filter
            </button>
        </div>
    </div>
    
    <!-- Table Section -->
    <div class="table-wrapper">
        <!-- Table Header dengan Search -->
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari data monitoring..." onkeyup="filterTable()">
            </div>
            <div class="table-actions">
                <button class="btn btn-sm btn-outline-secondary" onclick="loadMonitoringData(currentPage)">
                    <i class="fas fa-sync-alt"></i> Refresh Table
                </button>
            </div>
        </div>
        
        <!-- Table Content -->
        <div class="table-content">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>WAKTU</th>
                            <th>DEVICE</th>
                            <th>RUANG</th>
                            <th>SUHU (°C)</th>
                            <th>KELEMBABAN (%)</th>
                            <th>STATUS SUHU</th>
                            <th>STATUS KELEMBABAN</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="fas fa-spinner fa-spin me-2"></i>Memuat data monitoring...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container">
            <div class="pagination">
                <div class="pagination-info" id="paginationInfo">
                    Menampilkan 0-0 dari 0 data
                </div>
                <div class="pagination-controls" id="paginationControls">
                    <button class="pagination-prev" disabled><i class="fas fa-chevron-left"></i></button>
                    <button class="active">1</button>
                    <button class="pagination-next" disabled><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
</div>
@endsection

@section('Modal')
<!-- Modal Device Suhu -->
<div class="modal fade" id="modalDeviceSuhu" tabindex="-1" aria-labelledby="modalDeviceSuhuLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-start gap-2">
        <div>
          <h5 class="modal-title" id="modalDeviceSuhuLabel">
            <i class="fas fa-thermometer-half me-2"></i> Daftar Device Suhu
          </h5>
          <small class="text-muted">CRUD device tanpa refresh</small>
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
                <th style="width:180px">Aksi</th>
              </tr>
            </thead>
            <tbody id="devicesSuhuTbody">
              <tr><td colspan="4" class="text-center">Loading device...</td></tr>
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

<!-- Modal Ruangan -->
<div class="modal fade" id="modalRuangan" tabindex="-1" aria-labelledby="modalRuanganLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-start gap-2">
                <div>
                    <h5 class="modal-title" id="modalRuanganLabel">
                        <i class="fas fa-door-open me-2"></i> Manajemen Ruangan
                    </h5>
                    <small class="text-muted">Kelola data ruangan dan device</small>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <button id="btnShowCreateRoom" type="button" class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-1"></i> Tambah Ruangan
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div id="roomsAlert" class="alert-container"></div>
                <div id="roomFormWrap" class="mb-3" style="display:none;">
                    <div class="card p-3">
                        <form id="roomForm">
                            <input type="hidden" id="roomFormMode" value="create">
                            <input type="hidden" id="roomId" value="">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">ID Ruang *</label>
                                    <select id="roomIdSelect" class="form-select" required>
                                        <option value="">Pilih ID Ruang</option>
                                    </select>
                                    <small class="text-muted">Pilih ID ruangan dari daftar</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Device *</label>
                                    <select id="roomDeviceSelect" class="form-select" required>
                                        <option value="">Pilih Device</option>
                                    </select>
                                    <small class="text-muted">Pilih device yang terpasang di ruangan</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status *</label>
                                    <select id="roomStatusSelect" class="form-select" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </select>
                                    <small class="text-muted">Pilih status keaktifan ruangan</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Suhu Min (°C)</label>
                                    <input type="number" id="roomTempMin" class="form-control form-control-sm" step="0.1" value="15.0" required>
                                    <small class="text-muted">Alarm dingin (Default: 15°C)</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Suhu Maks (°C)</label>
                                    <input type="number" id="roomTempMax" class="form-control form-control-sm" step="0.1" value="28.0" required>
                                    <small class="text-muted">Alarm panas (Default: 28°C)</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kelembaban Min (%)</label>
                                    <input type="number" id="roomHumMin" class="form-control form-control-sm" step="0.1" value="30.0" required>
                                    <small class="text-muted">Alarm kering (Default: 30%)</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kelembaban Maks (%)</label>
                                    <input type="number" id="roomHumMax" class="form-control form-control-sm" step="0.1" value="60.0" required>
                                    <small class="text-muted">Alarm basah (Default: 60%)</small>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 mt-2">
                                        <button id="btnCancelRoomForm" type="button" class="btn btn-sm btn-outline-secondary">Batal</button>
                                        <button id="btnSubmitRoomForm" type="submit" class="btn btn-sm btn-primary">Simpan Ruangan</button>
                                    </div>
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
                                <th>ID Ruang</th>
                                <th>Nama Ruangan</th>
                                <th>Device</th>
                                <th>Suhu</th>
                                <th>Kelembaban</th>
                                <th>Status</th>
                                <th style="width:180px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="roomsTbody">
                            <tr><td colspan="8" class="text-center">Loading ruangan...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <small class="text-muted me-auto">Total: <span id="totalRooms">0</span> ruangan</small>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div class="modal fade" id="modalQRCode" tabindex="-1" aria-labelledby="modalQRCodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="modalQRCodeLabel">
                    <i class="fas fa-qrcode text-success me-2"></i> QR Code Monitoring Publik
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted small mb-3">Pindai kode QR ini untuk memantau suhu & kelembaban secara langsung di ruangan <strong id="qrRoomName"></strong> tanpa login.</p>
                <div class="d-inline-block p-3 bg-white rounded-3 shadow-sm border mb-3">
                    <img id="qrCodeImage" src="" alt="QR Code" style="width: 250px; height: 250px; display: block; margin: 0 auto;">
                </div>
                <div class="mt-2">
                    <a id="btnOpenPublicLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-external-link-alt me-1"></i> Buka Link
                    </a>
                    <button id="btnPrintQR" type="button" class="btn btn-sm btn-primary">
                        <i class="fas fa-print me-1"></i> Cetak QR
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ekspor Data -->
<div class="modal fade" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExportLabel">
                    <i class="fas fa-download me-2"></i> Ekspor Data Monitoring
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="exportAlert" class="alert-container"></div>
                
                <!-- Statistics Section -->
                <div class="mb-4">
                    <h6 class="mb-3">Statistik Data:</h6>
                    <div class="row" id="exportStats">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light export-stat-card">
                                <div class="card-body text-center p-3">
                                    <div class="text-primary fw-bold" id="statToday">0</div>
                                    <small class="text-muted">Data Hari Ini</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light export-stat-card">
                                <div class="card-body text-center p-3">
                                    <div class="text-primary fw-bold" id="statWeek">0</div>
                                    <small class="text-muted">Data 7 Hari</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light export-stat-card">
                                <div class="card-body text-center p-3">
                                    <div class="text-primary fw-bold" id="statAll">0</div>
                                    <small class="text-muted">Total Semua Data</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Export Form -->
                <form id="exportForm">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Pilih Periode Ekspor *</label>
                            <select class="form-select" id="exportRange" required>
                                <option value="">Pilih periode...</option>
                                <option value="today">Hari Ini</option>
                                <option value="yesterday">Kemarin</option>
                                <option value="week">7 Hari Terakhir</option>
                                <option value="month">Bulan Ini</option>
                                <option value="last_month">Bulan Lalu</option>
                                <option value="custom">Custom Tanggal</option>
                                <option value="all">Semua Data</option>
                            </select>
                        </div>
                        
                        <!-- Custom Date Range (hidden by default) -->
                        <div id="customDateRange" class="row g-3" style="display: none;">
                            <div class="col-md-6">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="text" class="form-control date-picker" id="customStart" placeholder="dd/mm/yyyy">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="text" class="form-control date-picker" id="customEnd" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                        
                        <!-- Applied Filters Info -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Filter yang berlaku:</strong>
                                <div id="currentFilters" class="mt-1 small">
                                    Device: Semua, Ruangan: Semua, Status: Semua
                                </div>
                            </div>
                        </div>
                        
                        <!-- Warning for Large Data -->
                        <div class="col-12">
                            <div class="alert alert-warning" id="exportWarning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Perhatian:</strong> Mengekspor data dalam jumlah besar mungkin memerlukan waktu beberapa menit.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="startExport()">
                    <i class="fas fa-download me-2"></i> Mulai Ekspor
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // ============================================
    // KONFIGURASI GLOBAL
    // ============================================
    const API_BASE = '{{ url("/") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    
    // State variables
    let currentPage = 1;
    let totalPages = 1;
    let totalItems = 0;
    let perPage = 10;
    
    // Data cache
    let devicesCache = [];
    let roomsCache = [];
    let monitoringDataCache = [];
    
    // ============================================
    // INISIALISASI UTAMA
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing Monitoring System...');
        
        // Inisialisasi komponen
        initializeDatePickers();
        initializeEventListeners();
        
        // Load data awal
        loadInitialData();
        
        // Start auto-refresh setiap 30 detik
        startAutoRefresh();
        
        console.log('Monitoring System initialized successfully!');
    });
    
    // ============================================
    // LOAD DATA AWAL
    // ============================================
    async function loadInitialData() {
        showLoading();
        
        try {
            // Load secara paralel untuk performa
            await Promise.all([
                loadDashboardData(),      // Dashboard dan tabel status device
                loadMonitoringData(),     // Data monitoring
                loadDropdownData()        // Dropdown filter
            ]);
            
            showAlert('success', 'Sistem monitoring berhasil dimuat!');
            
        } catch (error) {
            console.error('Error loading initial data:', error);
            showAlert('error', 'Gagal memuat data awal. ' + error.message);
            
        } finally {
            hideLoading();
        }
    }
    
    // ============================================
    // DASHBOARD FUNCTIONS
    // ============================================
    async function loadDashboardData() {
        try {
            console.log('Loading dashboard data...');
            
            // Build query parameters sesuai dengan filter yang aktif
            const params = new URLSearchParams({
                device: document.getElementById('filterDevice')?.value || 'Semua',
                ruangan: document.getElementById('filterRuangan')?.value || 'Semua',
                status_suhu: document.getElementById('filterStatus')?.value || 'Semua',
                status_kelembaban: document.getElementById('filterKelembaban')?.value || 'Semua',
                dari_tanggal: document.getElementById('filterDariTanggal')?.value || '',
                dari_jam: document.getElementById('filterDariJam')?.value || '',
                sampai_tanggal: document.getElementById('filterSampaiTanggal')?.value || '',
                sampai_jam: document.getElementById('filterSampaiJam')?.value || ''
            });
            
            const response = await fetch(`${API_BASE}/monitoring/suhu/dashboard?${params}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    console.log('Dashboard data loaded:', result.data);
                    
                    // Update dashboard cards
                    document.getElementById('totalDevices').textContent = formatNumber(result.data.total_devices || 0);
                    document.getElementById('normalDevices').textContent = formatNumber(result.data.normal_devices || 0);
                    document.getElementById('warningDevices').textContent = formatNumber(result.data.warning_devices || 0);
                    document.getElementById('totalData').textContent = formatNumber(result.data.total_data || 0);
                    
                    // Update info text untuk total data
                    updateTotalDataInfo();
                    
                    // Update device status table
                    updateDeviceStatusTable(result.data.device_status || []);
                    
                    return true;
                }
            }
            throw new Error(`HTTP ${response.status}`);
            
        } catch (error) {
            console.error('Error loading dashboard:', error);
            showAlert('warning', 'Gagal memuat data dashboard. Menggunakan data cache.');
            return false;
        }
    }

    // Fungsi untuk update info text total data berdasarkan filter
    function updateTotalDataInfo() {
        const infoElement = document.getElementById('totalDataInfo');
        if (!infoElement) return;
        
        const deviceFilter = document.getElementById('filterDevice')?.value;
        const roomFilter = document.getElementById('filterRuangan')?.value;
        const dateFilter = document.getElementById('filterDariTanggal')?.value;
        
        let infoText = 'Total data monitoring';
        
        if (deviceFilter && deviceFilter !== 'Semua') {
            infoText += ` (Device: ${deviceFilter})`;
        }
        
        if (roomFilter && roomFilter !== 'Semua') {
            infoText += ` (Ruang: ${roomFilter})`;
        }
        
        if (dateFilter) {
            infoText += ` (Tanggal: ${dateFilter})`;
        }
        
        infoElement.textContent = infoText;
    }

    function updateDeviceStatusTable(devices) {
        const tbody = document.getElementById('deviceStatusBody');
        if (!tbody) return;
        
        if (!devices || devices.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-microchip-slash me-2"></i>
                        <div>Tidak ada device aktif</div>
                        <small class="text-muted mt-2">Tambahkan device di menu Device dan tetapkan ke ruangan</small>
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = devices.map(device => {
            const statusClass = getStatusBadgeClass(device.status_overall);
            const normalizedStatus = normalizeStatus(device.status_overall);
            
            return `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-microchip text-primary me-2"></i>
                            <div>
                                <div class="fw-bold">${escapeHtml(device.device_name)}</div>
                                <small class="text-muted">${escapeHtml(device.device)}</small>
                            </div>
                        </div>
                    </td>
                    <td><code>${escapeHtml(device.ruang_id)}</code></td>
                    <td>${escapeHtml(device.ruang_nama)}</td>
                    <td class="fw-bold">${device.suhu}°C</td>
                    <td>${device.kelembaban}%</td>
                    <td><span class="badge ${statusClass}">${escapeHtml(normalizedStatus)}</span></td>
                    <td class="timestamp">
                        <i class="fas fa-clock me-1 text-muted"></i>
                        ${escapeHtml(device.waktu)}
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    // ============================================
    // MONITORING DATA FUNCTIONS
    // ============================================
    async function loadMonitoringData(page = 1) {
        showLoading();
        
        try {
            // Build query parameters
            const params = new URLSearchParams({
                page: page,
                per_page: perPage,
                device: document.getElementById('filterDevice')?.value || 'Semua',
                ruangan: document.getElementById('filterRuangan')?.value || 'Semua',
                status_suhu: document.getElementById('filterStatus')?.value || 'Semua',
                status_kelembaban: document.getElementById('filterKelembaban')?.value || 'Semua',
                dari_tanggal: document.getElementById('filterDariTanggal')?.value || '',
                dari_jam: document.getElementById('filterDariJam')?.value || '',
                sampai_tanggal: document.getElementById('filterSampaiTanggal')?.value || '',
                sampai_jam: document.getElementById('filterSampaiJam')?.value || ''
            });
            
            const response = await fetch(`${API_BASE}/monitoring/suhu/data?${params}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    // Normalisasi data status sebelum disimpan ke cache
                    const normalizedData = (result.data || []).map(item => {
                        return {
                            ...item,
                            status_suhu: normalizeStatus(item.status_suhu),
                            status_kelembaban: normalizeStatus(item.status_kelembaban)
                        };
                    });
                    
                    monitoringDataCache = normalizedData;
                    updateMonitoringTable(monitoringDataCache);
                    updatePagination(result);
                    
                    // PERBARUI TOTAL DATA secara dinamis
                    await updateTotalData();
                    
                    hideLoading();
                    return;
                }
            }
            throw new Error(`HTTP ${response.status}`);
            
        } catch (error) {
            console.error('Error loading monitoring data:', error);
            showAlert('error', 'Gagal memuat data monitoring: ' + error.message);
        } finally {
            hideLoading();
        }
    }
    
    // FUNGSI UTAMA: Update total data secara dinamis berdasarkan filter
    async function updateTotalData() {
        try {
            const params = new URLSearchParams({
                device: document.getElementById('filterDevice')?.value || 'Semua',
                ruangan: document.getElementById('filterRuangan')?.value || 'Semua',
                status_suhu: document.getElementById('filterStatus')?.value || 'Semua',
                status_kelembaban: document.getElementById('filterKelembaban')?.value || 'Semua',
                dari_tanggal: document.getElementById('filterDariTanggal')?.value || '',
                dari_jam: document.getElementById('filterDariJam')?.value || '',
                sampai_tanggal: document.getElementById('filterSampaiTanggal')?.value || '',
                sampai_jam: document.getElementById('filterSampaiJam')?.value || ''
            });
            
            const response = await fetch(`${API_BASE}/monitoring/suhu/total-data?${params}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    document.getElementById('totalData').textContent = formatNumber(result.total_data || 0);
                    updateTotalDataInfo();
                    console.log('Total data updated:', result.total_data);
                    return result.total_data;
                }
            }
        } catch (error) {
            console.error('Error updating total data:', error);
        }
        return 0;
    }
    
    function updateMonitoringTable(data) {
        const tbody = document.getElementById('tableBody');
        if (!tbody) return;
        
        if (!data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-database me-2"></i>Tidak ada data yang ditemukan
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = data.map(item => {
            // Normalisasi status menjadi hanya Normal atau Warning
            const statusSuhu = normalizeStatus(item.status_suhu);
            const statusKelembaban = normalizeStatus(item.status_kelembaban);
            
            const statusSuhuClass = getStatusClass(statusSuhu);
            const statusKelembabanClass = getStatusClass(statusKelembaban);
            
            return `
                <tr>
                    <td class="timestamp">${escapeHtml(item.waktu)}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-microchip text-primary me-2"></i>
                            ${escapeHtml(item.device)}
                        </div>
                    </td>
                    <td>${escapeHtml(item.ruang)}</td>
                    <td><strong>${item.suhu}°C</strong></td>
                    <td>${item.kelembaban}%</td>
                    <td>
                        <span class="status-badge ${statusSuhuClass}">
                            ${escapeHtml(statusSuhu)}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge ${statusKelembabanClass}">
                            ${escapeHtml(statusKelembaban)}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    // ============================================
    // DROPDOWN DATA FUNCTIONS
    // ============================================
    async function loadDropdownData() {
        try {
            // Load devices untuk dropdown
            const devicesResponse = await fetch(`${API_BASE}/monitoring/suhu/devices-filter`, {
                headers: { 'Accept': 'application/json' }
            });
            
            if (devicesResponse.ok) {
                const devicesResult = await devicesResponse.json();
                if (devicesResult.success) {
                    devicesCache = devicesResult.devices || [];
                    updateDeviceDropdown();
                }
            }
            
            // Load rooms untuk dropdown
            const roomsResponse = await fetch(`${API_BASE}/rooms/list`, {
                headers: { 'Accept': 'application/json' }
            });
            
            if (roomsResponse.ok) {
                const roomsResult = await roomsResponse.json();
                if (roomsResult.ok && roomsResult.rows) {
                    roomsCache = roomsResult.rows;
                    updateRoomDropdown();
                }
            }
            
        } catch (error) {
            console.error('Error loading dropdown data:', error);
            showAlert('warning', 'Gagal memuat data dropdown filter');
        }
    }
    
    function updateDeviceDropdown() {
        const select = document.getElementById('filterDevice');
        if (!select) return;
        
        const selectedValue = select.value;
        
        select.innerHTML = '<option value="Semua">Semua Device</option>';
        
        devicesCache.forEach(device => {
            const option = document.createElement('option');
            option.value = device.device_key;
            option.textContent = device.name || device.device_key;
            select.appendChild(option);
        });
        
        // Restore selection
        if (selectedValue && Array.from(select.options).some(opt => opt.value === selectedValue)) {
            select.value = selectedValue;
        }
    }
    
    function updateRoomDropdown() {
        const select = document.getElementById('filterRuangan');
        if (!select) return;
        
        const selectedValue = select.value;
        
        select.innerHTML = '<option value="Semua">Semua Ruangan</option>';
        
        roomsCache.forEach(room => {
            const roomId = room.id_ruang || room.room_id || room.id;
            const roomName = room.nama_ruang || room.room_name || 'Unknown';
            
            if (roomId) {
                const option = document.createElement('option');
                option.value = roomId;
                option.textContent = `${roomId} - ${roomName}`;
                select.appendChild(option);
            }
        });
        
        // Restore selection
        if (selectedValue && Array.from(select.options).some(opt => opt.value === selectedValue)) {
            select.value = selectedValue;
        }
    }
    
    // ============================================
    // STATUS NORMALIZATION FUNCTIONS
    // ============================================
    // Fungsi untuk mengubah semua status menjadi hanya Normal atau Warning
    function normalizeStatus(status) {
        if (!status) return 'Normal';
        
        const statusLower = status.toLowerCase();
        
        // Mapping semua status menjadi hanya Normal atau Warning
        if (statusLower.includes('normal')) return 'Normal';
        
        // Semua status selain normal dianggap Warning
        return 'Warning';
    }
    
    // Fungsi untuk mendapatkan class CSS berdasarkan status
    function getStatusClass(status) {
        if (!status) return 'status-normal';
        
        const statusLower = status.toLowerCase();
        
        // Hanya dua status yang diizinkan: NORMAL dan WARNING
        if (statusLower.includes('normal')) return 'status-normal';
        
        // Semua status selain normal dianggap Warning
        return 'status-warning';
    }
    
    // Fungsi untuk mendapatkan badge class
    function getStatusBadgeClass(status) {
        if (!status) return 'bg-secondary';
        
        const statusLower = status.toLowerCase();
        
        // Hanya dua status: Normal (success) dan Warning (warning)
        if (statusLower.includes('normal')) return 'bg-success';
        
        // Semua status selain normal dianggap Warning
        return 'bg-warning';
    }
    
    // ============================================
    // EXPORT FUNCTIONS
    // ============================================
    function showExportModal() {
        // Load export statistics
        loadExportStats();
        
        // Show current filters
        updateExportFilterInfo();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('modalExport'));
        modal.show();
    }

    async function loadExportStats() {
        try {
            showLoading();
            
            const response = await fetch(`${API_BASE}/monitoring/suhu/export-stats`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success && result.stats) {
                    document.getElementById('statToday').textContent = formatNumber(result.stats.today.count);
                    document.getElementById('statWeek').textContent = formatNumber(result.stats.week.count);
                    document.getElementById('statAll').textContent = formatNumber(result.stats.all.count);
                }
            }
        } catch (error) {
            console.error('Error loading export stats:', error);
        } finally {
            hideLoading();
        }
    }

    function updateExportFilterInfo() {
        const deviceFilter = document.getElementById('filterDevice').value;
        const roomFilter = document.getElementById('filterRuangan').value;
        const statusFilter = document.getElementById('filterStatus').value;
        const humidityFilter = document.getElementById('filterKelembaban').value;
        
        let filterText = `Device: ${deviceFilter === 'Semua' ? 'Semua' : deviceFilter}, `;
        filterText += `Ruangan: ${roomFilter === 'Semua' ? 'Semua' : roomFilter}, `;
        filterText += `Status Suhu: ${statusFilter}, `;
        filterText += `Status Kelembaban: ${humidityFilter}`;
        
        document.getElementById('currentFilters').textContent = filterText;
    }

    // Toggle custom date range
    document.getElementById('exportRange')?.addEventListener('change', function() {
        const customRangeDiv = document.getElementById('customDateRange');
        if (this.value === 'custom') {
            customRangeDiv.style.display = 'flex';
        } else {
            customRangeDiv.style.display = 'none';
        }
    });

    async function startExport() {
        const exportRange = document.getElementById('exportRange').value;
        
        if (!exportRange) {
            showAlert('warning', 'Silakan pilih periode ekspor');
            return;
        }
        
        // Validate custom date range
        if (exportRange === 'custom') {
            const startDate = document.getElementById('customStart').value;
            const endDate = document.getElementById('customEnd').value;
            
            if (!startDate || !endDate) {
                showAlert('warning', 'Silakan isi tanggal awal dan akhir untuk custom range');
                return;
            }
        }
        
        // Show confirmation for large data
        if (exportRange === 'all') {
            if (!confirm('Mengekspor SEMUA data bisa menghasilkan file yang sangat besar. Apakah Anda yakin?')) {
                return;
            }
        }
        
        // Prepare export data
        const exportData = {
            export_range: exportRange,
            custom_start: exportRange === 'custom' ? document.getElementById('customStart').value : '',
            custom_end: exportRange === 'custom' ? document.getElementById('customEnd').value : '',
            device: document.getElementById('filterDevice').value,
            ruangan: document.getElementById('filterRuangan').value,
            status_suhu: document.getElementById('filterStatus').value,
            status_kelembaban: document.getElementById('filterKelembaban').value,
            dari_tanggal: document.getElementById('filterDariTanggal').value,
            dari_jam: document.getElementById('filterDariJam').value,
            sampai_tanggal: document.getElementById('filterSampaiTanggal').value,
            sampai_jam: document.getElementById('filterSampaiJam').value,
            _token: CSRF_TOKEN
        };
        
        try {
            showLoading();
            showExportAlert('info', 'Menyiapkan ekspor data...');
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `${API_BASE}/monitoring/suhu/export`;
            form.style.display = 'none';
            
            Object.keys(exportData).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = exportData[key];
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
            
            // Wait a moment before cleaning up
            setTimeout(() => {
                document.body.removeChild(form);
                hideLoading();
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalExport'));
                if (modal) modal.hide();
                
                showAlert('success', 'Permintaan ekspor berhasil dikirim. File akan segera diunduh.');
            }, 1000);
            
        } catch (error) {
            console.error('Export error:', error);
            showExportAlert('danger', 'Gagal memulai ekspor: ' + error.message);
            hideLoading();
        }
    }

    function showExportAlert(type, message) {
        const alertBox = document.getElementById('exportAlert');
        if (!alertBox) return;
        
        const alertClass = {
            'success': 'alert-success',
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'info': 'alert-info',
            'danger': 'alert-danger'
        }[type] || 'alert-info';
        
        alertBox.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} me-2"></i>
                ${escapeHtml(message)}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            </div>
        `;
    }
    
    // ============================================
    // FORMAT FUNCTIONS
    // ============================================
    function formatNumber(num) {
        if (!num && num !== 0) return '0';
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // ============================================
    // DEVICE CRUD FUNCTIONS (Tetap sama seperti sebelumnya)
    // ============================================
    function initializeDeviceModal() {
        const modal = document.getElementById('modalDeviceSuhu');
        const btnShowCreate = document.getElementById('btnShowCreate');
        const btnCancelForm = document.getElementById('btnCancelForm');
        const deviceForm = document.getElementById('deviceForm');
        const tbody = document.getElementById('devicesSuhuTbody');
        
        if (!modal) {
            console.error('Device modal not found!');
            return;
        }
        
        // Modal show event
        modal.addEventListener('show.bs.modal', function() {
            console.log('Device modal opened');
            loadDevices();
            hideDeviceForm();
            clearDeviceAlert();
        });
        
        // Show create form
        if (btnShowCreate) {
            btnShowCreate.addEventListener('click', function() {
                console.log('Create device clicked');
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
                console.log('Device form submitted');
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
                    console.log('Edit device clicked:', deviceId);
                    editDevice(deviceId);
                }
                
                if (deleteBtn) {
                    const deviceId = deleteBtn.dataset.id;
                    console.log('Delete device clicked:', deviceId);
                    deleteDevice(deviceId);
                }
            });
        }
        
        console.log('Device modal initialized');
    }
    
    async function loadDevices() {
        const tbody = document.getElementById('devicesSuhuTbody');
        const totalSpan = document.getElementById('totalDevicesModal');
        
        if (!tbody) {
            console.error('Device table body not found!');
            return;
        }
        
        try {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
            
            const response = await fetch(`${API_BASE}/device?device_type=suhu`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            console.log('Devices response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Devices data received:', data);
            
            const devices = Array.isArray(data) ? data : [];
            
            if (devices.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Tidak ada device</td></tr>';
                if (totalSpan) totalSpan.textContent = '0';
                return;
            }
            
            tbody.innerHTML = devices.map((device, index) => `
                <tr data-id="${device.id}">
                    <td>${index + 1}</td>
                    <td>${escapeHtml(device.device_key)}</td>
                    <td>${escapeHtml(device.device_type)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${device.id}">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${device.id}">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </td>
                </tr>
            `).join('');
            
            if (totalSpan) totalSpan.textContent = devices.length;
            
            console.log('Devices loaded successfully:', devices.length, 'devices');
            
        } catch (error) {
            console.error('Error loading devices:', error);
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Gagal memuat data: ' + error.message + '</td></tr>';
        }
    }
    
    function showDeviceForm(mode = 'create', deviceData = null) {
        const formWrap = document.getElementById('deviceFormWrap');
        const formMode = document.getElementById('deviceFormMode');
        const deviceIdInput = document.getElementById('deviceId');
        const deviceKeyInput = document.getElementById('deviceKey');
        const deviceTypeInput = document.getElementById('deviceType');
        
        if (!formWrap) {
            console.error('Device form wrapper not found!');
            return;
        }
        
        formWrap.style.display = 'block';
        formMode.value = mode;
        
        if (mode === 'create') {
            deviceIdInput.value = '';
            deviceKeyInput.value = '';
            deviceTypeInput.value = 'suhu';
        } else if (mode === 'edit' && deviceData) {
            deviceIdInput.value = deviceData.id;
            deviceKeyInput.value = deviceData.device_key || '';
            deviceTypeInput.value = deviceData.device_type || 'suhu';
        }
        
        deviceKeyInput.focus();
        console.log('Device form shown in mode:', mode);
    }
    
    function hideDeviceForm() {
        const formWrap = document.getElementById('deviceFormWrap');
        const form = document.getElementById('deviceForm');
        
        if (formWrap) formWrap.style.display = 'none';
        if (form) form.reset();
        
        console.log('Device form hidden');
    }
    
    async function saveDevice() {
        const formMode = document.getElementById('deviceFormMode').value;
        const deviceId = document.getElementById('deviceId').value;
        const deviceKey = document.getElementById('deviceKey').value.trim();
        const deviceType = document.getElementById('deviceType').value;
        
        console.log('Saving device - Mode:', formMode, 'ID:', deviceId, 'Key:', deviceKey);
        
        if (!deviceKey) {
            showDeviceAlert('warning', 'Device Key wajib diisi!');
            return;
        }
        
        const payload = {
            device_key: deviceKey,
            device_type: deviceType
        };
        
        let url = `${API_BASE}/device`;
        let method = 'POST';
        let formData = null;
        
        if (formMode === 'edit' && deviceId) {
            url = `${API_BASE}/device/${deviceId}`;
            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('device_key', deviceKey);
            formData.append('device_type', deviceType);
        }
        
        console.log('Sending to URL:', url);
        console.log('Payload:', payload);
        
        try {
            const response = await fetch(url, {
                method: formData ? 'POST' : method,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(formData ? {} : {'Content-Type': 'application/json'})
                },
                body: formData ? formData : JSON.stringify(payload)
            });
            
            console.log('Save response status:', response.status);
            
            const result = await response.json();
            console.log('Save response data:', result);
            
            if (!response.ok) {
                throw new Error(result.message || `HTTP ${response.status}: Gagal menyimpan device`);
            }
            
            // Reload devices
            await loadDevices();
            
            // Hide form
            hideDeviceForm();
            
            // Show success message
            showDeviceAlert('success', formMode === 'create' ? 'Device berhasil ditambahkan!' : 'Device berhasil diupdate!');
            
            // Update dropdown dan dashboard
            loadDropdownData();
            loadDashboardData();
            
            console.log('Device saved successfully');
            
        } catch (error) {
            console.error('Error saving device:', error);
            showDeviceAlert('danger', error.message || 'Gagal menyimpan device');
        }
    }
    
    async function editDevice(deviceId) {
        console.log('Editing device ID:', deviceId);
        
        try {
            const response = await fetch(`${API_BASE}/device/${deviceId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            console.log('Edit response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const result = await response.json();
            console.log('Edit response data:', result);
            
            const device = result.device || result;
            showDeviceForm('edit', device);
            
        } catch (error) {
            console.error('Error loading device:', error);
            showDeviceAlert('danger', 'Gagal memuat data device: ' + error.message);
        }
    }
    
    async function deleteDevice(deviceId) {
        if (!confirm('Apakah Anda yakin ingin menghapus device ini?')) {
            console.log('Delete cancelled by user');
            return;
        }
        
        console.log('Deleting device ID:', deviceId);
        
        try {
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            
            const response = await fetch(`${API_BASE}/device/${deviceId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            console.log('Delete response status:', response.status);
            
            if (!response.ok) {
                const result = await response.json().catch(() => ({}));
                throw new Error(result.message || `HTTP ${response.status}: Gagal menghapus device`);
            }
            
            const result = await response.json();
            console.log('Delete response data:', result);
            
            // Remove from table
            const row = document.querySelector(`#devicesSuhuTbody tr[data-id="${deviceId}"]`);
            if (row) {
                row.remove();
                updateRowNumbers('#devicesSuhuTbody');
                
                // Update total count
                const totalSpan = document.getElementById('totalDevicesModal');
                if (totalSpan) {
                    const currentCount = parseInt(totalSpan.textContent) || 0;
                    totalSpan.textContent = Math.max(0, currentCount - 1);
                }
            }
            
            showDeviceAlert('success', result.message || 'Device berhasil dihapus!');
            
            // Update dropdown dan dashboard
            loadDropdownData();
            loadDashboardData();
            
            console.log('Device deleted successfully');
            
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
    // ROOM CRUD FUNCTIONS (Tetap sama seperti sebelumnya)
    // ============================================
    function initializeRoomModal() {
        const modal = document.getElementById('modalRuangan');
        const btnShowCreateRoom = document.getElementById('btnShowCreateRoom');
        const btnCancelRoomForm = document.getElementById('btnCancelRoomForm');
        const roomForm = document.getElementById('roomForm');
        const tbody = document.getElementById('roomsTbody');
        
        if (!modal) {
            console.error('Room modal not found!');
            return;
        }
        
        // Modal show event
        modal.addEventListener('show.bs.modal', function() {
            console.log('Room modal opened');
            loadRooms();
            hideRoomForm();
            clearRoomAlert();
            loadRoomDropdowns();
        });
        
        // Show create form
        if (btnShowCreateRoom) {
            btnShowCreateRoom.addEventListener('click', function() {
                console.log('Create room clicked');
                showRoomForm('create');
            });
        }
        
        // Cancel form
        if (btnCancelRoomForm) {
            btnCancelRoomForm.addEventListener('click', function() {
                hideRoomForm();
            });
        }
        
        // Form submit
        if (roomForm) {
            roomForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Room form submitted');
                saveRoom();
            });
        }
        
        // Event delegation for edit/delete/reset-wifi buttons
        if (tbody) {
            tbody.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.btn-edit-room');
                const deleteBtn = e.target.closest('.btn-delete-room');
                const resetWifiBtn = e.target.closest('.btn-reset-wifi-room');
                const qrBtn = e.target.closest('.btn-qr-room');
                
                if (editBtn) {
                    const roomId = editBtn.dataset.id;
                    console.log('Edit room clicked:', roomId);
                    editRoom(roomId);
                }
                
                if (deleteBtn) {
                    const roomId = deleteBtn.dataset.id;
                    console.log('Delete room clicked:', roomId);
                    deleteRoom(roomId);
                }
                
                if (resetWifiBtn) {
                    const deviceKey = resetWifiBtn.dataset.deviceKey;
                    console.log('Reset WiFi clicked for device:', deviceKey);
                    resetWifiForDevice(deviceKey);
                }

                if (qrBtn) {
                    const roomId = qrBtn.dataset.roomId;
                    const roomName = qrBtn.dataset.roomName;
                    console.log('QR Code clicked for room:', roomId);
                    showQRCodeModal(roomId, roomName);
                }
            });
        }
        
        console.log('Room modal initialized');
    }
    
    async function resetWifiForDevice(deviceKey) {
        if (!confirm(`Apakah Anda yakin ingin mereset WiFi perangkat ${deviceKey}?\n\nPerangkat akan dipaksa memutus WiFi aktif saat ini dan menyalakan Captive Portal (JoMonitor-ESP32-Config) untuk konfigurasi ulang nirkabel pada siklus pengiriman berikutnya.`)) {
            return;
        }
        
        try {
            showRoomAlert('info', `Mengirim perintah reset WiFi untuk perangkat ${deviceKey}...`);
            
            const response = await fetch(`${API_BASE}/api/devices/${deviceKey}/reset-wifi`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                const result = await response.json();
                throw new Error(result.message || `HTTP ${response.status}: Gagal memicu reset WiFi`);
            }
            
            const result = await response.json();
            showRoomAlert('success', `Perintah Sukses! Kredensial WiFi perangkat ${deviceKey} akan direset secara nirkabel dalam waktu 15 detik pada pengiriman telemetry berikutnya.`);
            
        } catch (error) {
            console.error('Error resetting wifi:', error);
            showRoomAlert('danger', `Gagal mereset WiFi: ${error.message}`);
        }
    }

    function showQRCodeModal(roomId, roomName) {
        // Build the public link URL dynamically using Laravel url helper
        const publicUrl = "{{ url('/public-monitoring') }}/" + roomId;
        
        // Generate QR code using QRServer API
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(publicUrl)}`;
        
        document.getElementById('qrRoomName').textContent = roomName;
        document.getElementById('qrCodeImage').src = qrApiUrl;
        document.getElementById('btnOpenPublicLink').href = publicUrl;
        
        // Printable handler
        document.getElementById('btnPrintQR').onclick = function() {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print QR Code - ${roomName}</title>
                    <style>
                        body {
                            font-family: 'Outfit', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            text-align: center;
                            padding: 40px;
                            color: #1e293b;
                        }
                        .container {
                            border: 3px dashed #cbd5e1;
                            padding: 40px;
                            border-radius: 24px;
                            max-width: 400px;
                            margin: 0 auto;
                            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
                        }
                        h2 {
                            margin: 0 0 5px 0;
                            color: #0f172a;
                            font-size: 24px;
                            font-weight: 700;
                        }
                        p {
                            color: #64748b;
                            font-size: 14px;
                            margin-bottom: 20px;
                            line-height: 1.5;
                        }
                        .qr-box {
                            background: white;
                            padding: 20px;
                            display: inline-block;
                            border: 1px solid #e2e8f0;
                            border-radius: 16px;
                            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
                        }
                        img {
                            display: block;
                            width: 230px;
                            height: 230px;
                        }
                        .credentials-box {
                            margin-top: 20px;
                            font-size: 13px;
                            color: #475569;
                            background: #f8fafc;
                            border: 1px solid #e2e8f0;
                            padding: 12px;
                            border-radius: 12px;
                            text-align: left;
                        }
                        .footer {
                            margin-top: 30px;
                            font-size: 11px;
                            color: #94a3b8;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 0.05em;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>📡 JoMonitor</h2>
                        <p>Pindai untuk memantau & mengontrol langsung kondisi suhu & kelembapan ruangan:<br><strong style="color:#0f172a; font-size:16px;">${roomName}</strong></p>
                        <div class="qr-box">
                            <img src="${qrApiUrl}" />
                        </div>
                        <div class="credentials-box">
                            🔑 <strong>ID Ruang:</strong> ${roomId}<br>
                            🔒 <strong>Default Password Control:</strong> ${roomId}
                        </div>
                        <div class="footer">TI UMPO X IT RSPM &copy; 2025 - 2026</div>
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            window.onafterprint = function() { window.close(); };
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        };

        const modal = new bootstrap.Modal(document.getElementById('modalQRCode'));
        modal.show();
    }
    
    async function loadRooms() {
        const tbody = document.getElementById('roomsTbody');
        const totalSpan = document.getElementById('totalRooms');
        
        if (!tbody) {
            console.error('Room table body not found!');
            return;
        }
        
        try {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>';
            
            console.log('Loading rooms from:', `${API_BASE}/rooms`);
            
            const response = await fetch(`${API_BASE}/rooms`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            console.log('Rooms response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const result = await response.json();
            console.log('Rooms response data:', result);
            
            const rooms = (result.ok && result.data) ? result.data : [];
            
            if (rooms.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-door-closed text-muted fa-2x mb-3"></i>
                            <div class="text-muted">Belum ada ruangan</div>
                            <small class="text-muted mt-2">Klik "Tambah Ruangan" untuk menambahkan</small>
                        </td>
                    </tr>
                `;
                if (totalSpan) totalSpan.textContent = '0';
                return;
            }
            
            tbody.innerHTML = rooms.map((room, index) => {
                let statusClass = 'bg-secondary';
                let statusText = 'Nonaktif';
                
                if (room.status === 'active') {
                    statusClass = 'bg-success';
                    statusText = 'Aktif';
                } else if (room.status === 'warning') {
                    statusClass = 'bg-warning text-dark';
                    statusText = 'Warning';
                } else if (room.status === 'inactive') {
                    statusClass = 'bg-secondary';
                    statusText = 'Nonaktif';
                }
                
                const temperature = room.current_temperature ? 
                    `${parseFloat(room.current_temperature).toFixed(1)}°C` : '-';
                const humidity = room.current_humidity ? 
                    `${parseFloat(room.current_humidity).toFixed(1)}%` : '-';
                
                return `
                    <tr data-id="${room.id}">
                        <td>${index + 1}</td>
                        <td><strong class="text-primary">${escapeHtml(room.room_id)}</strong></td>
                        <td>
                            <div class="fw-medium">${escapeHtml(room.room_name)}</div>
                            <small class="text-muted">ID: ${room.id}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-microchip text-info me-2"></i>
                                <div>
                                    <div>${escapeHtml(room.device_id || '-')}</div>
                                    <small class="text-muted">${escapeHtml(room.device_name || '')}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-thermometer-half me-1"></i>
                                ${temperature}
                            </span>
                            <div class="mt-1 text-muted" style="font-size: 0.72rem;">
                                <i class="fas fa-bell me-1" style="font-size: 0.65rem;"></i>${parseFloat(room.temp_min || 15.0).toFixed(1)}-${parseFloat(room.temp_max || 28.0).toFixed(1)}°C
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-tint me-1"></i>
                                ${humidity}
                            </span>
                            <div class="mt-1 text-muted" style="font-size: 0.72rem;">
                                <i class="fas fa-bell me-1" style="font-size: 0.65rem;"></i>${parseFloat(room.hum_min || 30.0).toFixed(1)}-${parseFloat(room.hum_max || 60.0).toFixed(1)}%
                            </div>
                        </td>
                        <td>
                            <span class="badge ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-success btn-qr-room" data-room-id="${escapeHtml(room.room_id)}" data-room-name="${escapeHtml(room.room_name)}" title="Cetak / Tampilkan QR Code Monitoring Publik">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                                ${room.device_id ? `
                                <button class="btn btn-outline-warning btn-reset-wifi-room" data-device-key="${escapeHtml(room.device_id)}" title="Reset WiFi & Konfigurasi Ulang Alat">
                                    <i class="fas fa-wifi"></i>
                                </button>
                                ` : ''}
                                <button class="btn btn-outline-primary btn-edit-room" data-id="${room.id}" title="Edit Ruangan">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-delete-room" data-id="${room.id}" title="Hapus Ruangan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            if (totalSpan) totalSpan.textContent = rooms.length;
            
            console.log('Rooms loaded successfully:', rooms.length, 'rooms');
            
        } catch (error) {
            console.error('Error loading rooms:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Gagal memuat data: ${escapeHtml(error.message)}
                    </td>
                </tr>
            `;
        }
    }
    
    async function loadRoomDropdowns() {
        console.log('=== LOAD ROOM DROPDOWNS ===');
        
        try {
            // Load rooms list untuk dropdown ID Ruang
            const roomsResponse = await fetch(`${API_BASE}/rooms/list`);
            console.log('Rooms Response Status:', roomsResponse.status);
            
            let roomsResult;
            if (roomsResponse.ok) {
                roomsResult = await roomsResponse.json();
                console.log('Rooms Result:', roomsResult);
            } else {
                console.error('Rooms list failed:', roomsResponse.status);
                roomsResult = {
                    ok: true,
                    rows: [
                        { id_ruang: "FAR", nama_ruang: "Farmasi" },
                        { id_ruang: "ICU", nama_ruang: "ICU" },
                        { id_ruang: "SVR", nama_ruang: "Server" },
                        { id_ruang: "DIREK", nama_ruang: "Ruang Direksi" }
                    ]
                };
            }
            
            const roomIdSelect = document.getElementById('roomIdSelect');
            if (roomIdSelect) {
                roomIdSelect.innerHTML = '<option value="">Pilih ID Ruang</option>';
                
                if (roomsResult.ok && roomsResult.rows) {
                    roomsResult.rows.forEach(room => {
                        if (room.id_ruang && room.nama_ruang) {
                            const option = document.createElement('option');
                            option.value = room.id_ruang;
                            option.textContent = `${room.id_ruang} - ${room.nama_ruang}`;
                            option.dataset.nama = room.nama_ruang;
                            roomIdSelect.appendChild(option);
                        }
                    });
                }
                
                console.log('Room dropdown loaded:', roomIdSelect.options.length, 'options');
            }
            
            // Load devices list untuk dropdown Device
            const devicesResponse = await fetch(`${API_BASE}/device?device_type=suhu`);
            console.log('Devices Response Status:', devicesResponse.status);
            
            let devicesData = [];
            if (devicesResponse.ok) {
                devicesData = await devicesResponse.json();
                console.log('Devices Data:', devicesData);
            } else {
                console.error('Devices list failed:', devicesResponse.status);
                devicesData = [
                    { device_key: 'nodemcu1', name: 'nodemcu1' },
                    { device_key: 'wemos1', name: 'wemos1' }
                ];
            }
            
            const deviceSelect = document.getElementById('roomDeviceSelect');
            if (deviceSelect) {
                deviceSelect.innerHTML = '<option value="">Pilih Device</option>';
                
                const devices = Array.isArray(devicesData) ? devicesData : [];
                devices.forEach(device => {
                    const deviceKey = device.device_key || device.key || device.id;
                    const deviceName = device.name || deviceKey;
                    
                    if (deviceKey) {
                        const option = document.createElement('option');
                        option.value = deviceKey;
                        option.textContent = deviceName;
                        deviceSelect.appendChild(option);
                    }
                });
                
                console.log('Device dropdown loaded:', deviceSelect.options.length, 'options');
            }
            
        } catch (error) {
            console.error('Error loading room dropdowns:', error);
            
            // Fallback minimal
            const roomIdSelect = document.getElementById('roomIdSelect');
            const deviceSelect = document.getElementById('roomDeviceSelect');
            
            if (roomIdSelect) {
                roomIdSelect.innerHTML = `
                    <option value="">Pilih ID Ruang</option>
                    <option value="DIREK" data-nama="Ruang Direksi">DIREK - Ruang Direksi</option>
                    <option value="SVR" data-nama="Server">SVR - Server</option>
                `;
            }
            
            if (deviceSelect) {
                deviceSelect.innerHTML = `
                    <option value="">Pilih Device</option>
                    <option value="nodemcu1">nodemcu1</option>
                    <option value="wemos1">wemos1</option>
                `;
            }
        }
        
        console.log('=== END LOAD ROOM DROPDOWNS ===');
    }
    
    function showRoomForm(mode = 'create', roomData = null) {
        const formWrap = document.getElementById('roomFormWrap');
        const formMode = document.getElementById('roomFormMode');
        const roomIdInput = document.getElementById('roomId');
        const roomIdSelect = document.getElementById('roomIdSelect');
        const deviceSelect = document.getElementById('roomDeviceSelect');
        
        if (!formWrap) {
            console.error('Room form wrapper not found!');
            return;
        }
        
        formWrap.style.display = 'block';
        formMode.value = mode;
        
        if (mode === 'create') {
            roomIdInput.value = '';
            if (roomIdSelect) roomIdSelect.value = '';
            if (deviceSelect) deviceSelect.value = '';
            const statusSelect = document.getElementById('roomStatusSelect');
            if (statusSelect) statusSelect.value = 'active';
            
            // Reset thresholds
            const tempMinInput = document.getElementById('roomTempMin');
            const tempMaxInput = document.getElementById('roomTempMax');
            const humMinInput = document.getElementById('roomHumMin');
            const humMaxInput = document.getElementById('roomHumMax');
            if (tempMinInput) tempMinInput.value = '15.0';
            if (tempMaxInput) tempMaxInput.value = '28.0';
            if (humMinInput) humMinInput.value = '30.0';
            if (humMaxInput) humMaxInput.value = '60.0';
        } else if (mode === 'edit' && roomData) {
            roomIdInput.value = roomData.id;
            if (roomIdSelect) {
                // Periksa apakah opsi dengan nilai roomData.room_id sudah ada
                let optionExists = false;
                for (let i = 0; i < roomIdSelect.options.length; i++) {
                    if (roomIdSelect.options[i].value === roomData.room_id) {
                        optionExists = true;
                        break;
                    }
                }
                
                // Jika belum ada (misalnya ID ruang kustom seperti FAR1), tambahkan secara dinamis
                if (!optionExists && roomData.room_id) {
                    const customOpt = document.createElement('option');
                    customOpt.value = roomData.room_id;
                    customOpt.textContent = `${roomData.room_id} - ${roomData.room_name || roomData.room_id}`;
                    customOpt.dataset.nama = roomData.room_name || roomData.room_id;
                    roomIdSelect.appendChild(customOpt);
                }
                
                roomIdSelect.value = roomData.room_id || '';
            }
            if (deviceSelect) deviceSelect.value = roomData.device_id || '';
            const statusSelect = document.getElementById('roomStatusSelect');
            if (statusSelect) {
                if (roomData.status === 'warning') {
                    statusSelect.value = 'active';
                } else {
                    statusSelect.value = roomData.status || 'active';
                }
            }
            
            // Populate thresholds
            const tempMinInput = document.getElementById('roomTempMin');
            const tempMaxInput = document.getElementById('roomTempMax');
            const humMinInput = document.getElementById('roomHumMin');
            const humMaxInput = document.getElementById('roomHumMax');
            if (tempMinInput) tempMinInput.value = roomData.temp_min !== null && roomData.temp_min !== undefined ? roomData.temp_min : '15.0';
            if (tempMaxInput) tempMaxInput.value = roomData.temp_max !== null && roomData.temp_max !== undefined ? roomData.temp_max : '28.0';
            if (humMinInput) humMinInput.value = roomData.hum_min !== null && roomData.hum_min !== undefined ? roomData.hum_min : '30.0';
            if (humMaxInput) humMaxInput.value = roomData.hum_max !== null && roomData.hum_max !== undefined ? roomData.hum_max : '60.0';
        }
        
        console.log('Room form shown in mode:', mode);
    }
    
    function hideRoomForm() {
        const formWrap = document.getElementById('roomFormWrap');
        const form = document.getElementById('roomForm');
        
        if (formWrap) formWrap.style.display = 'none';
        if (form) form.reset();
        
        console.log('Room form hidden');
    }
    
    async function saveRoom() {
        console.log('=== SAVE ROOM FUNCTION ===');
        
        const formMode = document.getElementById('roomFormMode').value;
        const roomId = document.getElementById('roomId').value;
        const roomIdSelect = document.getElementById('roomIdSelect');
        const deviceSelect = document.getElementById('roomDeviceSelect');
        
        if (!roomIdSelect?.value) {
            showRoomAlert('warning', 'ID Ruang wajib dipilih!');
            return;
        }
        
        if (!deviceSelect?.value) {
            showRoomAlert('warning', 'Device wajib dipilih!');
            return;
        }
        
        // Ambil nama ruangan dari dropdown
        const selectedRoomOption = roomIdSelect?.options[roomIdSelect.selectedIndex];
        let roomName = '';
        
        if (selectedRoomOption && selectedRoomOption.dataset.nama) {
            roomName = selectedRoomOption.dataset.nama;
        } else if (selectedRoomOption) {
            const text = selectedRoomOption.textContent;
            const parts = text.split(' - ');
            if (parts.length > 1) {
                roomName = parts[1];
            } else {
                roomName = text;
            }
        }
        
        // Prepare payload
        const statusSelect = document.getElementById('roomStatusSelect');
        const status = statusSelect ? statusSelect.value : 'active';
        
        const tempMin = document.getElementById('roomTempMin')?.value || '15.0';
        const tempMax = document.getElementById('roomTempMax')?.value || '28.0';
        const humMin = document.getElementById('roomHumMin')?.value || '30.0';
        const humMax = document.getElementById('roomHumMax')?.value || '60.0';
        
        const payload = {
            room_id: roomIdSelect.value,
            room_name: roomName,
            device_id: deviceSelect.value,
            status: status,
            temp_min: tempMin,
            temp_max: tempMax,
            hum_min: humMin,
            hum_max: humMax
        };
        
        console.log('Payload:', payload);
        
        let url = `${API_BASE}/rooms`;
        let method = 'POST';
        
        if (formMode === 'edit' && roomId) {
            url = `${API_BASE}/rooms/${roomId}`;
            method = 'PUT';
        }
        
        try {
            const response = await fetch(url, {
                method: method === 'PUT' ? 'POST' : 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(method === 'POST' ? {'Content-Type': 'application/json'} : {})
                },
                body: method === 'PUT' ? 
                    (() => {
                        const formData = new FormData();
                        formData.append('_method', 'PUT');
                        formData.append('room_id', payload.room_id);
                        formData.append('room_name', payload.room_name);
                        formData.append('device_id', payload.device_id);
                        formData.append('status', payload.status);
                        formData.append('temp_min', payload.temp_min);
                        formData.append('temp_max', payload.temp_max);
                        formData.append('hum_min', payload.hum_min);
                        formData.append('hum_max', payload.hum_max);
                        return formData;
                    })() : 
                    JSON.stringify(payload)
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.error || result.message || 'Gagal menyimpan ruangan');
            }
            
            // SUCCESS
            await loadRooms();
            hideRoomForm();
            
            // Update dropdown dan dashboard
            loadDropdownData();
            loadDashboardData();
            
            showRoomAlert('success', result.message || 'Ruangan berhasil disimpan!');
            
        } catch (error) {
            console.error('Error saving room:', error);
            showRoomAlert('danger', error.message || 'Gagal menyimpan ruangan');
        }
        
        console.log('=== END SAVE ROOM ===');
    }
    
    async function editRoom(roomId) {
        try {
            const response = await fetch(`${API_BASE}/rooms/${roomId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const result = await response.json();
            const room = result.ok ? result.data : result;
            
            showRoomForm('edit', room);
            
        } catch (error) {
            console.error('Error loading room:', error);
            showRoomAlert('danger', 'Gagal memuat data ruangan');
        }
    }
    
    async function deleteRoom(roomId) {
        if (!confirm('Apakah Anda yakin ingin menghapus ruangan ini?')) {
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            
            const response = await fetch(`${API_BASE}/rooms/${roomId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            if (!response.ok) {
                const result = await response.json();
                throw new Error(result.message || `HTTP ${response.status}: Gagal menghapus ruangan`);
            }
            
            const result = await response.json();
            
            // Remove from table
            const row = document.querySelector(`#roomsTbody tr[data-id="${roomId}"]`);
            if (row) {
                row.remove();
                updateRowNumbers('#roomsTbody');
                
                // Update total count
                const totalSpan = document.getElementById('totalRooms');
                if (totalSpan) {
                    const currentCount = parseInt(totalSpan.textContent) || 0;
                    totalSpan.textContent = Math.max(0, currentCount - 1);
                }
            }
            
            showRoomAlert('success', result.message || 'Ruangan berhasil dihapus!');
            
            // Update dashboard
            loadDashboardData();
            
        } catch (error) {
            console.error('Error deleting room:', error);
            showRoomAlert('danger', error.message || 'Gagal menghapus ruangan');
        }
    }
    
    function showRoomAlert(type, message) {
        const alertBox = document.getElementById('roomsAlert');
        if (!alertBox) return;
        
        alertBox.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${escapeHtml(message)}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            </div>
        `;
    }
    
    function clearRoomAlert() {
        const alertBox = document.getElementById('roomsAlert');
        if (alertBox) alertBox.innerHTML = '';
    }
    
    // ============================================
    // PAGINATION FUNCTIONS
    // ============================================
    function updatePagination(data) {
        currentPage = data.current_page || 1;
        totalPages = data.last_page || 1;
        totalItems = data.total || 0;
        perPage = data.per_page || 10;
        
        updatePaginationInfo();
        updatePaginationButtons();
    }
    
    function updatePaginationInfo() {
        const infoElement = document.getElementById('paginationInfo');
        if (!infoElement) return;
        
        const startItem = (currentPage - 1) * perPage + 1;
        const endItem = Math.min(currentPage * perPage, totalItems);
        
        infoElement.textContent = 
            `Menampilkan ${formatNumber(startItem)}-${formatNumber(endItem)} dari ${formatNumber(totalItems)} data (Halaman ${currentPage}/${totalPages})`;
    }
    
    function updatePaginationButtons() {
        const controlsElement = document.getElementById('paginationControls');
        if (!controlsElement) return;
        
        controlsElement.innerHTML = '';
        
        // Previous button
        const prevButton = document.createElement('button');
        prevButton.className = 'pagination-prev';
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.disabled = currentPage === 1;
        prevButton.onclick = () => currentPage > 1 && loadMonitoringData(currentPage - 1);
        controlsElement.appendChild(prevButton);
        
        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.className = i === currentPage ? 'active' : '';
            pageButton.onclick = () => loadMonitoringData(i);
            controlsElement.appendChild(pageButton);
        }
        
        // Next button
        const nextButton = document.createElement('button');
        nextButton.className = 'pagination-next';
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.disabled = currentPage === totalPages;
        nextButton.onclick = () => currentPage < totalPages && loadMonitoringData(currentPage + 1);
        controlsElement.appendChild(nextButton);
    }
    
    // ============================================
    // FILTER FUNCTIONS
    // ============================================
    function resetFilters() {
        document.getElementById('filterDevice').value = 'Semua';
        document.getElementById('filterRuangan').value = 'Semua';
        document.getElementById('filterStatus').value = 'Semua';
        document.getElementById('filterKelembaban').value = 'Semua';
        document.getElementById('filterDariTanggal').value = '';
        document.getElementById('filterDariJam').value = '';
        document.getElementById('filterSampaiTanggal').value = '';
        document.getElementById('filterSampaiJam').value = '';
        document.getElementById('searchInput').value = '';
        
        loadMonitoringData(1);
        showAlert('info', 'Filter telah direset');
    }
    
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');
        
        let visibleCount = 0;
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        
        // Update pagination info
        const infoElement = document.getElementById('paginationInfo');
        if (infoElement && searchTerm) {
            infoElement.textContent = `Menampilkan ${formatNumber(visibleCount)} data (filter: "${searchTerm}")`;
        }
    }
    
    function setDateRange(rangeType) {
        const now = new Date();
        let startDate, endDate;
        
        switch(rangeType) {
            case 'today':
                startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                endDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
                break;
            case 'yesterday':
                startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
                endDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1, 23, 59, 59);
                break;
            case 'week':
                startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7);
                endDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
                break;
            case 'month':
                startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                endDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
                break;
            case 'last_month':
                startDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                endDate = new Date(now.getFullYear(), now.getMonth(), 0, 23, 59, 59);
                break;
            default:
                return;
        }
        
        // Format date for input (dd/mm/yyyy)
        const formatDate = (date) => {
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        };
        
        // Format time for input (HH:mm)
        const formatTime = (date) => {
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        };
        
        document.getElementById('filterDariTanggal').value = formatDate(startDate);
        document.getElementById('filterDariJam').value = formatTime(startDate);
        document.getElementById('filterSampaiTanggal').value = formatDate(endDate);
        document.getElementById('filterSampaiJam').value = formatTime(endDate);
        
        // Auto load data after 500ms
        setTimeout(() => {
            updateTotalData();
            loadMonitoringData(1);
        }, 500);
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
    
    function updateRowNumbers(selector) {
        const rows = document.querySelectorAll(`${selector} tr`);
        rows.forEach((row, index) => {
            const firstCell = row.querySelector('td:first-child');
            if (firstCell) {
                firstCell.textContent = index + 1;
            }
        });
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
            if (alertElement) {
                alertElement.remove();
            }
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
    
    function refreshAllData() {
        showLoading();
        
        Promise.all([
            loadDashboardData(),
            loadMonitoringData(currentPage),
            loadDropdownData()
        ]).then(() => {
            showAlert('success', 'Data berhasil diperbarui!');
        }).catch(error => {
            showAlert('error', 'Gagal memperbarui data: ' + error.message);
        }).finally(() => {
            hideLoading();
        });
    }
    
    // ============================================
    // AUTO REFRESH FUNCTIONS
    // ============================================
    function startAutoRefresh() {
        // Auto-refresh dashboard setiap 30 detik
        setInterval(() => {
            refreshDashboard();
        }, 30000);
    }
    
    async function refreshDashboard() {
        try {
            // Silent refresh untuk dashboard saja
            await loadDashboardData();
            
            // Refresh monitoring table jika sedang di halaman 1
            if (currentPage === 1) {
                await loadMonitoringData(1);
            }
            
            console.log('Dashboard refreshed:', new Date().toLocaleTimeString());
        } catch (error) {
            console.error('Auto-refresh error:', error);
        }
    }
    
    // ============================================
    // DATE PICKER INITIALIZATION
    // ============================================
    function initializeDatePickers() {
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".date-picker", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: true
            });
            
            flatpickr(".time-input", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        }
    }
    
    // ============================================
    // EVENT LISTENERS INITIALIZATION
    // ============================================
    function initializeEventListeners() {
        // Initialize device modal
        initializeDeviceModal();
        
        // Initialize room modal
        initializeRoomModal();
        
        // Filter dropdown change events - update total data juga
        const filterElements = [
            'filterDevice',
            'filterRuangan', 
            'filterStatus',
            'filterKelembaban',
            'filterDariTanggal',
            'filterDariJam',
            'filterSampaiTanggal',
            'filterSampaiJam'
        ];
        
        filterElements.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', () => {
                    // Update total data saat filter berubah
                    setTimeout(() => {
                        updateTotalData();
                        loadMonitoringData(1);
                    }, 300);
                });
            }
        });
        
        // Quick filter buttons
        document.querySelectorAll('.quick-filter-buttons button').forEach(button => {
            button.addEventListener('click', function() {
                const rangeType = this.getAttribute('onclick')?.match(/setDateRange\('(.+)'\)/)?.[1];
                if (rangeType) {
                    setDateRange(rangeType);
                }
            });
        });
        
        // Apply filter button
        const applyBtn = document.querySelector('.btn-apply');
        if (applyBtn) {
            applyBtn.addEventListener('click', () => loadMonitoringData(1));
        }
        
        // Reset filter button
        const resetBtn = document.querySelector('.btn-reset');
        if (resetBtn) {
            resetBtn.addEventListener('click', resetFilters);
        }
        
        // Search input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', filterTable);
        }
        
        // Date input changes
        ['filterDariTanggal', 'filterDariJam', 'filterSampaiTanggal', 'filterSampaiJam'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', () => {
                setTimeout(() => {
                    updateTotalData();
                    loadMonitoringData(1);
                }, 1000);
            });
        });
    }
    
    // ============================================
    // EXPORT FUNCTIONS TO GLOBAL SCOPE
    // ============================================
    window.refreshAllData = refreshAllData;
    window.showExportModal = showExportModal;
    window.resetFilters = resetFilters;
    window.setDateRange = setDateRange;
    window.loadMonitoringData = loadMonitoringData;
    window.filterTable = filterTable;
</script>
@endsection