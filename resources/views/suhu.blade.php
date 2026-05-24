@extends('layout.app')

@section('judul', 'Monitoring Suhu')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
    .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: none; position: relative; overflow: hidden; }
    .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--gradient); }
    .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .card-title { font-size: 0.95rem; font-weight: 500; color: #6c757d; margin: 0; }
    .card-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: var(--gradient-light); color: #2E8B57; }
    .card-value { font-size: 2.2rem; font-weight: 700; background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .data-section { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; position: relative; overflow: hidden; }
    .data-section::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--gradient); }
    .section-header { padding: 25px 25px 0 25px; }
    .section-title { font-size: 1.3rem; font-weight: 600; color: #2c3e50; margin: 0 0 5px 0; }
    .section-subtitle { font-size: 0.9rem; color: #6c757d; margin: 0; }
    .filter-wrapper { padding: 0 25px 25px 25px; border-bottom: 1px solid #f0f0f0; }
    .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px; }
    .filter-group { margin-bottom: 0; }
    .filter-label { font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #2c3e50; display: block; }
    .filter-select, .filter-input { width: 100%; padding: 8px 12px; border: 1px solid #e0e0e0; border-radius: 6px; background: #f8f9fa; font-size: 13px; }
    .date-range-group { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
    .quick-filters { margin-top: 15px; }
    .quick-filters-label { font-size: 12px; font-weight: 600; margin-bottom: 8px; color: #2c3e50; display: block; }
    .quick-filter-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-apply { background: var(--gradient); border: none; color: white; padding: 8px 20px; border-radius: 6px; font-weight: 500; cursor: pointer; transition: all 0.3s ease; font-size: 13px; display: flex; align-items: center; gap: 5px; }
    .btn-reset { background: #f8f9fa; border: 1px solid #e0e0e0; color: #6c757d; padding: 8px 16px; border-radius: 6px; font-weight: 500; cursor: pointer; transition: all 0.3s ease; font-size: 13px; display: flex; align-items: center; gap: 5px; }
    .filter-actions { display: flex; gap: 10px; margin-top: 20px; }
    .table-wrapper { padding: 0 25px; }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
    .search-box { position: relative; flex: 1; max-width: 300px; }
    .search-box input { background: #f8f9fa; border: 1px solid #dee2e6; padding: 10px 15px 10px 40px; border-radius: 8px; width: 100%; transition: all 0.3s; }
    .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #7f8c8d; }
    .table-actions button { background: var(--gradient-light); border: 1px solid #dee2e6; color: #2E8B57; padding: 8px 15px; border-radius: 6px; cursor: pointer; transition: all 0.3s; }
    .table-content { padding: 0 25px 25px 25px; }
    .table-responsive { border-radius: 8px; overflow: hidden; border: 1px solid #f0f0f0; }
    .table { margin-bottom: 0; width: 100%; }
    .table thead th { background: #f8f9fa; border-bottom: 2px solid #dee2e6; font-weight: 600; font-size: 13px; color: #2c3e50; padding: 12px 16px; white-space: nowrap; }
    .table tbody td { padding: 12px 16px; font-size: 13px; vertical-align: middle; border-top: 1px solid #f0f0f0; }
    .timestamp { font-family: 'Courier New', monospace; font-size: 12px; color: #7f8c8d; }
    .pagination-container { padding: 20px 25px; border-top: 1px solid #f0f0f0; background: #fafafa; }
    .pagination { display: flex; justify-content: space-between; align-items: center; }
    .pagination-info { color: #6c757d; font-size: 12px; }
    .pagination-controls { display: flex; gap: 5px; }
    .pagination-controls button { padding: 6px 12px; border: 1px solid #dee2e6; background: white; border-radius: 6px; font-size: 12px; transition: all 0.3s ease; min-width: 32px; height: 32px; cursor: pointer; }
    .pagination-controls button.active { background: var(--gradient); color: white; border-color: transparent; }
    .pagination-controls button:hover:not(.active) { background: #f8f9fa; border-color: #ced4da; }
    .status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
    .status-normal { background: #d4edda; color: #155724; }
    .status-warning { background: #fff3cd; color: #856404; }
    .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
    .spinner { width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .alert-container { position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 400px; }
    .alert { margin-bottom: 10px; animation: slideIn 0.3s ease-out; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @media (max-width: 1200px) { .filter-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .filter-grid { grid-template-columns: 1fr; } .dashboard-grid { grid-template-columns: 1fr; } .date-range-group { grid-template-columns: 1fr; } .table-header { flex-direction: column; align-items: flex-start; } .search-box { max-width: 100%; } .pagination { flex-direction: column; gap: 15px; text-align: center; } }
</style>
@endsection

@section('Content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <div>
        <h1 class="h3 fw-bold text-dark">Monitoring Suhu Ruangan</h1>
        <p class="text-muted">Sistem monitoring real-time suhu ruangan dan kelembaban</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshAllData()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showExportModal()">
                <i class="fas fa-download me-1"></i> Ekspor
            </button>
        </div>
    </div>
</div>

<div class="dashboard-grid mb-4">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Device Terpantau</div>
            <div class="card-icon"><i class="fas fa-microchip"></i></div>
        </div>
        <div class="card-value" id="totalDevices">0</div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Status Normal</div>
            <div class="card-icon"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="card-value" id="normalDevices">0</div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Status Warning</div>
            <div class="card-icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
        <div class="card-value" id="warningDevices">0</div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Total Data</div>
            <div class="card-icon"><i class="fas fa-database"></i></div>
        </div>
        <div class="card-value" id="totalData">0</div>
    </div>
</div>

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
            <tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Memuat data...</td></tr>
        </tbody>
    </table>
</div>

<div class="data-section">
    <div class="section-header">
        <h3 class="section-title">Riwayat Log Suhu</h3>
        <p class="section-subtitle">Monitoring dan filter data suhu ruangan secara real-time</p>
    </div>
    
    <div class="filter-wrapper">
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">Device</label>
                <select class="filter-select" id="filterDevice">
                    <option value="Semua">Semua Device</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Ruangan</label>
                <select class="filter-select" id="filterRuangan">
                    <option value="Semua">Semua Ruangan</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Status Suhu</label>
                <select class="filter-select" id="filterStatus">
                    <option value="Semua">Semua Status</option>
                    <option value="Normal">Normal</option>
                    <option value="Warning">Warning</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Status Kelembaban</label>
                <select class="filter-select" id="filterKelembaban">
                    <option value="Semua">Semua Status</option>
                    <option value="Normal">Normal</option>
                    <option value="Warning">Warning</option>
                </select>
            </div>
        </div>
        
        <div class="date-range-group">
            <div class="filter-group">
                <label class="filter-label">Dari Tanggal</label>
                <input type="text" class="filter-input date-picker" id="filterDariTanggal" placeholder="dd/mm/yyyy">
            </div>
            <div class="filter-group">
                <label class="filter-label">Sampai Tanggal</label>
                <input type="text" class="filter-input date-picker" id="filterSampaiTanggal" placeholder="dd/mm/yyyy">
            </div>
        </div>
        
        <div class="quick-filters">
            <label class="quick-filters-label">Filter Cepat:</label>
            <div class="quick-filter-buttons">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDateRange('today')">Hari Ini</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDateRange('week')">7 Hari Terakhir</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDateRange('month')">Bulan Ini</button>
            </div>
        </div>
        
        <div class="filter-actions">
            <button class="btn-reset" onclick="resetFilters()"><i class="fas fa-redo"></i> Reset Filter</button>
            <button class="btn-apply" onclick="loadMonitoringData(1)"><i class="fas fa-filter"></i> Terapkan Filter</button>
        </div>
    </div>
    
    <div class="table-wrapper">
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari data monitoring..." onkeyup="filterTable()">
            </div>
            <div class="table-actions">
                <button onclick="loadMonitoringData(1)"><i class="fas fa-sync-alt"></i> Refresh Table</button>
            </div>
        </div>
        
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
                        <tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="pagination-container">
            <div class="pagination">
                <div class="pagination-info" id="paginationInfo">Menampilkan 0-0 dari 0 data</div>
                <div class="pagination-controls" id="paginationControls"></div>
            </div>
        </div>
    </div>
</div>

<div id="loadingOverlay" class="loading-overlay"><div class="spinner"></div></div>
<div id="alertContainer" class="alert-container"></div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const API_BASE = '{{ url("/") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let currentPage = 1, totalPages = 1, totalItems = 0, perPage = 10;

    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".date-picker", { dateFormat: "d/m/Y", locale: "id" });
        loadDashboardData();
        loadMonitoringData(1);
        loadDeviceStatus();
        loadFilters();
        setInterval(() => { loadDashboardData(); loadDeviceStatus(); }, 30000);
    });

    async function loadDashboardData() {
        try {
            const response = await fetch(`${API_BASE}/monitoring/suhu/dashboard?device=Semua&ruangan=Semua`);
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    document.getElementById('totalDevices').textContent = result.data.total_devices || 0;
                    document.getElementById('normalDevices').textContent = result.data.normal_devices || 0;
                    document.getElementById('warningDevices').textContent = result.data.warning_devices || 0;
                    document.getElementById('totalData').textContent = result.data.total_data || 0;
                }
            }
        } catch (error) { console.error('Error loading dashboard:', error); }
    }

    async function loadDeviceStatus() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/temperature-devices-status`);
            if (response.ok) {
                const result = await response.json();
                if (result.success) updateDeviceStatusTable(result.data || []);
            }
        } catch (error) { console.error('Error loading device status:', error); }
    }

    async function loadFilters() {
        try {
            const devicesResponse = await fetch(`${API_BASE}/monitoring/suhu/devices-filter`);
            if (devicesResponse.ok) {
                const result = await devicesResponse.json();
                if (result.success && result.devices) {
                    const deviceSelect = document.getElementById('filterDevice');
                    result.devices.forEach(device => {
                        const option = document.createElement('option');
                        option.value = device.device_key;
                        option.textContent = device.name || device.device_key;
                        deviceSelect.appendChild(option);
                    });
                }
            }
            const roomsResponse = await fetch(`${API_BASE}/monitoring/suhu/rooms-filter`);
            if (roomsResponse.ok) {
                const result = await roomsResponse.json();
                if (result.success && result.rooms) {
                    const roomSelect = document.getElementById('filterRuangan');
                    result.rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.room_id;
                        option.textContent = room.room_name;
                        roomSelect.appendChild(option);
                    });
                }
            }
        } catch (error) { console.error('Error loading filters:', error); }
    }

    function updateDeviceStatusTable(devices) {
        const tbody = document.getElementById('deviceStatusBody');
        if (!devices || devices.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada device aktif</td></tr>';
            return;
        }
        tbody.innerHTML = devices.map(device => `
            <tr>
                <td><div class="fw-bold">${escapeHtml(device.device_name)}</div><small class="text-muted">${escapeHtml(device.device_key)}</small></td>
                <td><code>${escapeHtml(device.room_id)}</code></td>
                <td>${escapeHtml(device.room_name)}</td>
                <td class="${device.temperature_status === 'Warning' ? 'text-warning fw-bold' : 'text-success'}">${device.temperature}</td>
                <td class="${device.humidity_status === 'Warning' ? 'text-warning fw-bold' : 'text-success'}">${device.humidity}</td>
                <td><span class="status-badge ${device.status === 'Normal' ? 'status-normal' : 'status-warning'}">${escapeHtml(device.status_display)}</span></td>
                <td class="timestamp">${escapeHtml(device.time_ago)}<br><small class="text-muted">${escapeHtml(device.last_update)}</small></td>
            </tr>
        `).join('');
    }

    async function loadMonitoringData(page = 1) {
        showLoading();
        const params = new URLSearchParams({
            page: page, per_page: perPage,
            device: document.getElementById('filterDevice').value || 'Semua',
            ruangan: document.getElementById('filterRuangan').value || 'Semua',
            status_suhu: document.getElementById('filterStatus').value || 'Semua',
            status_kelembaban: document.getElementById('filterKelembaban').value || 'Semua',
            dari_tanggal: document.getElementById('filterDariTanggal').value || '',
            sampai_tanggal: document.getElementById('filterSampaiTanggal').value || ''
        });
        try {
            const response = await fetch(`${API_BASE}/monitoring/suhu/data?${params}`);
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    updateMonitoringTable(result.data || []);
                    updatePagination(result);
                }
            }
        } catch (error) { console.error('Error loading monitoring data:', error); showAlert('error', 'Gagal memuat data monitoring'); }
        finally { hideLoading(); }
    }

    function updateMonitoringTable(data) {
        const tbody = document.getElementById('tableBody');
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data yang ditemukan</td></tr>';
            return;
        }
        tbody.innerHTML = data.map(item => `
            <tr>
                <td class="timestamp">${escapeHtml(item.waktu)}</td>
                <td>${escapeHtml(item.device)}<br><small class="text-muted">${escapeHtml(item.device_name)}</small></td>
                <td>${escapeHtml(item.ruang)}</td>
                <td><strong>${item.suhu}°C</strong></td>
                <td>${item.kelembaban}%</td>
                <td><span class="status-badge ${item.status_suhu === 'Normal' ? 'status-normal' : 'status-warning'}">${item.status_suhu}</span></td>
                <td><span class="status-badge ${item.status_kelembaban === 'Normal' ? 'status-normal' : 'status-warning'}">${item.status_kelembaban}</span></td>
            </tr>
        `).join('');
    }

    function updatePagination(data) {
        currentPage = data.current_page;
        totalPages = data.last_page;
        totalItems = data.total;
        document.getElementById('paginationInfo').innerHTML = `Menampilkan ${((currentPage-1)*perPage)+1}-${Math.min(currentPage*perPage,totalItems)} dari ${totalItems} data (Halaman ${currentPage}/${totalPages})`;
        updatePaginationButtons();
    }

    function updatePaginationButtons() {
        const container = document.getElementById('paginationControls');
        if (!container) return;
        container.innerHTML = '';
        const prev = document.createElement('button');
        prev.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prev.disabled = currentPage === 1;
        prev.onclick = () => currentPage > 1 && loadMonitoringData(currentPage - 1);
        container.appendChild(prev);
        for (let i = Math.max(1, currentPage-2); i <= Math.min(totalPages, currentPage+2); i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = i === currentPage ? 'active' : '';
            btn.onclick = () => loadMonitoringData(i);
            container.appendChild(btn);
        }
        const next = document.createElement('button');
        next.innerHTML = '<i class="fas fa-chevron-right"></i>';
        next.disabled = currentPage === totalPages;
        next.onclick = () => currentPage < totalPages && loadMonitoringData(currentPage + 1);
        container.appendChild(next);
    }

    function resetFilters() {
        document.getElementById('filterDevice').value = 'Semua';
        document.getElementById('filterRuangan').value = 'Semua';
        document.getElementById('filterStatus').value = 'Semua';
        document.getElementById('filterKelembaban').value = 'Semua';
        document.getElementById('filterDariTanggal').value = '';
        document.getElementById('filterSampaiTanggal').value = '';
        document.getElementById('searchInput').value = '';
        loadMonitoringData(1);
        showAlert('info', 'Filter telah direset');
    }

    function setDateRange(type) {
        const today = new Date();
        const formatDate = (d) => {
            const day = d.getDate().toString().padStart(2,'0');
            const month = (d.getMonth()+1).toString().padStart(2,'0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        };
        if (type === 'today') {
            const d = formatDate(today);
            document.getElementById('filterDariTanggal').value = d;
            document.getElementById('filterSampaiTanggal').value = d;
        } else if (type === 'week') {
            const weekAgo = new Date(today);
            weekAgo.setDate(today.getDate() - 7);
            document.getElementById('filterDariTanggal').value = formatDate(weekAgo);
            document.getElementById('filterSampaiTanggal').value = formatDate(today);
        } else if (type === 'month') {
            const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            document.getElementById('filterDariTanggal').value = formatDate(monthStart);
            document.getElementById('filterSampaiTanggal').value = formatDate(today);
        }
        loadMonitoringData(1);
    }

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    function refreshAllData() {
        loadDashboardData();
        loadMonitoringData(currentPage);
        loadDeviceStatus();
        showAlert('success', 'Data berhasil diperbarui');
    }

    function showExportModal() {
        alert('Fitur ekspor data akan segera hadir');
    }

    function showAlert(type, message) {
        const container = document.getElementById('alertContainer');
        if (!container) return;
        const alertId = 'alert-'+Date.now();
        const bgClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
        container.insertAdjacentHTML('afterbegin', `<div id="${alertId}" class="alert ${bgClass} alert-dismissible fade show">${escapeHtml(message)}<button type="button" class="btn-close" onclick="document.getElementById('${alertId}').remove()"></button></div>`);
        setTimeout(() => { const el = document.getElementById(alertId); if (el) el.remove(); }, 5000);
    }

    function showLoading() { const overlay = document.getElementById('loadingOverlay'); if (overlay) overlay.style.display = 'flex'; }
    function hideLoading() { const overlay = document.getElementById('loadingOverlay'); if (overlay) overlay.style.display = 'none'; }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endsection