@extends('layouts.app')

@section('title', 'Daftar Trip')
@section('page-title', 'Daftar Trip')
@section('breadcrumb', 'Transportation / Trip')

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

.trip-id-mono {
  font-family: 'Space Mono', monospace;
  font-size: 11.5px;
  font-weight: 700;
  color: var(--primary);
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

/* ── MODALS ── */
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
  max-width: 480px;
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
.modal-header .trip-badge {
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
.modal-body { padding: 20px; display: flex; flex-direction: column; gap: 14px; }
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
.km-summary {
  background: var(--surface);
  border-radius: var(--radius);
  padding: 10px 14px;
  font-size: 12.5px;
  color: var(--text-muted);
  display: flex;
  align-items: center;
  gap: 8px;
  min-height: 38px;
}
.km-summary strong { color: var(--text); }
.input-with-unit { position: relative; }
.input-with-unit input { padding-right: 48px; }
.input-with-unit .unit-label {
  position: absolute; right: 12px; top: 50%;
  transform: translateY(-50%);
  font-size: 12px; color: var(--text-muted); font-weight: 600;
  pointer-events: none;
}

/* Detail grid */
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
  <h2>Daftar Trip</h2>
  <a href="{{ route('trip.create') }}" class="btn btn-primary">
    <i class="ti ti-plus"></i> Buat Trip Baru
  </a>
</div>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('trip.index') }}" class="filter-bar">
  <div class="form-group">
    <label class="form-label">Cari</label>
    <input type="text" name="search" class="form-control"
      placeholder="Kode / Dari / Tujuan..." value="{{ request('search') }}">
  </div>
  <div class="form-group" style="min-width:130px;flex:0;">
    <label class="form-label">Status</label>
    <select name="status" class="form-control">
      <option value="">Semua</option>
      @foreach(['Requested','Approved','Rejected','Start','Finished'] as $s)
        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group" style="min-width:130px;flex:0;">
    <label class="form-label">Prioritas</label>
    <select name="priority" class="form-control">
      <option value="">Semua</option>
      @foreach(['Flexible','Normal','Urgent','Emergency'] as $p)
        <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ $p }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group" style="min-width:130px;flex:0;">
    <label class="form-label">Dari Tanggal</label>
    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
  </div>
  <div class="form-group" style="min-width:130px;flex:0;">
    <label class="form-label">Sampai Tanggal</label>
    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
  </div>
  <div style="display:flex;gap:8px;">
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="ti ti-filter"></i> Filter
    </button>
    <a href="{{ route('trip.index') }}" class="btn btn-outline btn-sm">
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
          <th>Trip ID</th>
          <th>Dari</th>
          <th>Tujuan</th>
          <th>Tanggal Berangkat</th>
          <th>Driver</th>
          <th>Kendaraan</th>
          <th>Prioritas</th>
          <th>Status</th>
          <th style="width:140px;text-align:center;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($trips as $t)
        @php
          $prioMap   = ['Flexible'=>'badge-flexible','Normal'=>'badge-normal','Urgent'=>'badge-urgent','Emergency'=>'badge-emergency'];
          $dotMap    = ['Flexible'=>'prio-flexible','Normal'=>'prio-normal','Urgent'=>'prio-urgent','Emergency'=>'prio-emergency'];
          $statusMap = ['Requested'=>'badge-requested','Approved'=>'badge-approved','Rejected'=>'badge-rejected','Start'=>'badge-started','Finished'=>'badge-finished'];
        @endphp
        <tr>
          <td><span class="trip-id-mono">{{ $t->trip_code }}</span></td>
          <td>{{ $t->from_location }}</td>
          <td>{{ $t->to_location }}</td>
          <td style="font-size:12.5px;">
            {{ \Carbon\Carbon::parse($t->departure_datetime)->format('d M Y H:i') }}
          </td>
          <td>
            @if($t->driver)
              <div class="avatar-chip">
                <div class="mini-av">{{ strtoupper(substr($t->driver,0,2)) }}</div>
                <span style="font-size:12.5px;">{{ $t->driver }}</span>
              </div>
            @else
              <span style="color:var(--text-muted);">—</span>
            @endif
          </td>
          <td style="font-size:12.5px;">
            @if($t->vehicleRel)
              {{ $t->vehicleRel->name }} — {{ $t->vehicleRel->plate_number }}
            @else
              <span style="color:var(--text-muted);">—</span>
            @endif
          </td>
          <td>
            <span class="badge {{ $prioMap[$t->priority] ?? 'badge-normal' }}">
              <span class="priority-dot {{ $dotMap[$t->priority] ?? '' }}"></span>
              {{ $t->priority }}
            </span>
          </td>
          <td>
            <span class="badge {{ $statusMap[$t->status] ?? 'badge-requested' }}">
              {{ $t->status }}
            </span>
          </td>

          {{-- AKSI --}}
          <td style="width:140px;white-space:nowrap;">
            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">

              {{-- DETAIL --}}
              <button type="button" class="btn btn-outline btn-sm btn-icon" title="Detail"
                onclick="openDetailModal({{ $t->id }})">
                <i class="ti ti-eye"></i>
              </button>

              {{-- EDIT --}}
              <a href="{{ route('trip.edit', $t->id) }}"
                 class="btn btn-outline btn-sm btn-icon" title="Edit">
                <i class="ti ti-pencil"></i>
              </a>

              {{-- START — hanya Approved --}}
              @if($t->status === 'Approved')
                <form method="POST" action="{{ route('trip.start', $t->id) }}" style="margin:0;">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-primary btn-sm btn-icon" title="Mulai Trip">
                    <i class="ti ti-player-play"></i>
                  </button>
                </form>
              @endif

              {{-- SELESAI — hanya Start --}}
              @if($t->status === 'Start')
                <button type="button" class="btn btn-success btn-sm btn-icon" title="Selesaikan Trip"
                  onclick="openFinishModal({{ $t->id }}, '{{ $t->trip_code }}', {{ $t->km_start ?? 'null' }})">
                  <i class="ti ti-flag"></i>
                </button>
              @endif

              {{-- HAPUS — selalu tampil --}}
              <form method="POST" action="{{ route('trip.destroy', $t->id) }}"
                    style="margin:0;"
                    onsubmit="return confirm('Hapus trip {{ $t->trip_code }}?')">
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
          <td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted);">
            <i class="ti ti-inbox" style="font-size:32px;display:block;margin-bottom:8px;"></i>
            Tidak ada data trip
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($trips->hasPages())
  <div style="padding:14px 18px;border-top:1px solid var(--border);">
    {{ $trips->appends(request()->query())->links() }}
  </div>
  @endif
</div>

{{-- ═══════════════════ DETAIL MODAL ═══════════════════ --}}
<div class="modal-overlay" id="detailModal">
  <div class="modal-box" style="max-width:600px;">
    <div class="modal-header">
      <div class="modal-icon" style="background:color-mix(in srgb, var(--primary) 15%, transparent);">
        <i class="ti ti-file-description" style="color:var(--primary);font-size:20px;"></i>
      </div>
      <div>
        <h4>Detail Trip</h4>
        <span class="trip-badge" id="detailTripCode">—</span>
      </div>
      <button type="button" onclick="closeDetailModal()"
        style="margin-left:auto;background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:20px;line-height:1;padding:4px;">
        <i class="ti ti-x"></i>
      </button>
    </div>

    {{-- Loading --}}
    <div id="detailLoading" style="padding:40px;text-align:center;color:var(--text-muted);">
      <i class="ti ti-loader-2" style="font-size:28px;display:block;margin-bottom:8px;animation:spin 1s linear infinite;"></i>
      Memuat data...
    </div>

    {{-- Content --}}
    <div id="detailContent" style="display:none;">

      <div class="detail-section">
        <div class="detail-section-title">Informasi Perjalanan</div>
        <div class="detail-grid">
          <div>
            <div class="detail-item-label">Dari</div>
            <div class="detail-item-value" id="d-from"></div>
          </div>
          <div>
            <div class="detail-item-label">Tujuan</div>
            <div class="detail-item-value" id="d-to"></div>
          </div>
          <div>
            <div class="detail-item-label">Tanggal Berangkat</div>
            <div class="detail-item-value" id="d-depart"></div>
          </div>
          <div>
            <div class="detail-item-label">Estimasi Kembali</div>
            <div class="detail-item-value" id="d-arrival"></div>
          </div>
          <div>
            <div class="detail-item-label">Jenis Trip</div>
            <div class="detail-item-value" id="d-type"></div>
          </div>
          <div>
            <div class="detail-item-label">Region</div>
            <div class="detail-item-value" id="d-region"></div>
          </div>
          <div>
            <div class="detail-item-label">Prioritas</div>
            <div class="detail-item-value" id="d-priority"></div>
          </div>
          <div>
            <div class="detail-item-label">Status</div>
            <div class="detail-item-value" id="d-status"></div>
          </div>
        </div>
      </div>

      <div class="detail-section">
        <div class="detail-section-title">Kendaraan & Driver</div>
        <div class="detail-grid">
          <div>
            <div class="detail-item-label">Kendaraan</div>
            <div class="detail-item-value" id="d-vehicle"></div>
          </div>
          <div>
            <div class="detail-item-label">Driver</div>
            <div class="detail-item-value" id="d-driver"></div>
          </div>
          <div>
            <div class="detail-item-label">KM Awal</div>
            <div class="detail-item-value" id="d-km-start"></div>
          </div>
          <div>
            <div class="detail-item-label">KM Akhir</div>
            <div class="detail-item-value" id="d-km-end"></div>
          </div>
          <div>
            <div class="detail-item-label">Jarak Tempuh</div>
            <div class="detail-item-value" id="d-km-used"></div>
          </div>
          <div>
            <div class="detail-item-label">BBM Digunakan</div>
            <div class="detail-item-value" id="d-fuel"></div>
          </div>
        </div>
      </div>

      <div class="detail-section">
        <div class="detail-section-title">Persetujuan</div>
        <div class="detail-grid">
          <div>
            <div class="detail-item-label">Approver Level 1</div>
            <div class="detail-item-value" id="d-approver1"></div>
          </div>
          <div>
            <div class="detail-item-label">Approver Level 2</div>
            <div class="detail-item-value" id="d-approver2"></div>
          </div>
        </div>
      </div>

      <div class="detail-section">
        <div class="detail-section-title">Keperluan & Catatan</div>
        <div style="margin-bottom:12px;">
          <div class="detail-item-label">Tujuan Perjalanan</div>
          <div class="detail-item-value" id="d-purpose"></div>
        </div>
        <div>
          <div class="detail-item-label">Catatan</div>
          <div class="detail-item-value" id="d-notes"></div>
        </div>
      </div>

    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeDetailModal()">Tutup</button>
    </div>
  </div>
</div>

{{-- ═══════════════════ FINISH MODAL ═══════════════════ --}}
<div class="modal-overlay" id="finishModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-icon" style="background:color-mix(in srgb, var(--success) 15%, transparent);">
        <i class="ti ti-flag" style="color:var(--success);font-size:20px;"></i>
      </div>
      <div>
        <h4>Selesaikan Trip</h4>
        <span class="trip-badge" id="modalTripCode">—</span>
      </div>
    </div>

    <form method="POST" id="finishForm">
      @csrf @method('PATCH')
      <div class="modal-body">

        <div class="km-summary">
          <i class="ti ti-speedometer" style="color:var(--accent);flex-shrink:0;"></i>
          <span>KM Awal: <strong id="kmStartVal">—</strong></span>
        </div>

        <div class="form-group" style="margin-bottom:0;">
          <label class="form-label">KM Akhir (Odometer) <span class="required">*</span></label>
          <div class="input-with-unit">
            <input type="number" name="km_end" id="kmEndInput"
              class="form-control" placeholder="Cth: 12850"
              min="0" required oninput="recalcKm()">
            <span class="unit-label">km</span>
          </div>
          <div id="kmUsedPreview" style="font-size:12px;color:var(--text-muted);margin-top:4px;"></div>
        </div>

        <div class="form-group" style="margin-bottom:0;">
          <label class="form-label">BBM Digunakan <span class="required">*</span></label>
          <div class="input-with-unit">
            <input type="number" name="fuel_used" id="fuelUsedInput"
              class="form-control" placeholder="Cth: 25.5" min="0" step="0.1" required>
            <span class="unit-label">liter</span>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:0;">
          <label class="form-label">Jumlah Penumpang</label>
          <input type="number" name="passengers"
            class="form-control" placeholder="Opsional" min="0">
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeFinishModal()">Batal</button>
        <button type="submit" class="btn btn-success">
          <i class="ti ti-flag"></i> Konfirmasi Selesai
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
// ── DETAIL MODAL ──────────────────────────────────────
function openDetailModal(tripId) {
  document.getElementById('detailModal').classList.add('active');
  document.getElementById('detailLoading').style.display = 'block';
  document.getElementById('detailContent').style.display = 'none';

  fetch(`/transportation/trip/${tripId}/detail`)
    .then(r => r.json())
    .then(d => {
      document.getElementById('detailTripCode').textContent  = d.trip_code;
      document.getElementById('d-from').textContent         = d.from;
      document.getElementById('d-to').textContent           = d.to;
      document.getElementById('d-depart').textContent       = d.depart;
      document.getElementById('d-arrival').textContent      = d.arrival;
      document.getElementById('d-type').textContent         = d.type;
      document.getElementById('d-region').textContent       = d.region;
      document.getElementById('d-priority').textContent     = d.priority;
      document.getElementById('d-status').textContent       = d.status;
      document.getElementById('d-vehicle').textContent      = d.vehicle;
      document.getElementById('d-driver').textContent       = d.driver;
      document.getElementById('d-km-start').textContent     = d.km_start;
      document.getElementById('d-km-end').textContent       = d.km_end;
      document.getElementById('d-km-used').textContent      = d.km_used;
      document.getElementById('d-fuel').textContent         = d.fuel;
      document.getElementById('d-approver1').textContent    = d.approver1;
      document.getElementById('d-approver2').textContent    = d.approver2;
      document.getElementById('d-purpose').textContent      = d.purpose;
      document.getElementById('d-notes').textContent        = d.notes;

      document.getElementById('detailLoading').style.display = 'none';
      document.getElementById('detailContent').style.display = 'block';
    });
}

function closeDetailModal() {
  document.getElementById('detailModal').classList.remove('active');
}

document.getElementById('detailModal').addEventListener('click', function(e) {
  if (e.target === this) closeDetailModal();
});

// ── FINISH MODAL ──────────────────────────────────────
let _kmStart = null;

function openFinishModal(tripId, tripCode, kmStart) {
  _kmStart = kmStart;
  document.getElementById('modalTripCode').textContent = tripCode;
  document.getElementById('finishForm').action = `/transportation/trip/${tripId}/finish`;

  document.getElementById('kmStartVal').textContent = kmStart !== null
    ? kmStart.toLocaleString('id') + ' km' : 'Tidak dicatat';

  document.getElementById('kmEndInput').value    = '';
  document.getElementById('fuelUsedInput').value = '';
  document.querySelector('[name="passengers"]').value = '';
  document.getElementById('kmUsedPreview').textContent = '';

  if (kmStart !== null) document.getElementById('kmEndInput').min = kmStart;

  document.getElementById('finishModal').classList.add('active');
  document.getElementById('kmEndInput').focus();
}

function closeFinishModal() {
  document.getElementById('finishModal').classList.remove('active');
}

function recalcKm() {
  const kmEnd   = parseInt(document.getElementById('kmEndInput').value);
  const preview = document.getElementById('kmUsedPreview');
  if (_kmStart !== null && !isNaN(kmEnd) && kmEnd >= _kmStart) {
    const used = kmEnd - _kmStart;
    preview.innerHTML = `<i class="ti ti-route" style="color:var(--accent);"></i> Jarak tempuh: <strong>${used.toLocaleString('id')} km</strong>`;
  } else {
    preview.textContent = '';
  }
}

document.getElementById('finishModal').addEventListener('click', function(e) {
  if (e.target === this) closeFinishModal();
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeDetailModal();
    closeFinishModal();
  }
});
</script>
@endpush

@endsection