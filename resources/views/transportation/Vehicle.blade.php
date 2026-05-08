@extends('layouts.app')

@section('title', 'Daftar Kendaraan')
@section('page-title', 'Daftar Kendaraan')
@section('breadcrumb', 'Transportation / Vehicle')

@push('styles')
<style>
.filter-bar {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 14px 18px;
  margin-bottom: 20px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: flex-end;
}
.filter-bar .form-group { margin-bottom: 0; min-width: 140px; flex: 1; }
.filter-bar .form-label { font-size: 11px; margin-bottom: 4px; }
.filter-bar .form-control { padding: 7px 10px; font-size: 13px; }

.plate-mono {
  font-family: 'Space Mono', monospace;
  font-size: 12px;
  font-weight: 700;
  color: var(--primary);
  background: var(--primary-light);
  padding: 2px 8px;
  border-radius: 5px;
}

.btn-icon {
  width: 32px !important;
  height: 32px !important;
  padding: 0 !important;
  display: inline-flex !important;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

/* ── MODAL ── */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.55);
  backdrop-filter: blur(3px);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}
.modal-overlay.active { display: flex; }
.modal-box {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  width: 100%;
  max-width: 580px;
  margin: 16px;
  box-shadow: 0 20px 60px rgba(0,0,0,.35);
  animation: modalIn .2s ease;
  max-height: 90vh;
  overflow-y: auto;
}
@keyframes modalIn {
  from { opacity:0; transform:translateY(16px) scale(.97); }
  to   { opacity:1; transform:translateY(0) scale(1); }
}
.modal-header {
  padding: 18px 20px 14px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  gap: 10px;
  position: sticky;
  top: 0;
  background: var(--card);
  z-index: 1;
}
.modal-header .modal-icon {
  width: 38px; height: 38px;
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.modal-header h4 { margin: 0; font-size: 15px; font-weight: 700; }
.modal-header .plate-badge {
  font-family: 'Space Mono', monospace;
  font-size: 11px;
  font-weight: 700;
  color: var(--primary);
  background: color-mix(in srgb, var(--primary) 12%, transparent);
  padding: 2px 8px;
  border-radius: 4px;
  margin-top: 2px;
  display: inline-block;
}
.modal-footer {
  padding: 14px 20px;
  border-top: 1px solid var(--border);
  display: flex;
  justify-content: flex-end;
  gap: 8px;
  position: sticky;
  bottom: 0;
  background: var(--card);
}

/* Detail sections */
.detail-section {
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
}
.detail-section:last-child { border-bottom: none; }
.detail-section-title {
  font-size: 11px;
  font-family: 'Space Mono', monospace;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 12px;
}
.detail-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}
.detail-grid-3 {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 12px;
}
.detail-item-label {
  font-size: 11px;
  color: var(--text-muted);
  margin-bottom: 3px;
}
.detail-item-value {
  font-size: 13.5px;
  font-weight: 500;
  line-height: 1.5;
}
</style>
@endpush

@section('content')

<div class="page-header">
  <h2>Daftar Kendaraan</h2>
  <a href="{{ route('vehicle.create') }}" class="btn btn-primary">
    <i class="ti ti-plus"></i> Tambah Kendaraan
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success" style="background:var(--success-bg);border:1px solid #a8d5b5;border-radius:var(--radius);padding:12px 16px;margin-bottom:16px;font-size:13px;color:#1b5e20;">
    <i class="ti ti-circle-check" style="font-size:15px;vertical-align:-2px;margin-right:6px;"></i>{{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger" style="background:var(--danger-bg);border:1px solid #f5c6c6;border-radius:var(--radius);padding:12px 16px;margin-bottom:16px;font-size:13px;color:#c62828;">
    <i class="ti ti-alert-triangle" style="font-size:15px;vertical-align:-2px;margin-right:6px;"></i>{{ session('error') }}
  </div>
@endif

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('vehicle.index') }}" class="filter-bar">
  <div class="form-group">
    <label class="form-label">Cari</label>
    <input type="text" name="search" class="form-control"
      placeholder="Nama / Plat / Brand..." value="{{ request('search') }}">
  </div>
  <div class="form-group" style="min-width:140px;flex:0;">
    <label class="form-label">Tipe</label>
    <select name="type" class="form-control">
      <option value="">Semua</option>
      @foreach(['Angkutan Orang','Angkutan Barang'] as $t)
        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group" style="min-width:140px;flex:0;">
    <label class="form-label">Kepemilikan</label>
    <select name="ownership" class="form-control">
      <option value="">Semua</option>
      @foreach(['Milik Perusahaan','Sewa'] as $o)
        <option value="{{ $o }}" {{ request('ownership') == $o ? 'selected' : '' }}>{{ $o }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group" style="min-width:140px;flex:0;">
    <label class="form-label">Status</label>
    <select name="status" class="form-control">
      <option value="">Semua</option>
      @foreach(['Aktif','Dalam Perbaikan','Tidak Aktif'] as $s)
        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
      @endforeach
    </select>
  </div>
  <div style="display:flex;gap:8px;">
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="ti ti-filter"></i> Filter
    </button>
    <a href="{{ route('vehicle.index') }}" class="btn btn-outline btn-sm">
      <i class="ti ti-refresh"></i>
    </a>
  </div>
</form>

{{-- TABLE --}}
<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Plat</th>
          <th>Nama Kendaraan</th>
          <th>Brand</th>
          <th>Tahun</th>
          <th>Tipe</th>
          <th>Kepemilikan</th>
          <th>Perusahaan Sewa</th>
          <th>Servis Terakhir</th>
          <th>KM Servis</th>
          <th>Status</th>
          <th style="width:120px;text-align:center;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($vehicles as $v)
        @php
          $statusMap = [
            'Aktif'           => 'badge-approved',
            'Dalam Perbaikan' => 'badge-started',
            'Tidak Aktif'     => 'badge-rejected',
          ];
        @endphp
        <tr>
          <td><span class="plate-mono">{{ $v->plate_number }}</span></td>
          <td style="font-weight:500;">{{ $v->name }}</td>
          <td>{{ $v->brand }}</td>
          <td>{{ $v->year }}</td>
          <td>
            <span class="badge {{ $v->type === 'Angkutan Orang' ? 'badge-normal' : 'badge-flexible' }}">
              {{ $v->type }}
            </span>
          </td>
          <td>
            <span class="badge {{ $v->ownership === 'Milik Perusahaan' ? 'badge-approved' : 'badge-flexible' }}">
              {{ $v->ownership }}
            </span>
          </td>
          <td style="font-size:12.5px;">
            @if($v->ownership === 'Sewa')
              <div>{{ $v->rental_company ?? '—' }}</div>
              @if($v->rental_start && $v->rental_end)
                <div style="font-size:11px;color:var(--text-muted);">
                  {{ $v->rental_start->format('d M Y') }} – {{ $v->rental_end->format('d M Y') }}
                </div>
              @endif
            @else
              <span style="color:var(--text-muted);">—</span>
            @endif
          </td>
          <td style="font-size:12.5px;">
            {{ $v->last_service_date ? $v->last_service_date->format('d M Y') : '—' }}
          </td>
          <td style="font-size:12.5px;">
            {{ $v->last_service_km ? number_format($v->last_service_km) . ' km' : '—' }}
          </td>
          <td>
            <span class="badge {{ $statusMap[$v->status] ?? 'badge-requested' }}">
              {{ $v->status }}
            </span>
          </td>
          <td style="width:120px;white-space:nowrap;">
            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">

              {{-- DETAIL --}}
              <button type="button" class="btn btn-outline btn-sm btn-icon" title="Detail"
                onclick="openVehicleDetail({{ $v->id }})">
                <i class="ti ti-eye"></i>
              </button>

              {{-- EDIT --}}
              <a href="{{ route('vehicle.edit', $v->id) }}"
                 class="btn btn-outline btn-sm btn-icon" title="Edit">
                <i class="ti ti-pencil"></i>
              </a>

              {{-- HAPUS --}}
              <form method="POST" action="{{ route('vehicle.destroy', $v->id) }}"
                    style="margin:0;"
                    onsubmit="return confirm('Hapus kendaraan {{ $v->name }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                  <i class="ti ti-trash"></i>
                </button>
              </form>

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="11" style="text-align:center;padding:40px;color:var(--text-muted);">
            <i class="ti ti-car-off" style="font-size:32px;display:block;margin-bottom:8px;"></i>
            Tidak ada data kendaraan
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($vehicles->hasPages())
  <div style="padding:14px 18px;border-top:1px solid var(--border);">
    {{ $vehicles->appends(request()->query())->links() }}
  </div>
  @endif
</div>

{{-- ═══════════════════ DETAIL MODAL ═══════════════════ --}}
<div class="modal-overlay" id="vehicleDetailModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-icon" style="background:color-mix(in srgb, var(--primary) 15%, transparent);">
        <i class="ti ti-car" style="color:var(--primary);font-size:20px;"></i>
      </div>
      <div>
        <h4>Detail Kendaraan</h4>
        <span class="plate-badge" id="vd-plate-badge">—</span>
      </div>
      <button type="button" onclick="closeVehicleDetail()"
        style="margin-left:auto;background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:20px;line-height:1;padding:4px;">
        <i class="ti ti-x"></i>
      </button>
    </div>

    {{-- Loading --}}
    <div id="vd-loading" style="padding:40px;text-align:center;color:var(--text-muted);">
      <i class="ti ti-loader-2" style="font-size:28px;display:block;margin-bottom:8px;animation:spin 1s linear infinite;"></i>
      Memuat data...
    </div>

    {{-- Content --}}
    <div id="vd-content" style="display:none;">

      {{-- Identitas --}}
      <div class="detail-section">
        <div class="detail-section-title">Identitas Kendaraan</div>
        <div class="detail-grid">
          <div>
            <div class="detail-item-label">Nomor Plat</div>
            <div class="detail-item-value" id="vd-plate"></div>
          </div>
          <div>
            <div class="detail-item-label">Nama Kendaraan</div>
            <div class="detail-item-value" id="vd-name"></div>
          </div>
          <div>
            <div class="detail-item-label">Brand</div>
            <div class="detail-item-value" id="vd-brand"></div>
          </div>
          <div>
            <div class="detail-item-label">Tahun</div>
            <div class="detail-item-value" id="vd-year"></div>
          </div>
          <div>
            <div class="detail-item-label">Tipe</div>
            <div class="detail-item-value" id="vd-type"></div>
          </div>
          <div>
            <div class="detail-item-label">Status</div>
            <div class="detail-item-value" id="vd-status"></div>
          </div>
        </div>
      </div>

      {{-- Kepemilikan --}}
      <div class="detail-section">
        <div class="detail-section-title">Kepemilikan</div>
        <div class="detail-grid">
          <div>
            <div class="detail-item-label">Status Kepemilikan</div>
            <div class="detail-item-value" id="vd-ownership"></div>
          </div>
          <div>
            <div class="detail-item-label">Perusahaan Sewa</div>
            <div class="detail-item-value" id="vd-rental-company"></div>
          </div>
          <div>
            <div class="detail-item-label">Mulai Sewa</div>
            <div class="detail-item-value" id="vd-rental-start"></div>
          </div>
          <div>
            <div class="detail-item-label">Akhir Sewa</div>
            <div class="detail-item-value" id="vd-rental-end"></div>
          </div>
        </div>
      </div>

      {{-- Servis --}}
      <div class="detail-section">
        <div class="detail-section-title">Data Servis</div>
        <div class="detail-grid">
          <div>
            <div class="detail-item-label">Tanggal Servis Terakhir</div>
            <div class="detail-item-value" id="vd-service-date"></div>
          </div>
          <div>
            <div class="detail-item-label">KM Saat Servis</div>
            <div class="detail-item-value" id="vd-service-km"></div>
          </div>
        </div>
      </div>

      {{-- Catatan --}}
      <div class="detail-section">
        <div class="detail-section-title">Catatan</div>
        <div class="detail-item-value" id="vd-notes" style="font-weight:400;color:var(--text-muted);"></div>
      </div>

    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeVehicleDetail()">Tutup</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openVehicleDetail(vehicleId) {
  document.getElementById('vehicleDetailModal').classList.add('active');
  document.getElementById('vd-loading').style.display = 'block';
  document.getElementById('vd-content').style.display = 'none';

  fetch(`/transportation/vehicles/${vehicleId}/detail`)
    .then(r => r.json())
    .then(d => {
      document.getElementById('vd-plate-badge').textContent      = d.plate_number;
      document.getElementById('vd-plate').textContent            = d.plate_number;
      document.getElementById('vd-name').textContent             = d.name;
      document.getElementById('vd-brand').textContent            = d.brand;
      document.getElementById('vd-year').textContent             = d.year;
      document.getElementById('vd-type').textContent             = d.type;
      document.getElementById('vd-status').textContent           = d.status;
      document.getElementById('vd-ownership').textContent        = d.ownership;
      document.getElementById('vd-rental-company').textContent   = d.rental_company;
      document.getElementById('vd-rental-start').textContent     = d.rental_start;
      document.getElementById('vd-rental-end').textContent       = d.rental_end;
      document.getElementById('vd-service-date').textContent     = d.last_service_date;
      document.getElementById('vd-service-km').textContent       = d.last_service_km;
      document.getElementById('vd-notes').textContent            = d.notes;

      document.getElementById('vd-loading').style.display = 'none';
      document.getElementById('vd-content').style.display = 'block';
    });
}

function closeVehicleDetail() {
  document.getElementById('vehicleDetailModal').classList.remove('active');
}

document.getElementById('vehicleDetailModal').addEventListener('click', function(e) {
  if (e.target === this) closeVehicleDetail();
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeVehicleDetail();
});
</script>
@endpush

@endsection