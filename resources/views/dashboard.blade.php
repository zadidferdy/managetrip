@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Home / Dashboard')

@push('styles')
@if(auth()->user()->role_user !== 'karyawan')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endif

<style>
/* ── Stats Grid ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: box-shadow .2s;
}
.stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.08); }
.stat-label {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
}
.stat-value {
    font-size: 30px;
    font-weight: 700;
    color: var(--text);
    line-height: 1;
}
.stat-icon { font-size: 22px; margin-bottom: 2px; }

/* ── Chart Row ── */
.chart-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}
@media (max-width: 768px) {
    .chart-row { grid-template-columns: 1fr; }
}

/* PENTING: wrapper canvas harus punya tinggi eksplisit */
.chart-wrapper {
    position: relative;
    width: 100%;
    height: 260px;
    padding: 8px 0 16px;
}

/* ── Table ── */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 14px; }
thead th {
    text-align: left;
    padding: 10px 14px;
    color: var(--text-muted);
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-bottom: 1px solid var(--border);
}
tbody td {
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
    color: var(--text);
    vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: rgba(0,0,0,.02); }

/* ── Badge ── */
.badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .3px;
}
.badge-requested { background:#fef3c7; color:#92400e; }
.badge-approved  { background:#d1fae5; color:#065f46; }
.badge-rejected  { background:#fee2e2; color:#991b1b; }
.badge-started   { background:#dbeafe; color:#1e40af; }
.badge-finished  { background:#ede9fe; color:#5b21b6; }

.trip-id-mono {
    font-family: monospace;
    font-size: 12px;
    background: var(--border);
    padding: 2px 6px;
    border-radius: 4px;
}

/* ── Karyawan Banner ── */
.welcome-banner {
    background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
    color: white;
    border-radius: var(--radius-lg);
    padding: 28px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.welcome-banner .wb-icon { font-size: 36px; }
.welcome-banner h2 { margin: 0 0 4px; font-size: 20px; font-weight: 700; }
.welcome-banner p  { margin: 0; font-size: 14px; opacity: .85; }

/* ── Empty state ── */
.empty-chart {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 260px;
    color: var(--text-muted);
    font-size: 13px;
    gap: 8px;
}
.empty-chart .empty-icon { font-size: 36px; opacity: .4; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════
     KARYAWAN — Hanya status trip
════════════════════════════ --}}
@if(auth()->user()->role_user === 'karyawan')

    <div class="welcome-banner">
        <div class="wb-icon">🚌</div>
        <div>
            <h2>Halo, {{ auth()->user()->nama_user }}!</h2>
            <p>Berikut status trip terbaru yang bisa kamu pantau.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Status Trip Terbaru</h3>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Trip ID</th>
                        <th>Dari</th>
                        <th>Tujuan</th>
                        <th>Tanggal Berangkat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTrips as $trip)
                    <tr>
                        <td><span class="trip-id-mono">{{ $trip->trip_code }}</span></td>
                        <td>{{ $trip->from_location ?? '-' }}</td>
                        <td>{{ $trip->to_location ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($trip->departure_datetime)->format('d M Y, H:i') }}</td>
                        <td>
                            @php $statusMap=['Requested'=>'badge-requested','Approved'=>'badge-approved','Rejected'=>'badge-rejected','Start'=>'badge-started','Finished'=>'badge-finished']; @endphp
                            <span class="badge {{ $statusMap[$trip->status] ?? 'badge-requested' }}">{{ $trip->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px;">
                            Belum ada trip.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

{{-- ═══════════════════════════════════════
     MANAGER / ADMIN / ADMIN_TRANS — Lengkap
════════════════════════════════════════ --}}
@else

    @php
        $hasData = ($pendingApproval + $approved + $started + $finished + $rejected) > 0;
    @endphp

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-label">Trip Bulan Ini</div>
            <div class="stat-value">{{ $totalTrip }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $pendingApproval }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-label">Approved</div>
            <div class="stat-value">{{ $approved }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🚌</div>
            <div class="stat-label">Berjalan</div>
            <div class="stat-value">{{ $started }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏁</div>
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $finished }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">❌</div>
            <div class="stat-label">Ditolak</div>
            <div class="stat-value">{{ $rejected }}</div>
        </div>
    </div>

    {{-- Chart Row --}}
    <div class="chart-row">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status Trip</h3>
            </div>
            @if($hasData)
            <div class="chart-wrapper">
                <canvas id="statusChart"></canvas>
            </div>
            @else
            <div class="empty-chart">
                <div class="empty-icon">📊</div>
                <span>Belum ada data trip</span>
            </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ringkasan</h3>
            </div>
            @if($hasData)
            <div class="chart-wrapper">
                <canvas id="barChart"></canvas>
            </div>
            @else
            <div class="empty-chart">
                <div class="empty-icon">📈</div>
                <span>Belum ada data trip</span>
            </div>
            @endif
        </div>

    </div>

    {{-- Recent Trips Table --}}
    <div class="card">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
            <h3 class="card-title">Trip Terbaru</h3>
            @if(in_array(auth()->user()->role_user, ['manager','admin']))
            <a href="{{ route('trip.index') }}" class="btn btn-outline" style="font-size:13px;">Lihat Semua</a>
            @endif
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Trip ID</th>
                        <th>Dari</th>
                        <th>Tujuan</th>
                        <th>Tanggal Berangkat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTrips as $trip)
                    <tr>
                        <td><span class="trip-id-mono">{{ $trip->trip_code }}</span></td>
                        <td>{{ $trip->from_location ?? '-' }}</td>
                        <td>{{ $trip->to_location ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($trip->departure_datetime)->format('d M Y, H:i') }}</td>
                        <td>
                            @php $statusMap=['Requested'=>'badge-requested','Approved'=>'badge-approved','Rejected'=>'badge-rejected','Start'=>'badge-started','Finished'=>'badge-finished']; @endphp
                            <span class="badge {{ $statusMap[$trip->status] ?? 'badge-requested' }}">{{ $trip->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px;">
                            Belum ada trip.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($hasData)
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const COLORS = ['#fbbf24','#34d399','#60a5fa','#a78bfa','#f87171'];
        const LABELS = ['Pending','Approved','Berjalan','Selesai','Ditolak'];
        const DATA   = [{{ $pendingApproval }},{{ $approved }},{{ $started }},{{ $finished }},{{ $rejected }}];

        // Donut Chart
        const ctxD = document.getElementById('statusChart');
        if (ctxD) {
            new Chart(ctxD, {
                type: 'doughnut',
                data: {
                    labels: LABELS,
                    datasets: [{
                        data: DATA,
                        backgroundColor: COLORS,
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 10,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 14,
                                font: { size: 12 },
                                usePointStyle: true,
                                pointStyleWidth: 8,
                            }
                        }
                    }
                }
            });
        }

        // Bar Chart
        const ctxB = document.getElementById('barChart');
        if (ctxB) {
            new Chart(ctxB, {
                type: 'bar',
                data: {
                    labels: LABELS,
                    datasets: [{
                        label: 'Jumlah Trip',
                        data: DATA,
                        backgroundColor: COLORS,
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 48,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 11 } },
                            grid: { color: 'rgba(0,0,0,.06)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }
    });
    </script>
    @endpush
    @endif

@endif

@endsection