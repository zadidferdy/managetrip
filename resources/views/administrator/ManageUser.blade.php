@extends('layouts.app')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')
@section('breadcrumb', 'Administrator / Manage Users')

@push('styles')
<style>
.role-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600;
}
.role-admin       { background: #fdecea; color: #c62828; }
.role-manager     { background: #fff8e1; color: #f57f17; }
.role-karyawan    { background: #e8f5e9; color: #2e7d32; }
.role-admin_trans { background: #e3f2fd; color: #1565c0; }
.role-driver      { background: #f3e5f5; color: #6a1b9a; }

.filter-bar {
  display: flex; gap: 10px; align-items: center;
  margin-bottom: 20px; flex-wrap: wrap;
}
.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-wrap i {
  position: absolute; left: 11px; top: 50%;
  transform: translateY(-50%); color: var(--text-muted); font-size: 16px; pointer-events: none;
}
.search-wrap input {
  width: 100%; padding: 9px 12px 9px 36px;
  border: 1px solid var(--border); border-radius: var(--radius);
  font-family: 'DM Sans', sans-serif; font-size: 13.5px; outline: none;
  transition: border-color 0.15s, box-shadow 0.15s;
}
.search-wrap input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(26,60,94,0.1);
}

/* Modal */
.modal-overlay {
  position: fixed; inset: 0;
  background: rgba(15,34,54,0.55); backdrop-filter: blur(2px);
  z-index: 200; display: flex; align-items: center; justify-content: center;
  padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.2s;
}
.modal-overlay.open { opacity: 1; pointer-events: all; }
.modal {
  background: var(--card); border-radius: var(--radius-lg);
  width: 100%; max-width: 460px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2);
  transform: translateY(20px); transition: transform 0.2s;
}
.modal-overlay.open .modal { transform: translateY(0); }
.modal-header {
  padding: 18px 22px 14px; border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
.modal-header h2 { font-size: 16px; font-weight: 600; }
.modal-close {
  width: 30px; height: 30px; border: none; background: transparent;
  border-radius: 6px; cursor: pointer; display: flex; align-items: center;
  justify-content: center; color: var(--text-muted); font-size: 18px;
}
.modal-close:hover { background: var(--surface); }
.modal-body  { padding: 20px 22px; }
.modal-footer {
  padding: 14px 22px 18px; border-top: 1px solid var(--border);
  display: flex; justify-content: flex-end; gap: 10px;
}
.modal-body .form-group { margin-bottom: 14px; }
.modal-body .form-group:last-child { margin-bottom: 0; }

.user-initials {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #3498db, #1a3c5e);
  color: white; font-size: 13px; font-weight: 700;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.empty-state { text-align: center; padding: 56px 20px; color: var(--text-muted); }
.empty-state i { font-size: 40px; margin-bottom: 10px; display: block; }

.stat-chips { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-chip {
  background: var(--card); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 10px 16px;
  display: flex; align-items: center; gap: 10px;
  font-size: 13px; box-shadow: var(--shadow);
}
.stat-chip .num { font-size: 20px; font-weight: 700; color: var(--text); line-height: 1; }
.stat-chip .lbl { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
.stat-chip i { font-size: 22px; }
</style>
@endpush

@section('content')

<div class="page-header">
  <h2>Manage Users</h2>
</div>

{{-- STAT CHIPS --}}
@php
  $allUsers    = \App\Models\User::all();
  $cAdmin      = $allUsers->where('role_user','admin')->count();
  $cManager    = $allUsers->where('role_user','manager')->count();
  $cKaryawan   = $allUsers->where('role_user','karyawan')->count();
  $cAdminTrans = $allUsers->where('role_user','admin_trans')->count();
  $cDriver     = $allUsers->where('role_user','driver')->count();
@endphp
<div class="stat-chips">
  <div class="stat-chip">
    <i class="ti ti-users" style="color:var(--primary);"></i>
    <div><div class="num">{{ $allUsers->count() }}</div><div class="lbl">Total User</div></div>
  </div>
  <div class="stat-chip">
    <i class="ti ti-shield-check" style="color:#c62828;"></i>
    <div><div class="num">{{ $cAdmin }}</div><div class="lbl">Admin</div></div>
  </div>
  <div class="stat-chip">
    <i class="ti ti-user-check" style="color:#f57f17;"></i>
    <div><div class="num">{{ $cManager }}</div><div class="lbl">Manager</div></div>
  </div>
  <div class="stat-chip">
    <i class="ti ti-user" style="color:#2e7d32;"></i>
    <div><div class="num">{{ $cKaryawan }}</div><div class="lbl">Karyawan</div></div>
  </div>
  <div class="stat-chip">
    <i class="ti ti-truck" style="color:#1565c0;"></i>
    <div><div class="num">{{ $cAdminTrans }}</div><div class="lbl">Admin Transportation</div></div>
  </div>
  <div class="stat-chip">
    <i class="ti ti-steering-wheel" style="color:#6a1b9a;"></i>
    <div><div class="num">{{ $cDriver }}</div><div class="lbl">Driver</div></div>
  </div>
</div>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('admin.users') }}">
  <div class="filter-bar">
    <div class="search-wrap">
      <i class="ti ti-search"></i>
      <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email...">
    </div>
    <select name="role" class="form-control" style="width:210px;" onchange="this.form.submit()">
      <option value="">Semua Role</option>
      <option value="admin"       {{ $role === 'admin'       ? 'selected' : '' }}>Admin</option>
      <option value="manager"     {{ $role === 'manager'     ? 'selected' : '' }}>Manager</option>
      <option value="karyawan"    {{ $role === 'karyawan'    ? 'selected' : '' }}>Karyawan</option>
      <option value="admin_trans" {{ $role === 'admin_trans' ? 'selected' : '' }}>Admin Transportation</option>
      <option value="driver"      {{ $role === 'driver'      ? 'selected' : '' }}>Driver</option>
    </select>
    <button type="submit" class="btn btn-primary">
      <i class="ti ti-search"></i> Cari
    </button>
    @if($search || $role)
      <a href="{{ route('admin.users') }}" class="btn btn-outline">
        <i class="ti ti-x"></i> Reset
      </a>
    @endif
  </div>
</form>

{{-- FLASH MESSAGES --}}
@if(session('success'))
  <div style="background:#e8f5e9;border:1px solid #a5d6a7;border-radius:var(--radius);padding:10px 14px;margin-bottom:16px;font-size:13px;color:#2e7d32;display:flex;align-items:center;gap:8px;">
    <i class="ti ti-circle-check" style="font-size:16px;"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div style="background:#fdecea;border:1px solid #f5c6c6;border-radius:var(--radius);padding:10px 14px;margin-bottom:16px;font-size:13px;color:#c62828;display:flex;align-items:center;gap:8px;">
    <i class="ti ti-alert-circle" style="font-size:16px;"></i> {{ session('error') }}
  </div>
@endif

{{-- TABLE --}}
<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Bergabung</th>
          <th style="text-align:center;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $i => $user)
        <tr>
          <td style="color:var(--text-muted);font-size:12px;">{{ $i + 1 }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="user-initials">{{ strtoupper(substr($user->nama_user, 0, 2)) }}</div>
              <div>
                <div style="font-weight:500;">{{ $user->nama_user }}</div>
                @if($user->id_user === auth()->user()->id_user)
                  <span style="font-size:10px;color:var(--accent);font-weight:600;">● Anda</span>
                @endif
              </div>
            </div>
          </td>
          <td style="color:var(--text-muted);">{{ $user->email_user }}</td>
          <td>
            <span class="role-badge role-{{ $user->role_user }}">
              @if($user->role_user === 'admin')
                <i class="ti ti-shield-check" style="font-size:12px;"></i> Admin
              @elseif($user->role_user === 'manager')
                <i class="ti ti-user-check" style="font-size:12px;"></i> Manager
              @elseif($user->role_user === 'admin_trans')
                <i class="ti ti-truck" style="font-size:12px;"></i> Admin Transportation
              @elseif($user->role_user === 'driver')
                <i class="ti ti-steering-wheel" style="font-size:12px;"></i> Driver
              @else
                <i class="ti ti-user" style="font-size:12px;"></i> Karyawan
              @endif
            </span>
          </td>
          <td style="color:var(--text-muted);font-size:12.5px;">
            {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
          </td>
          <td style="text-align:center;">
            <div style="display:flex;gap:6px;justify-content:center;">
              @php $isSelf = $user->id_user === auth()->user()->id_user; @endphp

              {{-- Tombol Edit: tampil untuk semua user lain, dan untuk diri sendiri jika manager --}}
              @if(!$isSelf || auth()->user()->role_user === 'manager')
                <button class="btn btn-outline btn-sm"
                  onclick="openEdit({{ $user->id_user }},'{{ addslashes($user->nama_user) }}','{{ addslashes($user->email_user) }}','{{ $user->role_user }}',{{ $isSelf ? 'true' : 'false' }})">
                  <i class="ti ti-edit"></i> Edit
                </button>
              @endif

              {{-- Tombol Hapus: tidak boleh hapus diri sendiri --}}
              @if(!$isSelf)
                <button class="btn btn-danger btn-sm"
                  onclick="openDelete({{ $user->id_user }},'{{ addslashes($user->nama_user) }}')">
                  <i class="ti ti-trash"></i>
                </button>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6">
            <div class="empty-state">
              <i class="ti ti-users-group"></i>
              <p>Tidak ada user ditemukan</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ══════════════ EDIT MODAL ══════════════ --}}
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <div class="modal-header">
      <h2><i class="ti ti-edit" style="color:var(--primary);margin-right:6px;"></i> Edit User</h2>
      <button class="modal-close" onclick="closeModal('editModal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="editForm" action="">
      @csrf
      <input type="hidden" name="_method" value="PATCH">
      <div class="modal-body">

        {{-- Nama --}}
        <div class="form-group">
          <label class="form-label">Nama <span style="color:var(--danger);">*</span></label>
          <input type="text" name="nama_user" id="editNama" class="form-control"
                 placeholder="Nama lengkap" required>
        </div>

        {{-- Email --}}
        <div class="form-group">
          <label class="form-label">Email <span style="color:var(--danger);">*</span></label>
          <input type="email" name="email_user" id="editEmail" class="form-control"
                 placeholder="Email" required>
        </div>

        {{-- Password --}}
        <div class="form-group">
          <label class="form-label">
            Password Baru
            <span style="color:var(--text-muted);font-weight:400;font-size:12px;">(kosongkan jika tidak diubah)</span>
          </label>
          <input type="password" name="password" id="editPassword" class="form-control"
                 placeholder="Password baru..." autocomplete="new-password">
        </div>

        {{-- Role: hanya tampil untuk manager --}}
        @if(auth()->user()->role_user === 'manager')
        <div class="form-group" id="roleGroup">
          <label class="form-label">Role <span style="color:var(--danger);">*</span></label>
          <select name="role_user" id="editRoleSelect" class="form-control" required>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="karyawan">Karyawan / Staff</option>
            <option value="admin_trans">Admin Transportation</option>
            <option value="driver">Driver</option>
          </select>
        </div>
        @endif

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ══════════════ DELETE MODAL ══════════════ --}}
<div class="modal-overlay" id="deleteModal">
  <div class="modal" style="max-width:400px;">
    <div class="modal-header">
      <h2><i class="ti ti-trash" style="color:var(--danger);margin-right:6px;"></i> Hapus Akun</h2>
      <button class="modal-close" onclick="closeModal('deleteModal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="deleteForm" action="">
      @csrf
      <input type="hidden" name="_method" value="DELETE">
      <div class="modal-body">
        <div style="background:#fdecea;border:1px solid #f5c6c6;border-radius:var(--radius);padding:12px;font-size:13px;color:#c62828;margin-bottom:14px;">
          <i class="ti ti-alert-triangle" style="font-size:15px;vertical-align:-2px;margin-right:6px;"></i>
          Tindakan ini tidak dapat dibatalkan!
        </div>
        <div id="deleteUserInfo" style="font-size:13.5px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('deleteModal')">Batal</button>
        <button type="submit" class="btn btn-danger"><i class="ti ti-trash"></i> Hapus</button>
      </div>
    </form>
  </div>
</div>

{{-- LOGOUT MODAL --}}
<div class="modal-overlay" id="logoutModal">
  <div class="modal" style="max-width:380px;">
    <div class="modal-header">
      <h2><i class="ti ti-logout" style="color:var(--danger);margin-right:6px;"></i> Logout</h2>
      <button class="modal-close" onclick="closeModal('logoutModal')"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <p style="font-size:13.5px;color:var(--text-muted);">Apakah Anda yakin ingin keluar dari sistem?</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeModal('logoutModal')">Batal</button>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger"><i class="ti ti-logout"></i> Logout</button>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// isManager dikirim dari Blade ke JS
var isManager = {{ auth()->user()->role_user === 'manager' ? 'true' : 'false' }};

function openEdit(id, nama, email, role, isSelf) {
  document.getElementById('editForm').action  = '/administrator/users/' + id;
  document.getElementById('editNama').value   = nama;
  document.getElementById('editEmail').value  = email;
  document.getElementById('editPassword').value = '';

  // Field role: tampil hanya jika manager dan bukan edit diri sendiri
  var roleGroup  = document.getElementById('roleGroup');
  var roleSelect = document.getElementById('editRoleSelect');
  if (roleGroup) {
    roleGroup.style.display = isSelf ? 'none' : 'block';
  }
  if (roleSelect) {
    roleSelect.value    = role;
    roleSelect.required = !isSelf; // tidak required jika disembunyikan
  }

  document.getElementById('editModal').classList.add('open');
}

function openDelete(id, nama) {
  document.getElementById('deleteForm').action = '/administrator/users/' + id;
  document.getElementById('deleteUserInfo').innerHTML =
    'Anda yakin ingin menghapus akun <strong>' + nama + '</strong>? Semua data terkait akan ikut terhapus.';
  document.getElementById('deleteModal').classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

document.querySelectorAll('.modal-overlay').forEach(function(o) {
  o.addEventListener('click', function(e) {
    if (e.target === o) o.classList.remove('open');
  });
});
</script>
@endpush