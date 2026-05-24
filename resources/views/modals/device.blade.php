<div class="modal fade" id="modalDeviceSuhu" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-microchip me-2"></i> Daftar Device Suhu</h5>
                <button class="btn btn-sm btn-success" id="btnShowCreate">+ Tambah Device</button>
            </div>
            <div class="modal-body">
                <div id="deviceFormWrap" style="display:none;" class="mb-3">
                    <div class="card p-3">
                        <form id="deviceForm">
                            <input type="hidden" id="deviceFormMode" value="create">
                            <input type="hidden" id="deviceId">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Device Key *</label>
                                    <input id="deviceKey" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Nama Device</label>
                                    <input id="deviceName" class="form-control">
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="button" class="btn btn-secondary" id="btnCancelForm">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>#</th><th>Device Key</th><th>Nama</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="devicesTbody"><tr><td colspan="4" class="text-center">Loading...</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalDeviceSuhu');
    if (modal) {
        modal.addEventListener('show.bs.modal', loadDevices);
        document.getElementById('btnShowCreate')?.addEventListener('click', () => showDeviceForm('create'));
        document.getElementById('btnCancelForm')?.addEventListener('click', hideDeviceForm);
        document.getElementById('deviceForm')?.addEventListener('submit', saveDevice);
    }
});

async function loadDevices() {
    try {
        const response = await fetch(`${API_BASE}/device?device_type=suhu`);
        const devices = await response.json();
        const tbody = document.getElementById('devicesTbody');
        if (devices.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada device</td></tr>';
            return;
        }
        tbody.innerHTML = devices.map((device, i) => `
            <tr data-id="${device.id}">
                <td>${i+1}</td>
                <td>${escapeHtml(device.device_key)}</td>
                <td>${escapeHtml(device.name || '-')}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${device.id}">Edit</button>
                    <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${device.id}">Hapus</button>
                </td>
            </tr>
        `).join('');
    } catch (error) { console.error(error); }
}

function showDeviceForm(mode, deviceData = null) {
    document.getElementById('deviceFormWrap').style.display = 'block';
    document.getElementById('deviceFormMode').value = mode;
    if (mode === 'edit' && deviceData) {
        document.getElementById('deviceId').value = deviceData.id;
        document.getElementById('deviceKey').value = deviceData.device_key;
        document.getElementById('deviceName').value = deviceData.name || '';
    } else {
        document.getElementById('deviceId').value = '';
        document.getElementById('deviceKey').value = '';
        document.getElementById('deviceName').value = '';
    }
}

function hideDeviceForm() {
    document.getElementById('deviceFormWrap').style.display = 'none';
    document.getElementById('deviceForm').reset();
}

async function saveDevice(e) {
    e.preventDefault();
    const mode = document.getElementById('deviceFormMode').value;
    const deviceId = document.getElementById('deviceId').value;
    const payload = {
        device_key: document.getElementById('deviceKey').value.trim(),
        device_type: 'suhu',
        name: document.getElementById('deviceName').value.trim()
    };

    let url = `${API_BASE}/device`;
    let method = 'POST';

    if (mode === 'edit' && deviceId) {
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('device_key', payload.device_key);
        formData.append('device_type', payload.device_type);
        formData.append('name', payload.name);
        var response = await fetch(`${API_BASE}/device/${deviceId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }, body: formData });
    } else {
        var response = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }, body: JSON.stringify(payload) });
    }

    if (response.ok) {
        hideDeviceForm();
        loadDevices();
    }
}
</script>