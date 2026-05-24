@extends('layout.app')

@section('judul', 'Dashboard Monitoring Suhu')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
    .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); border: none; position: relative; overflow: hidden; transition: all 0.3s ease; }
    .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--gradient); }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); }
    .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .card-title { font-size: 0.95rem; font-weight: 500; color: #6c757d; margin: 0; }
    .card-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: var(--gradient-light); color: #2E8B57; }
    .card-value { font-size: 2.2rem; font-weight: 700; margin-bottom: 10px; background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .card-footer { font-size: 0.8rem; color: #6c757d; margin-top: 15px; display: flex; align-items: center; gap: 5px; }
    .status-container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
    .status-card { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); border: none; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; }
    .status-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); }
    .status-card-header { background: var(--gradient-light); border-bottom: 1px solid #dee2e6; font-weight: 600; padding: 15px 20px; color: #2E8B57; display: flex; justify-content: space-between; align-items: center; }
    .server-list { max-height: 400px; overflow-y: auto; }
    .server-item { padding: 15px 20px; border-bottom: 1px solid #dee2e6; transition: background-color 0.3s; }
    .server-item:hover { background-color: rgba(122, 191, 85, 0.05); }
    .server-info { display: flex; justify-content: space-between; align-items: center; }
    .server-name { font-weight: 600; margin-bottom: 5px; color: #343a40; }
    .server-location { font-size: 12px; color: #6c757d; }
    .server-temp { font-size: 18px; font-weight: 700; margin-bottom: 5px; }
    .realtime-charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
    .chart-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); position: relative; overflow: hidden; }
    .chart-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--gradient); }
    .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
    .chart-title { font-size: 18px; font-weight: 600; color: #343a40; }
    .chart-actions { display: flex; gap: 10px; }
    .chart-actions button { background: var(--gradient-light); border: none; color: #2E8B57; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; transition: all 0.3s; }
    .chart-actions button:hover { background: var(--gradient); color: white; }
    .chart-container { height: 260px; }
    .data-table-container { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); margin-bottom: 30px; position: relative; overflow: hidden; }
    .data-table-container::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--gradient); }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
    .table-title { font-size: 1.1rem; font-weight: 600; color: #343a40; }
    .search-box { position: relative; }
    .search-box input { background: #f8f9fa; border: 1px solid #dee2e6; padding: 8px 15px 8px 35px; border-radius: 6px; width: 200px; }
    .search-box i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6c757d; }
    .table-actions button { background: var(--gradient-light); border: 1px solid #dee2e6; color: #2E8B57; padding: 8px 15px; border-radius: 6px; cursor: pointer; }
    .status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
    .status-normal { background: #d4edda; color: #155724; }
    .status-warning { background: #fff3cd; color: #856404; }
    .status-critical { background: #f8d7da; color: #721c24; }
    .status-offline { background: rgba(108, 117, 125, 0.1); color: #6c757d; }
    .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
    .spinner { width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .alert-container { position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 400px; }
    @media (max-width: 768px) { .dashboard-grid, .status-container, .realtime-charts-grid { grid-template-columns: 1fr; } .search-box input { width: 100%; } }
</style>
@endsection

@section('Content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <div><h1 class="h3 fw-bold text-dark">RESPA-Jo - Dashboard Monitoring Suhu</h1><p class="text-muted">Sistem monitoring real-time suhu ruangan dan kelembaban</p></div>
    <div class="btn-toolbar mb-2 mb-md-0"><div class="btn-group me-2"><button id="btnOpenDevices" type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDevice"><i class="fas fa-microchip me-1"></i> Device</button><button type="button" class="btn btn-sm btn-outline-secondary" id="refreshBtn" onclick="refreshDashboardData()"><i class="fas fa-sync-alt me-1"></i> Refresh</button></div></div>
</div>

<div class="dashboard-grid mb-4">
    <div class="card"><div class="card-header"><div class="card-title">Device Terpantau</div><div class="card-icon"><i class="fas fa-microchip"></i></div></div><div class="card-value" id="totalDevices">{{ $totalDevices ?? 0 }}</div><div class="card-footer"><i class="fas fa-info-circle text-primary"></i> <span>Total device suhu aktif</span></div></div>
    <div class="card"><div class="card-header"><div class="card-title">Ruangan Terpantau</div><div class="card-icon"><i class="fas fa-door-open"></i></div></div><div class="card-value" id="roomMonitorings">{{ $roomMonitorings ?? 0 }}</div><div class="card-footer"><i class="fas fa-info-circle text-primary"></i> <span>Total ruangan yang dimonitoring</span></div></div>
    <div class="card"><div class="card-header"><div class="card-title">Status Normal</div><div class="card-icon"><i class="fas fa-check-circle"></i></div></div><div class="card-value" id="normalDevices">0</div><div class="card-footer"><i class="fas fa-info-circle text-success"></i> <span>Suhu ≤ 27°C & Kelembaban ≤ 60%</span></div></div>
    <div class="card"><div class="card-header"><div class="card-title">Status Warning</div><div class="card-icon"><i class="fas fa-exclamation-triangle"></i></div></div><div class="card-value" id="warningDevices">0</div><div class="card-footer"><i class="fas fa-info-circle text-warning"></i> <span>Suhu > 27°C atau Kelembaban > 60%</span></div></div>
</div>

<div class="status-container mb-4">
    <div class="status-card"><div class="status-card-header"><span><i class="fas fa-server me-2"></i> Status Monitoring Suhu</span><div class="status-summary"><span class="badge bg-warning me-2" id="warningCountHeader">0 Warning</span><span class="badge bg-success" id="normalCountHeader">0 Normal</span></div></div><div class="status-card-body"><div class="server-list" id="serverStatusList"><div class="server-item text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading data...</div></div></div></div>
</div>

<div class="realtime-charts-grid mb-4">
    <div class="chart-card"><div class="chart-header"><div class="chart-title"><i class="fas fa-thermometer-half me-2"></i> Suhu</div><div class="chart-actions"><button onclick="changeChartRange('temperature', '1h')" class="active">1 Jam</button><button onclick="changeChartRange('temperature', '6h')">6 Jam</button><button onclick="changeChartRange('temperature', '24h')">24 Jam</button></div></div><div class="chart-container"><canvas id="temperatureChart"></canvas></div></div>
    <div class="chart-card"><div class="chart-header"><div class="chart-title"><i class="fas fa-cloud-rain me-2"></i> Kelembaban</div><div class="chart-actions"><button onclick="changeChartRange('humidity', '1h')" class="active">1 Jam</button><button onclick="changeChartRange('humidity', '6h')">6 Jam</button><button onclick="changeChartRange('humidity', '24h')">24 Jam</button></div></div><div class="chart-container"><canvas id="humidityChart"></canvas></div></div>
</div>

<div class="data-table-container"><div class="table-header"><div class="table-title"><i class="fas fa-thermometer-half me-2"></i> Status Suhu per Device</div><div class="table-actions"><div class="search-box"><i class="fas fa-search"></i><input type="text" placeholder="Cari device..." id="searchDeviceStatus" onkeyup="filterDeviceStatusTable()"></div><button onclick="refreshDeviceStatus()"><i class="fas fa-sync-alt"></i> Refresh</button></div></div><div class="table-responsive"><table class="table table-striped"><thead><tr><th>DEVICE</th><th>ID RUANG</th><th>NAMA RUANG</th><th>SUHU (°C)</th><th>KELEMBABAN (%)</th><th>STATUS</th><th>UPDATE TERAKHIR</th></tr></thead><tbody id="deviceStatusBody"><tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Memuat status device...</td></tr></tbody>}</div></div>

<div id="loadingOverlay" class="loading-overlay"><div class="spinner"></div></div>
<div id="alertContainer" class="alert-container"></div>
@endsection

@section('Modal')
<div class="modal fade" id="modalDevice" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><i class="fas fa-microchip me-2"></i> Daftar Device</h5><button id="btnShowCreate" type="button" class="btn btn-sm btn-success">+ Tambah Device</button></div><div class="modal-body"><div id="deviceFormWrap" style="display:none;" class="mb-3"><div class="card p-3"><form id="deviceForm"><input type="hidden" id="deviceFormMode" value="create"><input type="hidden" id="deviceId" value=""><div class="row"><div class="col-md-6"><label class="form-label">Device Key *</label><input id="deviceKey" class="form-control" required></div><div class="col-md-6"><label class="form-label">Tipe Device *</label><select id="deviceType" class="form-select"><option value="suhu">Suhu</option><option value="infus">Infus</option></select></div><div class="col-12 mt-3"><button id="btnCancelForm" type="button" class="btn btn-sm btn-outline-secondary">Batal</button><button id="btnSubmitForm" type="submit" class="btn btn-sm btn-primary ms-2">Simpan</button></div></div></form></div></div><table class="table table-bordered"><thead><tr><th>#</th><th>Device Key</th><th>Tipe</th><th>Aksi</th></tr></thead><tbody id="devicesTbody"><tr><td colspan="4" class="text-center">Loading...</td></tr></tbody></table></div></div></div></div>
@endsection

@section('script')
<script>
    const API_BASE = '{{ url("/") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let temperatureChart = null, humidityChart = null;
    let currentTempRange = '1h', currentHumidityRange = '1h';
    let deviceStatusData = [];

    document.addEventListener('DOMContentLoaded', function() {
        initializeDeviceModal();
        loadDashboardData();
        loadTemperatureChart();
        loadHumidityChart();
        loadDeviceStatus();
        setInterval(() => { loadDashboardData(); loadDeviceStatus(); }, 30000);
        setInterval(() => { loadTemperatureChart(); loadHumidityChart(); }, 60000);
    });

    async function loadDashboardData() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/stats`);
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    document.getElementById('totalDevices').textContent = result.data.total_devices || 0;
                    document.getElementById('roomMonitorings').textContent = result.data.room_monitorings || 0;
                }
            }
            const serverResponse = await fetch(`${API_BASE}/api/dashboard/server-status`);
            if (serverResponse.ok) {
                const result = await serverResponse.json();
                if (result.success && result.data) {
                    document.getElementById('normalCountHeader').innerHTML = `${result.data.normalCount || 0} Normal`;
                    document.getElementById('warningCountHeader').innerHTML = `${result.data.warningCount || 0} Warning`;
                    document.getElementById('normalDevices').textContent = result.data.normalCount || 0;
                    document.getElementById('warningDevices').textContent = result.data.warningCount || 0;
                    const servers = result.data.servers || [];
                    const listElement = document.getElementById('serverStatusList');
                    if (servers.length > 0) {
                        listElement.innerHTML = servers.map(server => `<div class="server-item"><div class="server-info"><div><div class="server-name">${escapeHtml(server.name)}</div><div class="server-location">${escapeHtml(server.location)}</div></div><div><div class="server-temp ${server.status === 'warning' ? 'text-warning' : 'text-success'}">${server.temperature}°C</div><span class="status-badge ${server.status === 'normal' ? 'status-normal' : 'status-warning'}">${server.status === 'normal' ? 'Normal' : 'Warning'}</span></div></div></div>`).join('');
                    } else { listElement.innerHTML = '<div class="server-item text-center text-muted py-4">Tidak ada data server</div>'; }
                }
            }
        } catch (error) { console.error('Error loading dashboard:', error); showAlert('error', 'Gagal memuat data dashboard'); }
    }

    async function loadTemperatureChart() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/temperature-realtime?range=${currentTempRange}`);
            if (response.ok) {
                const result = await response.json();
                if (result.success) renderTemperatureChart(result.data);
                else renderTemperatureChart(getSampleData(25, 30));
            } else renderTemperatureChart(getSampleData(25, 30));
        } catch (error) { renderTemperatureChart(getSampleData(25, 30)); }
    }

    async function loadHumidityChart() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/humidity-realtime?range=${currentHumidityRange}`);
            if (response.ok) {
                const result = await response.json();
                if (result.success) renderHumidityChart(result.data);
                else renderHumidityChart(getSampleData(50, 70));
            } else renderHumidityChart(getSampleData(50, 70));
        } catch (error) { renderHumidityChart(getSampleData(50, 70)); }
    }

    function renderTemperatureChart(data) {
        const ctx = document.getElementById('temperatureChart').getContext('2d');
        if (temperatureChart) temperatureChart.destroy();
        temperatureChart = new Chart(ctx, { type: 'line', data: { labels: data.labels, datasets: [{ label: 'Suhu (°C)', data: data.values, borderColor: '#e74c3c', backgroundColor: 'rgba(231, 76, 60, 0.1)', borderWidth: 2, tension: 0.4, fill: true, pointBackgroundColor: (ctx) => ctx.dataset.data[ctx.dataIndex] > 28 ? '#e74c3c' : '#2ecc71' }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { title: { display: true, text: 'Suhu (°C)' } } } } });
    }

    function renderHumidityChart(data) {
        const ctx = document.getElementById('humidityChart').getContext('2d');
        if (humidityChart) humidityChart.destroy();
        humidityChart = new Chart(ctx, { type: 'line', data: { labels: data.labels, datasets: [{ label: 'Kelembaban (%)', data: data.values, borderColor: '#3498db', backgroundColor: 'rgba(52, 152, 219, 0.1)', borderWidth: 2, tension: 0.4, fill: true }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { title: { display: true, text: 'Kelembaban (%)' }, min: 0, max: 100 } } } });
    }

    async function loadDeviceStatus() {
        try {
            const response = await fetch(`${API_BASE}/api/dashboard/temperature-devices-status`);
            if (response.ok) {
                const result = await response.json();
                if (result.success) { deviceStatusData = result.data || []; updateDeviceStatusTable(deviceStatusData); }
            }
        } catch (error) { console.error('Error loading device status:', error); }
    }

    function updateDeviceStatusTable(data) {
        const tbody = document.getElementById('deviceStatusBody');
        if (!data || data.length === 0) { tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Tidak ada device aktif</td></tr>'; return; }
        tbody.innerHTML = data.map(device => `<tr><td><div class="fw-bold">${escapeHtml(device.device_name)}</div><small class="text-muted">${escapeHtml(device.device_key)}</small></td><td><code>${escapeHtml(device.room_id)}</code></td><td>${escapeHtml(device.room_name)}</td><td class="${device.temperature_status === 'Warning' ? 'text-warning fw-bold' : 'text-success'}">${device.temperature}</td><td class="${device.humidity_status === 'Warning' ? 'text-warning fw-bold' : 'text-success'}">${device.humidity}</td><td><span class="status-badge ${device.status === 'Normal' ? 'status-normal' : 'status-warning'}">${escapeHtml(device.status_display)}</span></td><td class="timestamp">${escapeHtml(device.time_ago)}<br><small class="text-muted">${escapeHtml(device.last_update)}</small></td></tr>`).join('');
    }

    function filterDeviceStatusTable() { const searchTerm = document.getElementById('searchDeviceStatus').value.toLowerCase(); const filteredData = deviceStatusData.filter(device => device.device_name.toLowerCase().includes(searchTerm) || device.device_key.toLowerCase().includes(searchTerm) || device.room_name.toLowerCase().includes(searchTerm)); updateDeviceStatusTable(filteredData); }

    function changeChartRange(type, range) { if (type === 'temperature') { currentTempRange = range; loadTemperatureChart(); } else { currentHumidityRange = range; loadHumidityChart(); } }
    function refreshDashboardData() { loadDashboardData(); loadDeviceStatus(); showAlert('success', 'Dashboard diperbarui'); }
    function refreshDeviceStatus() { loadDeviceStatus(); showAlert('info', 'Status device diperbarui'); }

    function getSampleData(min, max) { const values = [], labels = []; for (let i = 59; i >= 0; i -= 5) { labels.push(new Date(Date.now() - i * 60000).getMinutes().toString().padStart(2, '0')); values.push(parseFloat((min + Math.random() * (max - min)).toFixed(1))); } return { labels, values, current: values[values.length-1], min: Math.min(...values), max: Math.max(...values), avg: values.reduce((a,b)=>a+b,0)/values.length }; }

    function escapeHtml(text) { if (!text) return ''; const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }
    function showAlert(type, message) { const container = document.getElementById('alertContainer'); if (!container) return; const alertId = 'alert-'+Date.now(); const bgClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : type === 'warning' ? 'alert-warning' : 'alert-info'; container.insertAdjacentHTML('afterbegin', `<div id="${alertId}" class="alert ${bgClass} alert-dismissible fade show">${escapeHtml(message)}<button type="button" class="btn-close" onclick="document.getElementById('${alertId}').remove()"></button></div>`); setTimeout(() => { const el = document.getElementById(alertId); if (el) el.remove(); }, 5000); }

    function initializeDeviceModal() {
        const modal = document.getElementById('modalDevice');
        if (modal) modal.addEventListener('show.bs.modal', () => loadDevices());
        document.getElementById('btnShowCreate')?.addEventListener('click', () => { document.getElementById('deviceFormWrap').style.display = 'block'; document.getElementById('deviceFormMode').value = 'create'; document.getElementById('deviceId').value = ''; document.getElementById('deviceKey').value = ''; });
        document.getElementById('btnCancelForm')?.addEventListener('click', () => { document.getElementById('deviceFormWrap').style.display = 'none'; });
        document.getElementById('deviceForm')?.addEventListener('submit', (e) => { e.preventDefault(); saveDevice(); });
    }

    async function loadDevices() {
        const tbody = document.getElementById('devicesTbody');
        try {
            const response = await fetch(`${API_BASE}/device?device_type=suhu`);
            const devices = await response.json();
            if (devices.length === 0) { tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Tidak ada device</td></tr>'; return; }
            tbody.innerHTML = devices.map((device, i) => `<tr><td>${i+1}</td><td>${escapeHtml(device.device_key)}</td><td>${escapeHtml(device.device_type)}</td><td><button class="btn btn-sm btn-outline-primary btn-edit" data-id="${device.id}">Edit</button> <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${device.id}">Hapus</button></td></tr>`).join('');
            document.querySelectorAll('.btn-edit').forEach(btn => btn.addEventListener('click', () => editDevice(btn.dataset.id)));
            document.querySelectorAll('.btn-delete').forEach(btn => btn.addEventListener('click', () => deleteDevice(btn.dataset.id)));
        } catch (error) { tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Gagal memuat data</td></tr>'; }
    }

    async function saveDevice() {
        const mode = document.getElementById('deviceFormMode').value;
        const id = document.getElementById('deviceId').value;
        const deviceKey = document.getElementById('deviceKey').value.trim();
        const deviceType = document.getElementById('deviceType').value;
        if (!deviceKey) { showAlert('warning', 'Device Key wajib diisi!'); return; }
        let url = `${API_BASE}/device`, method = 'POST', body = JSON.stringify({ device_key: deviceKey, device_type: deviceType });
        if (mode === 'edit' && id) { url = `${API_BASE}/device/${id}`; method = 'PUT'; }
        try {
            const response = await fetch(url, { method: method === 'PUT' ? 'POST' : 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Content-Type': 'application/json' }, body });
            if (response.ok) { loadDevices(); document.getElementById('deviceFormWrap').style.display = 'none'; showAlert('success', 'Device berhasil disimpan!'); loadDashboardData(); }
            else showAlert('error', 'Gagal menyimpan device');
        } catch (error) { showAlert('error', 'Error: ' + error.message); }
    }

    async function editDevice(id) {
        try {
            const response = await fetch(`${API_BASE}/device/${id}`);
            const device = await response.json();
            const data = device.device || device;
            document.getElementById('deviceFormMode').value = 'edit';
            document.getElementById('deviceId').value = data.id;
            document.getElementById('deviceKey').value = data.device_key;
            document.getElementById('deviceType').value = data.device_type;
            document.getElementById('deviceFormWrap').style.display = 'block';
        } catch (error) { showAlert('error', 'Gagal memuat data device'); }
    }

    async function deleteDevice(id) {
        if (!confirm('Hapus device ini?')) return;
        try {
            const response = await fetch(`${API_BASE}/device/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } });
            if (response.ok) { loadDevices(); loadDashboardData(); showAlert('success', 'Device berhasil dihapus!'); }
            else showAlert('error', 'Gagal menghapus device');
        } catch (error) { showAlert('error', 'Error: ' + error.message); }
    }
</script>
@endsection