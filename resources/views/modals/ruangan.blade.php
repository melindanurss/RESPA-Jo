<div class="modal fade" id="modalRuangan" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-door-open me-2"></i> Manajemen Ruangan</h5>
                <button class="btn btn-sm btn-success" id="btnShowCreateRoom">+ Tambah Ruangan</button>
            </div>
            <div class="modal-body">
                <div id="roomFormWrap" style="display:none;" class="mb-3">
                    <div class="card p-3">
                        <form id="roomForm">
                            <input type="hidden" id="roomFormMode" value="create">
                            <input type="hidden" id="roomId">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>ID Ruang *</label>
                                    <select id="roomIdSelect" class="form-select" required></select>
                                </div>
                                <div class="col-md-6">
                                    <label>Device *</label>
                                    <select id="roomDeviceSelect" class="form-select" required></select>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="button" class="btn btn-secondary" id="btnCancelRoomForm">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>#</th><th>ID Ruang</th><th>Nama Ruangan</th><th>Device</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="roomsTbody"><tr><td colspan="5" class="text-center">Loading...</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalRuangan');
    if (modal) {
        modal.addEventListener('show.bs.modal', () => { loadRooms(); loadRoomDropdowns(); });
        document.getElementById('btnShowCreateRoom')?.addEventListener('click', () => showRoomForm('create'));
        document.getElementById('btnCancelRoomForm')?.addEventListener('click', hideRoomForm);
        document.getElementById('roomForm')?.addEventListener('submit', saveRoom);
    }
});

async function loadRooms() {
    try {
        const response = await fetch(`${API_BASE}/rooms`);
        const result = await response.json();
        const rooms = result.ok ? result.data : [];
        const tbody = document.getElementById('roomsTbody');
        if (rooms.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada ruangan</td></tr>';
            return;
        }
        tbody.innerHTML = rooms.map((room, i) => `
            <tr data-id="${room.id}">
                <td>${i+1}</td>
                <td>${escapeHtml(room.room_id)}</td>
                <td>${escapeHtml(room.room_name)}</td>
                <td>${escapeHtml(room.device_id || '-')}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary btn-edit-room" data-id="${room.id}">Edit</button>
                    <button class="btn btn-sm btn-outline-danger btn-delete-room" data-id="${room.id}">Hapus</button>
                </td>
            <tr>
        `).join('');
    } catch (error) { console.error(error); }
}

async function loadRoomDropdowns() {
    const roomsRes = await fetch(`${API_BASE}/rooms/list`);
    const roomsResult = await roomsRes.json();
    const roomSelect = document.getElementById('roomIdSelect');
    roomSelect.innerHTML = '<option value="">Pilih ID Ruang</option>';
    if (roomsResult.ok && roomsResult.rows) {
        roomsResult.rows.forEach(room => {
            const option = document.createElement('option');
            option.value = room.id_ruang;
            option.textContent = `${room.id_ruang} - ${room.nama_ruang}`;
            roomSelect.appendChild(option);
        });
    }

    const devicesRes = await fetch(`${API_BASE}/device?device_type=suhu`);
    const devices = await devicesRes.json();
    const deviceSelect = document.getElementById('roomDeviceSelect');
    deviceSelect.innerHTML = '<option value="">Pilih Device</option>';
    devices.forEach(device => {
        const option = document.createElement('option');
        option.value = device.device_key;
        option.textContent = device.name || device.device_key;
        deviceSelect.appendChild(option);
    });
}

function showRoomForm(mode, roomData = null) {
    document.getElementById('roomFormWrap').style.display = 'block';
    document.getElementById('roomFormMode').value = mode;
    if (mode === 'edit' && roomData) {
        document.getElementById('roomId').value = roomData.id;
        document.getElementById('roomIdSelect').value = roomData.room_id;
        document.getElementById('roomDeviceSelect').value = roomData.device_id;
    } else {
        document.getElementById('roomId').value = '';
        document.getElementById('roomIdSelect').value = '';
        document.getElementById('roomDeviceSelect').value = '';
    }
}

function hideRoomForm() {
    document.getElementById('roomFormWrap').style.display = 'none';
    document.getElementById('roomForm').reset();
}

async function saveRoom(e) {
    e.preventDefault();
    const mode = document.getElementById('roomFormMode').value;
    const roomId = document.getElementById('roomId').value;
    const payload = {
        room_id: document.getElementById('roomIdSelect').value,
        room_name: document.getElementById('roomIdSelect').selectedOptions[0]?.textContent.split(' - ')[1] || '',
        device_id: document.getElementById('roomDeviceSelect').value
    };

    let url = `${API_BASE}/rooms`;
    let options = { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }, body: JSON.stringify(payload) };

    if (mode === 'edit' && roomId) {
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('room_id', payload.room_id);
        formData.append('room_name', payload.room_name);
        formData.append('device_id', payload.device_id);
        options = { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }, body: formData };
        url = `${API_BASE}/rooms/${roomId}`;
    }

    const response = await fetch(url, options);
    if (response.ok) {
        hideRoomForm();
        loadRooms();
    }
}
</script>