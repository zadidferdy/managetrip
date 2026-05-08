<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VehiclePool — @yield('title', 'Dashboard')</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.0.0/dist/tabler-icons.min.css">
@stack('styles')
<style>
:root {
  --sidebar-w: 240px;
  --primary: #1a3c5e;
  --primary-light: #e8f0fa;
  --accent: #e67e22;
  --accent-light: #fdf3e7;
  --success: #27ae60;
  --success-bg: #eafaf1;
  --warning: #f39c12;
  --warning-bg: #fef9e7;
  --danger: #e74c3c;
  --danger-bg: #fdf0ef;
  --info: #2980b9;
  --info-bg: #ebf5fb;
  --text: #1a2332;
  --text-muted: #6b7a8d;
  --border: #e2e8f0;
  --surface: #f7f8fa;
  --card: #ffffff;
  --sidebar-bg: #0f2236;
  --sidebar-text: #b0c4d8;
  --sidebar-hover: rgba(255,255,255,0.07);
  --sidebar-active: rgba(255,255,255,0.12);
  --radius: 10px;
  --radius-lg: 14px;
  --shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04);
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--surface);
  color: var(--text);
  display: flex;
  min-height: 100vh;
  font-size: 14px;
  line-height: 1.6;
}

/* ── SIDEBAR ── */
#sidebar {
  width: var(--sidebar-w);
  background: var(--sidebar-bg);
  position: fixed;
  top: 0; left: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
  z-index: 100;
  overflow: hidden;
}

.sidebar-logo {
  padding: 20px 16px 16px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  display: flex;
  align-items: center;
  gap: 10px;
}
.sidebar-logo .logo-icon {
  width: 34px; height: 34px;
  background: var(--accent);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  color: white; font-size: 16px; flex-shrink: 0;
}
.sidebar-logo .logo-text { color: #fff; font-size: 15px; font-weight: 600; letter-spacing: -0.3px; }
.sidebar-logo .logo-sub { font-size: 10px; color: var(--sidebar-text); font-family: 'Space Mono', monospace; margin-top: 1px; text-transform: uppercase; letter-spacing: 0.5px; }

.sidebar-section { padding: 12px 12px 4px; font-size: 10px; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 1px; font-family: 'Space Mono', monospace; }

.sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0; }
.sidebar-nav::-webkit-scrollbar { width: 4px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 16px; cursor: pointer;
  border-radius: 8px; margin: 1px 8px;
  color: var(--sidebar-text); font-size: 13.5px; font-weight: 400;
  transition: background 0.15s, color 0.15s;
  position: relative; white-space: nowrap;
  text-decoration: none;
}
.nav-item i { font-size: 17px; flex-shrink: 0; width: 20px; text-align: center; }
.nav-item:hover { background: var(--sidebar-hover); color: #fff; }
.nav-item.active { background: var(--sidebar-active); color: #fff; }
.nav-item.active::before {
  content: ''; position: absolute; left: -8px; top: 50%;
  transform: translateY(-50%); width: 3px; height: 22px;
  background: var(--accent); border-radius: 0 3px 3px 0;
}

.sidebar-footer {
  padding: 12px 8px;
  border-top: 1px solid rgba(255,255,255,0.06);
}
.user-card {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 8px; border-radius: 8px; cursor: pointer; transition: background 0.15s;
}
.user-card:hover { background: var(--sidebar-hover); }
.user-avatar {
  width: 32px; height: 32px; border-radius: 50%;
  background: linear-gradient(135deg, #3498db, #2c3e50);
  display: flex; align-items: center; justify-content: center;
  color: white; font-size: 12px; font-weight: 600; flex-shrink: 0;
}
.user-info .user-name { color: #fff; font-size: 13px; font-weight: 500; }
.user-info .user-role { color: var(--sidebar-text); font-size: 11px; }

/* ── MAIN ── */
#main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

.topbar {
  background: var(--card); border-bottom: 1px solid var(--border);
  padding: 0 28px; height: 58px;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 50;
}
.topbar-left h1 { font-size: 17px; font-weight: 600; color: var(--text); }
.topbar-left .breadcrumb { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
.topbar-right { display: flex; align-items: center; gap: 12px; }
.topbar-btn {
  width: 36px; height: 36px; border: 1px solid var(--border); border-radius: 8px;
  background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center;
  color: var(--text-muted); font-size: 17px; transition: all 0.15s; position: relative;
}
.topbar-btn:hover { background: var(--surface); color: var(--text); }
.mini-avatar {
  width: 34px; height: 34px; border-radius: 50%;
  background: linear-gradient(135deg, #3498db, #2c3e50);
  display: flex; align-items: center; justify-content: center;
  color: white; font-size: 12px; font-weight: 600; cursor: pointer;
}

.content-area { flex: 1; padding: 24px 28px; }

/* ── SHARED COMPONENTS ── */
.card { background: var(--card); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow); }
.card-header { padding: 16px 20px 12px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.card-header h3 { font-size: 15px; font-weight: 600; }
.card-body { padding: 20px; }

.btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 8px; font-size: 13.5px; font-weight: 500;
  cursor: pointer; border: none; transition: all 0.15s;
  font-family: 'DM Sans', sans-serif; white-space: nowrap; text-decoration: none;
}
.btn-primary { background: var(--primary); color: white; }
.btn-primary:hover { background: #1f4a78; }
.btn-success { background: var(--success); color: white; }
.btn-success:hover { background: #219a52; }
.btn-danger { background: var(--danger); color: white; }
.btn-danger:hover { background: #c0392b; }
.btn-warning { background: var(--warning); color: white; }
.btn-warning:hover { background: #d68910; }
.btn-outline { background: transparent; color: var(--text-muted); border: 1px solid var(--border); }
.btn-outline:hover { background: var(--surface); color: var(--text); }
.btn-sm { padding: 5px 11px; font-size: 12.5px; }
.btn-icon { padding: 6px 8px; }

.badge {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 9px; border-radius: 20px; font-size: 11.5px; font-weight: 500; white-space: nowrap;
}
.badge-requested { background: #fff3cd; color: #856404; }
.badge-approved  { background: var(--info-bg); color: #1565c0; }
.badge-rejected  { background: var(--danger-bg); color: #c62828; }
.badge-started   { background: #e3f2fd; color: #0d47a1; }
.badge-finished  { background: var(--success-bg); color: #1b5e20; }
.badge-flexible  { background: #f3e5f5; color: #6a1b9a; }
.badge-normal    { background: #e0f2f1; color: #004d40; }
.badge-urgent    { background: #fff3e0; color: #e65100; }
.badge-emergency { background: var(--danger-bg); color: #b71c1c; }

.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.page-header h2 { font-size: 20px; font-weight: 600; }

.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
th { padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; background: var(--surface); border-bottom: 1px solid var(--border); white-space: nowrap; }
td { padding: 12px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: rgba(0,0,0,0.015); }

.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 12.5px; font-weight: 500; color: var(--text-muted); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.3px; }
.form-label .required { color: var(--danger); }
.form-control {
  width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13.5px; color: var(--text); background: #fff;
  transition: border-color 0.15s, box-shadow 0.15s; outline: none;
}
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,60,94,0.1); }
select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b7a8d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 10px center; background-size: 12px; padding-right: 32px; }
textarea.form-control { resize: vertical; min-height: 80px; }

.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

.priority-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; }
.prio-flexible { background: #9b59b6; }
.prio-normal   { background: var(--info); }
.prio-urgent   { background: var(--warning); }
.prio-emergency{ background: var(--danger); }

.avatar-chip { display: inline-flex; align-items: center; gap: 7px; }
.mini-av {
  width: 26px; height: 26px; border-radius: 50%;
  background: var(--primary-light); color: var(--primary);
  font-size: 10px; font-weight: 700;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

.toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 999; display: flex; flex-direction: column; gap: 8px; }
.toast { background: var(--primary); color: white; padding: 12px 18px; border-radius: var(--radius); font-size: 13.5px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.2); animation: slideUp 0.3s ease; }
.toast.success { background: var(--success); }
.toast.error   { background: var(--danger); }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
</style>
</head>
<body>

<aside id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="ti ti-truck"></i></div>
    <div>
      <div class="logo-text">VehiclePool</div>
      <div class="logo-sub">Mining Transport</div>
    </div>
  </div>

  <nav class="sidebar-nav">

  {{-- MAIN: semua role bisa akses dashboard --}}
  <div class="sidebar-section">Main</div>
  <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="ti ti-layout-dashboard"></i>
    <span>Dashboard</span>
  </a>

  {{-- ADMINISTRATION: hanya manager --}}
  @if(auth()->user()->role_user === 'manager')
  <div class="sidebar-section">Administration</div>
  <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
    <i class="ti ti-users-group"></i>
    <span>Manage Users</span>
  </a>
  @endif

  {{-- TRANSPORTATION --}}
  @if(in_array(auth()->user()->role_user, ['manager', 'admin', 'admin_trans']))
  <div class="sidebar-section">Transportation</div>
  @endif

  {{-- Trip: manager & admin --}}
  @if(in_array(auth()->user()->role_user, ['manager', 'admin']))
  <a href="{{ route('trip.index') }}" class="nav-item {{ request()->routeIs('trip.*') ? 'active' : '' }}">
    <i class="ti ti-list"></i>
    <span>Trip</span>
  </a>
  @endif

  {{-- Approval: manager & admin_trans --}}
  @if(in_array(auth()->user()->role_user, ['manager', 'admin_trans']))
  <a href="{{ route('approval.index') }}" class="nav-item {{ request()->routeIs('approval.*') ? 'active' : '' }}">
    <i class="ti ti-file-check"></i>
    <span>Approval</span>
    @php $pendingCount = \App\Models\Trip::where('status','Requested')->count(); @endphp
    @if($pendingCount > 0)
      <span style="margin-left:auto;background:var(--accent);color:white;font-size:10px;padding:2px 6px;border-radius:10px;font-weight:600;">{{ $pendingCount }}</span>
    @endif
  </a>
  @endif

  {{-- Vehicle: manager & admin_trans --}}
  @if(in_array(auth()->user()->role_user, ['manager', 'admin_trans']))
  <a href="{{ route('vehicle.index') }}" class="nav-item {{ request()->routeIs('vehicle.*') ? 'active' : '' }}">
    <i class="ti ti-car"></i>
    <span>Vehicle</span>
  </a>
  @endif

</nav>

  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">
        {{ strtoupper(substr(auth()->user()->nama_user, 0, 2)) }}
      </div>
      <div class="user-info">
        <div class="user-name">{{ auth()->user()->nama_user }}</div>
        <div class="user-role">{{ ucfirst(auth()->user()->role_user) }}</div>
      </div>
    </div>
  </div>
</aside>

<div id="main">
  <div class="topbar">
    <div class="topbar-left">
      <h1>@yield('page-title', 'Dashboard')</h1>
      <div class="breadcrumb">@yield('breadcrumb', 'Home / Dashboard')</div>
    </div>
    <div class="topbar-right">
      <button class="topbar-btn" title="Settings">
        <i class="ti ti-settings"></i>
      </button>
      <div class="mini-avatar">
        {{ strtoupper(substr(auth()->user()->nama_user, 0, 2)) }}
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="topbar-btn" title="Logout" style="color:var(--danger);">
          <i class="ti ti-logout"></i>
        </button>
      </form>
    </div>
  </div>

  <div class="content-area">
    @yield('content')
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
function showToast(msg, type = 'info') {
  const c = document.getElementById('toastContainer');
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  const icons = { success: 'ti-circle-check', error: 'ti-alert-circle', info: 'ti-info-circle' };
  t.innerHTML = `<i class="ti ${icons[type]||'ti-info-circle'}" style="font-size:18px;"></i> ${msg}`;
  c.appendChild(t);
  setTimeout(() => t.remove(), 4000);
}

@if(session('success'))
  document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));
@endif
@if(session('error'))
  document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));
@endif
</script>

@stack('scripts')
</body>
</html>