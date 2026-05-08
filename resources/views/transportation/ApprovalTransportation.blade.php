@extends('layouts.app')

@section('title', 'Persetujuan Trip')
@section('page-title', 'Persetujuan Trip')
@section('breadcrumb', 'Transportation / Approval')

@push('styles')
<style>
.tabs { display: flex; gap: 4px; margin-bottom: 20px; border-bottom: 1px solid var(--border); }
.tab-btn { padding: 9px 18px; border: none; background: transparent; cursor: pointer; font-size: 13.5px; font-family: 'DM Sans', sans-serif; color: var(--text-muted); border-bottom: 2px solid transparent; margin-bottom: -1px; transition: color 0.15s; font-weight: 500; }
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-btn:hover:not(.active) { color: var(--text); }
.tab-count { background: var(--accent); color: white; border-radius: 10px; padding: 1px 7px; font-size: 11px; margin-left: 4px; }

.approval-card { background: var(--card); border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 14px; overflow: hidden; transition: box-shadow 0.2s; }
.approval-card:hover { box-shadow: var(--shadow); }
.approval-header { padding: 13px 18px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); background: var(--surface); gap: 12px; flex-wrap: wrap; }
.approval-body { padding: 16px 18px; }
.approval-footer { padding: 12px 18px; background: var(--surface); border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
.trip-id { font-family: 'Space Mono', monospace; font-size: 12.5px; font-weight: 700; color: var(--primary); background: var(--primary-light); padding: 3px 9px; border-radius: 6px; }
.info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 12px; }
.info-item .il { font-size: 10.5px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 3px; }
.info-item .iv { font-size: 13.5px; font-weight: 500; }

.level-dot { width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: white; }
.level-dot.done    { background: var(--success); }
.level-dot.pending { background: var(--warning); }
.level-dot.waiting { background: var(--border); color: var(--text-muted); }
.level-line { width: 28px; height: 2px; background: var(--border); }
.level-line.done { background: var(--success); }

/* Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(15,34,54,0.55); backdrop-filter: blur(2px); z-index: 200; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.2s; }
.modal-overlay.open { opacity: 1; pointer-events: all; }
.modal { background: var(--card); border-radius: var(--radius-lg); width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.25); transform: translateY(20px); transition: transform 0.2s; }
.modal-overlay.open .modal { transform: translateY(0); }
.modal-header { padding: 18px 22px 14px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.modal-header h2 { font-size: 16px; font-weight: 600; }
.modal-close { width: 30px; height: 30px; border: none; background: transparent; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 18px; }
.modal-close:hover { background: var(--surface); }
.modal-body { padding: 20px 22px; }
.modal-footer { padding: 14px 22px 18px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }

.empty-state { text-align: center; padding: 56px 20px; color: var(--text-muted); }
.empty-state i { font-size: 40px; margin-bottom: 10px; display: block; }
</style>
@endpush

@section('content')

<div class="page-header">
  <h2>Daftar Persetujuan</h2>
  <div style="font-size:13px;color:var(--text-muted);">
    <i class="ti ti-info-circle" style="font-size:15px;vertical-align:-2px;"></i>
    Diperlukan minimal 2 level persetujuan
  </div>
</div>

{{-- TABS --}}
<div class="tabs">
  <button class="tab-btn {{ $tab === 'pending'  ? 'active' : '' }}" onclick="switchTab('pending')">
    Menunggu
    @if($pendingCount > 0)<span class="tab-count">{{ $pendingCount }}</span>@endif
  </button>
  <button class="tab-btn {{ $tab === 'approved' ? 'active' : '' }}" onclick="switchTab('approved')">Disetujui</button>
  <button class="tab-btn {{ $tab === 'rejected' ? 'active' : '' }}" onclick="switchTab('rejected')">Ditolak</button>
  <button class="tab-btn {{ $tab === 'all'      ? 'active' : '' }}" onclick="switchTab('all')">Semua</button>
</div>

<input type="hidden" id="activeTab" value="{{ $tab }}">

{{-- APPROVAL LIST --}}
@forelse($trips as $t)
@php
  $prioMap   = ['Flexible'=>'badge-flexible','Normal'=>'badge-normal','Urgent'=>'badge-urgent','Emergency'=>'badge-emergency'];
  $dotMap    = ['Flexible'=>'prio-flexible','Normal'=>'prio-normal','Urgent'=>'prio-urgent','Emergency'=>'prio-emergency'];
  $statusMap = ['Requested'=>'badge-requested','Approved'=>'badge-approved','Rejected'=>'badge-rejected','Start'=>'badge-started','Finished'=>'badge-finished'];
  $l = $t->approval_level;
@endphp
<div class="approval-card">
  <div class="approval-header">
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
      <span class="trip-id">{{ $t->trip_code }}</span>
      <span class="badge {{ $statusMap[$t->status] ?? 'badge-requested' }}">{{ $t->status }}</span>
      <span class="badge {{ $prioMap[$t->priority] ?? 'badge-normal' }}">
        <span class="priority-dot {{ $dotMap[$t->priority] ?? '' }}"></span>{{ $t->priority }}
      </span>
    </div>
    {{-- Level indicator --}}
    <div style="display:flex;align-items:center;gap:4px;">
      <div class="level-dot done" title="Submitted">✓</div>
      <div class="level-line {{ $l >= 1 ? 'done' : '' }}"></div>
      <div class="level-dot {{ $l >= 1 ? 'done' : 'pending' }}" title="Level 1 - {{ $t->approver1 }}">{{ $l >= 1 ? '✓' : '1' }}</div>
      <div class="level-line {{ $l >= 2 ? 'done' : '' }}"></div>
      <div class="level-dot {{ $l >= 2 ? 'done' : 'waiting' }}" title="Level 2 - {{ $t->approver2 }}">{{ $l >= 2 ? '✓' : '2' }}</div>
      <span style="font-size:11px;color:var(--text-muted);margin-left:4px;">Lv.{{ $l }}/2</span>
    </div>
  </div>

  <div class="approval-body">
    <div class="info-grid">
      <div class="info-item">
        <div class="il"><i class="ti ti-map-pin" style="font-size:11px;"></i> Dari</div>
        <div class="iv">{{ $t->from_location }}</div>
      </div>
      <div class="info-item">
        <div class="il"><i class="ti ti-navigation" style="font-size:11px;"></i> Tujuan</div>
        <div class="iv">{{ $t->to_location }}</div>
      </div>
      <div class="info-item">
        <div class="il"><i class="ti ti-calendar" style="font-size:11px;"></i> Tanggal</div>
        <div class="iv">{{ \Carbon\Carbon::parse($t->departure_datetime)->format('d M Y H:i') }}</div>
      </div>
      <div class="info-item">
        <div class="il"><i class="ti ti-car" style="font-size:11px;"></i> Kendaraan</div>
        <div class="iv">{{ $t->vehicle ?? '—' }}</div>
      </div>
      <div class="info-item">
        <div class="il"><i class="ti ti-user" style="font-size:11px;"></i> Driver</div>
        <div class="iv">{{ $t->driver ?? '—' }}</div>
      </div>
      <div class="info-item">
        <div class="il"><i class="ti ti-users" style="font-size:11px;"></i> Penyetuju Lv.1</div>
        <div class="iv">{{ $t->approver1 }}</div>
      </div>
    </div>
    <div style="background:var(--surface);border-radius:var(--radius);padding:10px 12px;font-size:13px;color:var(--text-muted);">
      <strong style="color:var(--text);font-size:12px;">Keperluan:</strong> {{ $t->purpose }}
    </div>
  </div>

  <div class="approval-footer">
    <div style="font-size:12px;color:var(--text-muted);">
      <i class="ti ti-user-check" style="font-size:13px;vertical-align:-1px;"></i>
      Penyetuju Lv.2: <strong>{{ $t->approver2 }}</strong>
    </div>
    @if($t->status === 'Requested')
    <div style="display:flex;gap:8px;">
      <button class="btn btn-danger btn-sm" onclick="openReject({{ $t->id }}, '{{ $t->trip_code }}')">
        <i class="ti ti-x"></i> Tolak
      </button>
      <button class="btn btn-success btn-sm" onclick="openApprove({{ $t->id }}, '{{ $t->trip_code }}', '{{ $t->from_location }}', '{{ $t->to_location }}', '{{ $t->priority }}')">
        <i class="ti ti-check"></i> Setujui
      </button>
    </div>
    @else
    <div style="font-size:12px;color:var(--text-muted);">
      @if(in_array($t->status, ['Approved','Start','Finished']))
        <i class="ti ti-circle-check" style="color:var(--success);font-size:14px;vertical-align:-2px;"></i> Trip telah disetujui
      @else
        <i class="ti ti-circle-x" style="color:var(--danger);font-size:14px;vertical-align:-2px;"></i> Trip ditolak
        @if($t->reject_reason) — {{ $t->reject_reason }} @endif
      @endif
    </div>
    @endif
  </div>
</div>
@empty
<div class="empty-state">
  <i class="ti ti-clipboard-check"></i>
  <p>Tidak ada trip di kategori ini</p>
</div>
@endforelse

{{-- APPROVE MODAL --}}
<div class="modal-overlay" id="approveModal">
  <div class="modal">
    <div class="modal-header">
      <h2><i class="ti ti-circle-check" style="color:var(--success);margin-right:6px;"></i> Setujui Trip</h2>
      <button class="modal-close" onclick="closeModal('approveModal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="approveForm" action="">
      @csrf @method('PATCH')
      <div class="modal-body">
        <div id="approveSummary" style="background:var(--surface);border-radius:var(--radius);padding:12px;margin-bottom:14px;font-size:13px;"></div>
        <div class="form-group">
          <label class="form-label">Assign Driver (jika belum)</label>
          <select name="driver" class="form-control">
            <option value="">Tetap / Pilih Nanti</option>
            @foreach(['Agus Setiawan','Roni Kurniawan','Dedi Firmansyah','Supriadi'] as $d)
              <option value="{{ $d }}">{{ $d }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Assign Kendaraan (jika belum)</label>
          <select name="vehicle" class="form-control">
            <option value="">Tetap / Pilih Nanti</option>
            @foreach(['Toyota Hilux — KT 1234 AB','Isuzu ELF — KT 5678 CD','Mitsubishi Colt — KT 9012 EF','Ford Ranger — KT 3456 GH'] as $v)
              <option value="{{ $v }}">{{ $v }}</option>
            @endforeach
          </select>
        </div>
        <div style="background:var(--success-bg);border:1px solid #a8d5b5;border-radius:var(--radius);padding:12px;font-size:13px;color:#1b5e20;">
          <i class="ti ti-info-circle" style="font-size:15px;vertical-align:-2px;margin-right:6px;"></i>
          Tindakan ini akan menyetujui trip dan melanjutkan ke level berikutnya.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('approveModal')">Batal</button>
        <button type="submit" class="btn btn-success"><i class="ti ti-check"></i> Setujui</button>
      </div>
    </form>
  </div>
</div>

{{-- REJECT MODAL --}}
<div class="modal-overlay" id="rejectModal">
  <div class="modal" style="max-width:440px;">
    <div class="modal-header">
      <h2><i class="ti ti-circle-x" style="color:var(--danger);margin-right:6px;"></i> Tolak Trip</h2>
      <button class="modal-close" onclick="closeModal('rejectModal')"><i class="ti ti-x"></i></button>
    </div>
    <form method="POST" id="rejectForm" action="">
      @csrf @method('PATCH')
      <div class="modal-body">
        <div style="background:var(--danger-bg);border:1px solid #f5c6c6;border-radius:var(--radius);padding:12px;font-size:13px;color:#c62828;margin-bottom:14px;">
          <i class="ti ti-alert-triangle" style="font-size:15px;vertical-align:-2px;margin-right:6px;"></i>
          Setelah ditolak, pemohon perlu mengajukan ulang permohonan.
        </div>
        <div id="rejectSummary" style="background:var(--surface);border-radius:var(--radius);padding:10px 12px;margin-bottom:14px;font-size:13px;"></div>
        <div class="form-group">
          <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
          <textarea name="reject_reason" class="form-control" rows="4" placeholder="Tuliskan alasan penolakan..." required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('rejectModal')">Batal</button>
        <button type="submit" class="btn btn-danger"><i class="ti ti-x"></i> Tolak Trip</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function switchTab(tab) {
  window.location.href = '{{ route("approval.index") }}?tab=' + tab;
}

function openApprove(id, code, from, to, priority) {
  document.getElementById('approveForm').action = `/transportation/approval/${id}/approve`;
  document.getElementById('approveSummary').innerHTML = `
    <div style="display:flex;gap:8px;align-items:center;margin-bottom:6px;">
      <span style="font-family:'Space Mono',monospace;font-size:12px;font-weight:700;color:var(--primary);background:var(--primary-light);padding:2px 8px;border-radius:5px;">${code}</span>
    </div>
    <div style="font-size:13px;"><strong>${from}</strong> → <strong>${to}</strong></div>
    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">Prioritas: ${priority}</div>`;
  document.getElementById('approveModal').classList.add('open');
}

function openReject(id, code) {
  document.getElementById('rejectForm').action = `/transportation/approval/${id}/reject`;
  document.getElementById('rejectSummary').innerHTML = `Trip <strong style="font-family:'Space Mono',monospace;">${code}</strong> akan ditolak.`;
  document.getElementById('rejectModal').classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

document.querySelectorAll('.modal-overlay').forEach(o => {
  o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
</script>
@endpush