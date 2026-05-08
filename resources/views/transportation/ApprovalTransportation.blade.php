@extends('layouts.app')

@section('title', 'Persetujuan Trip')
@section('page-title', 'Persetujuan Trip')
@section('breadcrumb', 'Transportation / Approval')

@push('styles')
<style>
.tabs {
    display: flex;
    gap: 4px;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--border);
}
.tab-btn {
    padding: 10px 20px;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: -1px;
    transition: color .15s;
}
.tab-btn.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
}
.tab-btn .badge-count {
    background: var(--accent);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 10px;
}

/* Trip Card */
.trip-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    margin-bottom: 16px;
    overflow: hidden;
    transition: box-shadow .2s;
}
.trip-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); }

.trip-card-header {
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid var(--border);
    background: var(--surface, #fafafa);
}
.trip-code {
    font-family: 'Space Mono', monospace;
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
    background: var(--border);
    padding: 3px 8px;
    border-radius: 4px;
}
.priority-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* Progress Steps */
.approval-steps {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: auto;
}
.step {
    width: 28px; height: 28px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
}
.step.done  { background: #34d399; color: white; }
.step.wait  { background: var(--border); color: var(--text-muted); }
.step.rejected-step { background: #f87171; color: white; }
.step-line  { width: 32px; height: 2px; background: var(--border); border-radius: 1px; }
.step-line.done { background: #34d399; }
.step-label { font-size: 10px; color: var(--text-muted); }

.trip-card-body {
    padding: 16px 20px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}
@media (max-width: 768px) {
    .trip-card-body { grid-template-columns: 1fr 1fr; }
}
.trip-field-label {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--text-muted);
    margin-bottom: 2px;
    display: flex; align-items: center; gap: 4px;
}
.trip-field-value {
    font-size: 13px;
    color: var(--text);
    font-weight: 500;
}

.trip-card-footer {
    padding: 12px 20px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--surface, #fafafa);
    gap: 10px;
}
.approver-info {
    font-size: 12px;
    color: var(--text-muted);
    display: flex; align-items: center; gap: 6px;
}
.footer-actions {
    display: flex; gap: 8px;
}

/* Status badges */
.badge {
    display: inline-flex; align-items: center;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px; font-weight: 600;
    letter-spacing: .3px;
}
.badge-requested { background:#fef3c7; color:#92400e; }
.badge-approved  { background:#d1fae5; color:#065f46; }
.badge-rejected  { background:#fee2e2; color:#991b1b; }
.badge-started   { background:#dbeafe; color:#1e40af; }
.badge-finished  { background:#ede9fe; color:#5b21b6; }

/* Reject Modal */
.modal-backdrop {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 1000;
    align-items: center; justify-content: center;
}
.modal-backdrop.open { display: flex; }
.modal-box {
    background: var(--card);
    border-radius: var(--radius-lg);
    padding: 28px;
    width: 480px;
    max-width: 95vw;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
}
.modal-box h3 { margin: 0 0 6px; font-size: 18px; }
.modal-box p  { margin: 0 0 16px; font-size: 13px; color: var(--text-muted); }
.modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 16px; }

.empty-state {
    text-align: center; padding: 60px 20px;
    color: var(--text-muted);
}
.empty-state .empty-icon { font-size: 48px; margin-bottom: 12px; opacity: .4; }

/* Level info banner */
.level-banner {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; color: var(--text-muted);
    margin-bottom: 20px;
}
.level-banner i { color: var(--accent); }
</style>
@endpush

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="margin:0; font-size:20px;">Daftar Persetujuan</h2>
    <div class="level-banner">
        <i class="ti ti-info-circle"></i>
        Diperlukan minimal 2 level persetujuan
    </div>
</div>

{{-- Tabs --}}
<div class="tabs">
    @php
        $tabs = [
            'pending'  => ['label' => 'Menunggu', 'count' => $pendingCount],
            'approved' => ['label' => 'Disetujui', 'count' => null],
            'rejected' => ['label' => 'Ditolak',   'count' => null],
            'all'      => ['label' => 'Semua',     'count' => null],
        ];
    @endphp
    @foreach($tabs as $key => $t)
    <button
        class="tab-btn {{ $tab === $key ? 'active' : '' }}"
        onclick="window.location='{{ route('approval.index', ['tab' => $key]) }}'">
        {{ $t['label'] }}
        @if($t['count'])
        <span class="badge-count">{{ $t['count'] }}</span>
        @endif
    </button>
    @endforeach
</div>

{{-- Trip Cards --}}
@forelse($trips as $trip)

@php
    $statusMap = [
        'Requested' => 'badge-requested',
        'Approved'  => 'badge-approved',
        'Rejected'  => 'badge-rejected',
        'Start'     => 'badge-started',
        'Finished'  => 'badge-finished',
    ];
    $priorityColor = [
        'Flexible'  => '#94a3b8',
        'Normal'    => '#60a5fa',
        'Urgent'    => '#fb923c',
        'Emergency' => '#f87171',
    ];

    $userRole = auth()->user()->role_user;
    $lvl      = $trip->approval_level ?? 0;

    // Tentukan apakah user ini bisa approve pada level saat ini
    // Level 1: admin_trans atau manager (jika belum ada yang approve lvl 1)
    // Level 2: hanya manager (setelah lvl 1 selesai)
    $canApproveL1 = $trip->status === 'Requested'
                    && $lvl < 1
                    && in_array($userRole, ['admin_trans', 'manager']);

    $canApproveL2 = $trip->status === 'Requested'
                    && $lvl === 1
                    && $userRole === 'manager';

    $canReject    = $trip->status === 'Requested'
                    && in_array($userRole, ['admin_trans', 'manager'])
                    && (
                        ($lvl < 1 && in_array($userRole, ['admin_trans', 'manager'])) // belum ada yang approve
                        || ($lvl === 1 && $userRole === 'manager')                     // sudah lvl1, tinggal manager
                    );

    $showActions  = $canApproveL1 || $canApproveL2;
@endphp

<div class="trip-card">

    {{-- Header --}}
    <div class="trip-card-header">
        <span class="trip-code">{{ $trip->trip_code }}</span>
        <span class="badge {{ $statusMap[$trip->status] ?? 'badge-requested' }}">{{ $trip->status }}</span>
        <span class="badge" style="background:{{ ($priorityColor[$trip->priority] ?? '#94a3b8') }}22; color:{{ $priorityColor[$trip->priority] ?? '#94a3b8' }};">
            <span class="priority-dot" style="background:{{ $priorityColor[$trip->priority] ?? '#94a3b8' }};"></span>
            {{ $trip->priority }}
        </span>

        {{-- Approval Steps --}}
        <div class="approval-steps" title="Level Persetujuan">
            {{-- Step 1 --}}
            <div class="step {{ $lvl >= 1 ? 'done' : ($trip->status === 'Rejected' && $lvl < 1 ? 'rejected-step' : 'wait') }}"
                 title="Level 1: Admin Transportasi">
                @if($lvl >= 1)
                <i class="ti ti-check" style="font-size:11px;"></i>
                @else
                1
                @endif
            </div>
            <div class="step-line {{ $lvl >= 1 ? 'done' : '' }}"></div>
            {{-- Step 2 --}}
            <div class="step {{ $lvl >= 2 ? 'done' : ($trip->status === 'Rejected' ? 'rejected-step' : 'wait') }}"
                 title="Level 2: Manager">
                @if($lvl >= 2)
                <i class="ti ti-check" style="font-size:11px;"></i>
                @elseif($trip->status === 'Rejected')
                <i class="ti ti-x" style="font-size:11px;"></i>
                @else
                2
                @endif
            </div>
            <span class="step-label">Lv.{{ $lvl }}/2</span>
        </div>
    </div>

    {{-- Body --}}
    <div class="trip-card-body">
        <div>
            <div class="trip-field-label"><i class="ti ti-map-pin" style="font-size:10px;"></i> Dari</div>
            <div class="trip-field-value">{{ $trip->from_location ?? '—' }}</div>
        </div>
        <div>
            <div class="trip-field-label"><i class="ti ti-flag" style="font-size:10px;"></i> Tujuan</div>
            <div class="trip-field-value">{{ $trip->to_location ?? '—' }}</div>
        </div>
        <div>
            <div class="trip-field-label"><i class="ti ti-calendar" style="font-size:10px;"></i> Tanggal</div>
            <div class="trip-field-value">
                {{ $trip->departure_datetime
                    ? \Carbon\Carbon::parse($trip->departure_datetime)->format('d M Y H:i')
                    : '—' }}
            </div>
        </div>
        <div>
            <div class="trip-field-label"><i class="ti ti-car" style="font-size:10px;"></i> Kendaraan</div>
            <div class="trip-field-value">{{ $trip->vehicle ?? '—' }}</div>
        </div>
        <div>
            <div class="trip-field-label"><i class="ti ti-user" style="font-size:10px;"></i> Driver</div>
            <div class="trip-field-value">{{ $trip->driver ?? '—' }}</div>
        </div>
        <div>
            <div class="trip-field-label"><i class="ti ti-user-check" style="font-size:10px;"></i> Penyetuju Lv.1</div>
            <div class="trip-field-value">{{ $trip->approver1 ?? '—' }}</div>
        </div>
    </div>

    {{-- Keperluan --}}
    @if($trip->purpose)
    <div style="padding: 0 20px 14px; font-size:13px; color:var(--text-muted);">
        <strong style="color:var(--text);">Keperluan:</strong> {{ $trip->purpose }}
    </div>
    @endif

    {{-- Reject Reason --}}
    @if($trip->status === 'Rejected' && $trip->reject_reason)
    <div style="padding: 10px 20px; background:#fee2e2; border-top:1px solid #fca5a5; font-size:13px; color:#991b1b;">
        <i class="ti ti-x-circle" style="margin-right:4px;"></i>
        <strong>Alasan Penolakan:</strong> {{ $trip->reject_reason }}
    </div>
    @endif

    {{-- Footer --}}
    <div class="trip-card-footer">
        <div class="approver-info">
            <i class="ti ti-user-circle" style="font-size:14px;"></i>
            Penyetuju Lv.2: <strong>{{ $trip->approver2 ?? '—' }}</strong>
        </div>

        <div class="footer-actions">
            {{-- Tombol HANYA muncul jika user punya wewenang di level saat ini --}}

            @if($showActions)

            {{-- Tombol Tolak --}}
            @if($canReject)
            <button class="btn btn-danger" onclick="openRejectModal({{ $trip->id }}, '{{ $trip->trip_code }}')">
                <i class="ti ti-x"></i> Tolak
            </button>
            @endif

            {{-- Tombol Setujui --}}
            @if($canApproveL1 || $canApproveL2)
            <form method="POST" action="{{ route('approval.approve', $trip->id) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-success"
                    onclick="return confirm('Setujui trip {{ $trip->trip_code }}?')">
                    <i class="ti ti-check"></i>
                    Setujui
                    @if($canApproveL1) (Lv.1) @else (Lv.2) @endif
                </button>
            </form>
            @endif

            @elseif($trip->status === 'Requested')
            {{-- User tidak punya wewenang di level ini --}}
            <span style="font-size:12px; color:var(--text-muted); font-style:italic;">
                @if($lvl === 0)
                    Menunggu persetujuan Level 1
                @elseif($lvl === 1)
                    Menunggu persetujuan Level 2 (Manager)
                @endif
            </span>
            @endif

        </div>
    </div>

</div>
@empty
<div class="empty-state">
    <div class="empty-icon">📋</div>
    <p>Tidak ada trip pada tab ini.</p>
</div>
@endforelse

{{-- ── Reject Modal ── --}}
<div class="modal-backdrop" id="rejectModal">
    <div class="modal-box">
        <h3>❌ Tolak Trip</h3>
        <p id="rejectModalSubtitle">Masukkan alasan penolakan</p>
        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span style="color:var(--danger);">*</span></label>
                <textarea name="reject_reason" class="form-control" rows="3"
                    placeholder="Jelaskan alasan penolakan..." required minlength="5"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="ti ti-x"></i> Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openRejectModal(tripId, tripCode) {
    document.getElementById('rejectForm').action = `/transportation/approval/${tripId}/reject`;
    document.getElementById('rejectModalSubtitle').textContent = `Trip: ${tripCode}`;
    document.getElementById('rejectModal').classList.add('open');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('open');
}
// Tutup modal jika klik backdrop
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush