@extends('layouts.app')

@section('title', 'Buat Trip Baru')
@section('page-title', 'Buat Trip Baru')
@section('breadcrumb', 'Transportation / Trip / Buat')

@section('content')

<div class="page-header">
  <h2>Form Pemesanan Trip</h2>
  <a href="{{ route('trip.index') }}" class="btn btn-outline">
    <i class="ti ti-arrow-left"></i> Kembali
  </a>
</div>

<form method="POST" action="{{ route('trip.store') }}">
@csrf

{{-- INFORMASI PERJALANAN --}}
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <h3><i class="ti ti-map-pin" style="margin-right:6px;color:var(--accent);"></i>Informasi Perjalanan</h3>
  </div>
  <div class="card-body">
    <div class="grid-2">
      <div class="form-group">
        <label class="form-label">Tanggal & Jam Berangkat <span class="required">*</span></label>
        <input type="datetime-local" name="departure_datetime" class="form-control" value="{{ old('departure_datetime') }}" required>
        @error('departure_datetime')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Estimasi Tanggal & Jam Kembali</label>
        <input type="datetime-local" name="arrival_datetime" class="form-control" value="{{ old('arrival_datetime') }}">
        @error('arrival_datetime')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="grid-2">
      <div class="form-group">
        <label class="form-label">Lokasi Asal <span class="required">*</span></label>
        <input type="text" name="from_location" class="form-control" placeholder="Cth: Kantor Pusat, Balikpapan" value="{{ old('from_location') }}" required>
        @error('from_location')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Lokasi Tujuan <span class="required">*</span></label>
        <input type="text" name="to_location" class="form-control" placeholder="Cth: Site Tambang A, Kutai" value="{{ old('to_location') }}" required>
        @error('to_location')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
    </div>
    <div class="grid-3">
      <div class="form-group">
        <label class="form-label">Jenis Trip <span class="required">*</span></label>
        <select name="trip_type" class="form-control" required>
          <option value="">-- Pilih Jenis --</option>
          @foreach($tripTypes as $type)
            <option value="{{ $type }}" {{ old('trip_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
          @endforeach
        </select>
        @error('trip_type')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Prioritas <span class="required">*</span></label>
        <select name="priority" class="form-control" required>
          @foreach(['Flexible','Normal','Urgent','Emergency'] as $p)
            <option value="{{ $p }}" {{ old('priority','Normal') == $p ? 'selected' : '' }}>{{ $p }}</option>
          @endforeach
        </select>
        @error('priority')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Region</label>
        <select name="region" class="form-control">
          <option value="">-- Pilih Region --</option>
          @foreach(['Kantor Pusat','Kantor Cabang','Tambang 1','Tambang 2','Tambang 3','Tambang 4','Tambang 5','Tambang 6'] as $r)
            <option value="{{ $r }}" {{ old('region') == $r ? 'selected' : '' }}>{{ $r }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
</div>

{{-- KENDARAAN & DRIVER --}}
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <h3><i class="ti ti-car" style="margin-right:6px;color:var(--accent);"></i>Kendaraan & Driver</h3>
  </div>
  <div class="card-body">
    <div class="grid-2">

      {{-- KENDARAAN --}}
      <div class="form-group">
        <label class="form-label">Kendaraan</label>
        @if(in_array(auth()->user()->role_user, ['manager', 'admin', 'admin_trans']))
          <select name="vehicle_id" class="form-control">
            <option value="">-- Pilih Kendaraan --</option>
            @forelse($vehicles as $v)
              <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                {{ $v->name }} — {{ $v->plate_number }}{{ $v->type ? ' ('.$v->type.')' : '' }}
              </option>
            @empty
              <option value="" disabled>Tidak ada kendaraan aktif</option>
            @endforelse
          </select>
          @error('vehicle_id')
            <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
          @enderror
        @else
          <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:10px 14px;font-size:13px;color:var(--text-muted);">
            <i class="ti ti-info-circle" style="color:var(--accent);margin-right:6px;"></i>
            Kendaraan akan ditentukan oleh Admin Transportasi saat proses approval.
          </div>
        @endif
      </div>

      {{-- DRIVER --}}
      <div class="form-group">
        <label class="form-label">Driver</label>
        @if(in_array(auth()->user()->role_user, ['manager', 'admin', 'admin_trans']))
          <select name="driver" class="form-control">
            <option value="">-- Pilih Driver --</option>
            @forelse($drivers as $d)
              <option value="{{ $d->nama_user }}" {{ old('driver') == $d->nama_user ? 'selected' : '' }}>
                {{ $d->nama_user }}
              </option>
            @empty
              <option value="" disabled>Tidak ada driver terdaftar</option>
            @endforelse
          </select>
          @error('driver')
            <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
          @enderror
        @else
          <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:10px 14px;font-size:13px;color:var(--text-muted);">
            <i class="ti ti-info-circle" style="color:var(--accent);margin-right:6px;"></i>
            Driver akan ditentukan oleh Admin Transportasi saat proses approval.
          </div>
        @endif
      </div>

    </div>

    {{-- KM START --}}
    <div class="grid-2">
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">
          <i class="ti ti-speedometer" style="margin-right:4px;color:var(--accent);"></i>
          KM Awal (Odometer)
        </label>
        <div style="position:relative;">
          <input type="number" name="km_start" class="form-control" style="padding-right:48px;"
            placeholder="Cth: 12500"
            min="0"
            value="{{ old('km_start') }}">
          <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:12px;color:var(--text-muted);font-weight:600;pointer-events:none;">km</span>
        </div>
        <div style="font-size:11.5px;color:var(--text-muted);margin-top:4px;">Isi km odometer kendaraan saat berangkat</div>
        @error('km_start')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>
</div>

{{-- PERSETUJUAN --}}
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <h3><i class="ti ti-users" style="margin-right:6px;color:var(--accent);"></i>Persetujuan</h3>
  </div>
  <div class="card-body">
    <div class="grid-2">
      {{-- Level 1: Admin Transportasi --}}
      <div class="form-group">
        <label class="form-label">Penyetuju Level 1 <span class="required">*</span></label>
        <select name="approver1" class="form-control" required>
          <option value="">-- Pilih Approver --</option>
          @forelse($approvers1 as $user)
            <option value="{{ $user->nama_user }}" {{ old('approver1') == $user->nama_user ? 'selected' : '' }}>
              {{ $user->nama_user }} (Admin Transportasi)
            </option>
          @empty
            <option value="" disabled>Tidak ada Admin Transportasi terdaftar</option>
          @endforelse
        </select>
        @error('approver1')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
      {{-- Level 2: Manager --}}
      <div class="form-group">
        <label class="form-label">Penyetuju Level 2 <span class="required">*</span></label>
        <select name="approver2" class="form-control" required>
          <option value="">-- Pilih Approver --</option>
          @forelse($approvers2 as $user)
            <option value="{{ $user->nama_user }}" {{ old('approver2') == $user->nama_user ? 'selected' : '' }}>
              {{ $user->nama_user }} (Manager)
            </option>
          @empty
            <option value="" disabled>Tidak ada Manager terdaftar</option>
          @endforelse
        </select>
        @error('approver2')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>
</div>

{{-- KEPERLUAN --}}
<div class="card" style="margin-bottom:20px;">
  <div class="card-header">
    <h3><i class="ti ti-file-description" style="margin-right:6px;color:var(--accent);"></i>Keperluan & Catatan</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label class="form-label">Keperluan / Tujuan Perjalanan <span class="required">*</span></label>
      <textarea name="purpose" class="form-control" rows="3" placeholder="Jelaskan tujuan perjalanan..." required>{{ old('purpose') }}</textarea>
      @error('purpose')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
    </div>
    <div class="form-group" style="margin-bottom:0;">
      <label class="form-label">Catatan Tambahan</label>
      <textarea name="notes" class="form-control" rows="2" placeholder="Catatan untuk driver, muatan, dll...">{{ old('notes') }}</textarea>
    </div>
  </div>
</div>

<div style="display:flex;justify-content:flex-end;gap:10px;">
  <a href="{{ route('trip.index') }}" class="btn btn-outline">Batal</a>
  <button type="submit" class="btn btn-primary">
    <i class="ti ti-send"></i> Kirim Permohonan
  </button>
</div>

</form>

@endsection