<nav class="navbar navbar-dark navbar-custom">
    <div class="container-fluid">
        <button class="btn btn-link text-white d-lg-none me-2" id="sidebarToggle">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <div class="header-logo">
                <div class="header-logo-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div>
                    JoMonitor
                </div>
            </div>
        </a>
        <div class="ms-auto d-flex align-items-center">
            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" role="button" id="notificationDropdown" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" 
                        style="min-width: 350px; max-width: 400px; max-height: 500px; overflow-y: auto;">
                        <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
                            <div>
                                <strong>Notifikasi</strong>
                                <span class="badge bg-primary ms-2" id="dropdownNotificationCount">0</span>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-link text-decoration-none p-0" onclick="markAllNotificationsAsRead()">
                                    <small>Tandai semua dibaca</small>
                                </button>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider m-0"></li>
                        <li>
                            <div id="notificationList" class="p-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Memuat notifikasi...
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider m-0"></li>
                        <li class="dropdown-footer text-center px-3 py-2">
                            <a href="javascript:void(0)" class="text-decoration-none" onclick="viewAllNotifications()">
                                <small>Lihat semua notifikasi</small>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown user-menu">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="user-dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                {{ auth()->user()->nama ?? 'Admin' }} <i class="fas fa-caret-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form id="logout-form" method="GET" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Notification Styles */
    .notification-dropdown {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.2s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .notification-item {
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        margin-bottom: 8px;
        padding: 10px;
        border-radius: 8px;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa;
        transform: translateX(-2px);
    }
    
    .notification-item.unread {
        background-color: #f0f7ff;
        border-left-color: #007bff;
    }
    
    .notification-title {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.3;
        margin-bottom: 4px;
    }
    
    .notification-message {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.4;
    }
    
    .notification-time {
        font-size: 11px;
        color: #adb5bd;
    }
    
    .notification-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        flex-shrink: 0;
    }
    
    .notification-icon.suhu {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .notification-icon.infus {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    
    .notification-icon.warning {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    .notification-icon.critical {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
</style>

<script>
class NotificationManager {
    constructor() {
        this.baseUrl = '{{ url("/") }}';
        this.csrfToken = '{{ csrf_token() }}';
        this.pollingInterval = null;
        this.lastCheck = null;
        this.init();
    }
    
    init() {
        // Load initial notifications
        this.loadNotifications();
        this.loadUnreadCount();
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Start polling for new notifications
        this.startPolling();
        
        console.log('NotificationManager initialized');
    }
    
    setupEventListeners() {
        // Refresh when dropdown is opened
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.addEventListener('show.bs.dropdown', () => {
                this.loadNotifications();
            });
        }
        
        // Auto-refresh when tab becomes visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.loadNotifications();
                this.loadUnreadCount();
            }
        });
    }
    
    async loadUnreadCount() {
        try {
            const response = await fetch(`${this.baseUrl}/api/notifications/count`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    this.updateBadgeCount(result.unread_count || 0);
                }
            }
        } catch (error) {
            console.error('Error loading unread count:', error);
        }
    }
    
    async loadNotifications() {
        try {
            const response = await fetch(`${this.baseUrl}/api/notifications?limit=10`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    this.updateNotificationList(result.notifications || []);
                    this.updateBadgeCount(result.unread_count || 0);
                    this.lastCheck = new Date().toISOString();
                }
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.showErrorState();
        }
    }
    
    updateBadgeCount(count) {
        const badge = document.getElementById('notificationCount');
        const dropdownBadge = document.getElementById('dropdownNotificationCount');
        
        if (badge) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
        
        if (dropdownBadge) {
            dropdownBadge.textContent = count;
        }
        
        // Update title tab jika ada notifikasi baru
        if (count > 0 && !document.hidden) {
            document.title = `(${count}) JoMonitor - Monitoring System`;
        } else if (document.title.startsWith('(')) {
            document.title = 'JoMonitor - Monitoring System';
        }
    }
    
    updateNotificationList(notifications) {
        const container = document.getElementById('notificationList');
        if (!container) return;
        
        if (!notifications || notifications.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-bell-slash fa-2x mb-3"></i>
                    <div>Tidak ada notifikasi</div>
                    <small class="text-muted mt-2">Semua notifikasi sudah dibaca</small>
                </div>
            `;
            return;
        }
        
        container.innerHTML = notifications.map(notif => {
            // Determine icon and color based on type and severity
            let iconClass = 'fas fa-bell';
            let iconColorClass = '';
            
            if (notif.type === 'suhu') {
                iconClass = 'fas fa-thermometer-half';
                iconColorClass = notif.severity === 'warning' ? 'warning' : 'suhu';
            } else if (notif.type === 'infus') {
                iconClass = 'fas fa-tint';
                iconColorClass = notif.severity === 'warning' ? 'warning' : 'infus';
            }
            
            if (notif.severity === 'critical') {
                iconColorClass = 'critical';
            }
            
            const isUnread = !notif.is_read;
            const timeDisplay = notif.time_ago || notif.created_at;
            
            return `
                <div class="notification-item ${isUnread ? 'unread' : ''}" 
                     onclick="window.notificationManager.markAsRead(${notif.id}, this)">
                    <div class="d-flex">
                        <div class="notification-icon ${iconColorClass}">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="notification-title">${this.escapeHtml(notif.title)}</div>
                                    <div class="notification-message">${this.escapeHtml(notif.message)}</div>
                                </div>
                                <div class="text-end">
                                    <small class="notification-time">${timeDisplay}</small>
                                    ${isUnread ? '<span class="badge bg-primary btn-xs mt-1">Baru</span>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    showErrorState() {
        const container = document.getElementById('notificationList');
        if (!container) return;
        
        container.innerHTML = `
            <div class="text-center text-danger py-4">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <div>Gagal memuat notifikasi</div>
                <button class="btn btn-sm btn-outline-primary mt-2" onclick="window.notificationManager.loadNotifications()">
                    <i class="fas fa-redo me-1"></i>Coba Lagi
                </button>
            </div>
        `;
    }
    
    async markAsRead(notificationId, element = null) {
        try {
            const response = await fetch(`${this.baseUrl}/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    // Update UI immediately
                    if (element) {
                        element.classList.remove('unread');
                        const badge = element.querySelector('.badge.bg-primary');
                        if (badge) badge.remove();
                    }
                    
                    // Reload counts
                    this.loadUnreadCount();
                    
                    // If dropdown is open, refresh the list
                    const dropdown = document.getElementById('notificationDropdown');
                    if (dropdown && dropdown.classList.contains('show')) {
                        setTimeout(() => this.loadNotifications(), 300);
                    }
                }
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }
    
    async markAllAsRead() {
        try {
            const response = await fetch(`${this.baseUrl}/api/notifications/read-all`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    // Clear all "unread" classes
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const badge = item.querySelector('.badge.bg-primary');
                        if (badge) badge.remove();
                    });
                    
                    // Update counts
                    this.loadUnreadCount();
                    this.loadNotifications();
                    
                    // Show success message
                    this.showToast('Semua notifikasi ditandai sebagai dibaca', 'success');
                }
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
            this.showToast('Gagal menandai semua notifikasi', 'error');
        }
    }
    
    startPolling() {
        // Poll every 30 seconds for new notifications
        this.pollingInterval = setInterval(() => {
            this.loadUnreadCount();
            
            // Only load full notifications if dropdown is open
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                this.loadNotifications();
            }
        }, 30000); // 30 seconds
    }
    
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }
    
    showToast(message, type = 'info') {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = `notification-toast alert alert-${type} alert-dismissible fade show`;
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.notificationManager = new NotificationManager();
});

// Global functions for onclick handlers
function markAllNotificationsAsRead() {
    if (window.notificationManager) {
        window.notificationManager.markAllAsRead();
    }
}

function viewAllNotifications() {
    // Show modal or redirect to notifications page
    alert('Fitur lihat semua notifikasi akan datang!');
    // Or implement modal:
    // const modal = new bootstrap.Modal(document.getElementById('allNotificationsModal'));
    // modal.show();
}

function showProfilAdmin() {
    alert('Fitur profil admin akan datang!');
}

function showPengaturanAdmin() {
    alert('Fitur pengaturan admin akan datang!');
}
</script>