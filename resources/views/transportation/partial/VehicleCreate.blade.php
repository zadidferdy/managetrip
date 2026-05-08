  @extends('layouts.app')

  @section('title', 'Tambah Kendaraan')
  @section('page-title', 'Tambah Kendaraan')
  @section('breadcrumb', 'Transportation / Vehicle / Tambah')

  @push('styles')
  <style>
  #rental-fields { display: none; }
  #rental-fields.show { display: block; }
  </style>
  @endpush

  @section('content')

  <div class="page-header">
    <h2>Tambah Kendaraan</h2>
    <a href="{{ route('vehicle.index') }}" class="btn btn-outline">
      <i class="ti ti-arrow-left"></i> Kembali
    </a>
  </div>

  <form method="POST" action="{{ route('vehicle.store') }}">
  @csrf

  {{-- IDENTITAS KENDARAAN --}}
  <div class="card" style="margin-bottom:16px;">
    <div class="card-header">
      <h3><i class="ti ti-car" style="margin-right:6px;color:var(--accent);"></i>Identitas Kendaraan</h3>
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="form-group">
          <label class="form-label">Nomor Plat <span class="required">*</span></label>
          <input type="text" name="plate_number" class="form-control"
            placeholder="Cth: KT 1234 AB"
            value="{{ old('plate_number') }}" required>
          @error('plate_number')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Nama Kendaraan <span class="required">*</span></label>
          <input type="text" name="name" class="form-control"
            placeholder="Cth: Toyota Hilux"
            value="{{ old('name') }}" required>
          @error('name')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="grid-3">
        <div class="form-group">
          <label class="form-label">Brand <span class="required">*</span></label>
          <input type="text" name="brand" class="form-control"
            placeholder="Cth: Toyota"
            value="{{ old('brand') }}" required>
          @error('brand')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Tahun <span class="required">*</span></label>
          <input type="number" name="year" class="form-control"
            placeholder="Cth: 2020"
            value="{{ old('year') }}" min="1990" max="{{ now()->year }}" required>
          @error('year')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Tipe <span class="required">*</span></label>
          <select name="type" class="form-control" required>
            <option value="">-- Pilih Tipe --</option>
            @foreach(['Angkutan Orang','Angkutan Barang'] as $t)
              <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
          </select>
          @error('type')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>
  </div>

  {{-- KEPEMILIKAN --}}
  <div class="card" style="margin-bottom:16px;">
    <div class="card-header">
      <h3><i class="ti ti-building" style="margin-right:6px;color:var(--accent);"></i>Kepemilikan</h3>
    </div>
    <div class="card-body">
      <div class="form-group">
        <label class="form-label">Status Kepemilikan <span class="required">*</span></label>
        <select name="ownership" id="ownership" class="form-control" required>
          @foreach(['Milik Perusahaan','Sewa'] as $o)
            <option value="{{ $o }}" {{ old('ownership','Milik Perusahaan') == $o ? 'selected' : '' }}>{{ $o }}</option>
          @endforeach
        </select>
        @error('ownership')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>

      {{-- Rental fields (tampil hanya jika Sewa) --}}
      <div id="rental-fields" class="{{ old('ownership') === 'Sewa' ? 'show' : '' }}">
        <div class="grid-3">
          <div class="form-group" style="flex:2;">
            <label class="form-label">Nama Perusahaan Sewa <span class="required">*</span></label>
            <input type="text" name="rental_company" class="form-control"
              placeholder="Cth: PT Sewa Kendaraan Mandiri"
              value="{{ old('rental_company') }}">
            @error('rental_company')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Mulai Sewa <span class="required">*</span></label>
            <input type="date" name="rental_start" class="form-control"
              value="{{ old('rental_start') }}">
            @error('rental_start')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Akhir Sewa</label>
            <input type="date" name="rental_end" class="form-control"
              value="{{ old('rental_end') }}">
            @error('rental_end')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- DATA SERVIS --}}
  <div class="card" style="margin-bottom:16px;">
    <div class="card-header">
      <h3><i class="ti ti-tool" style="margin-right:6px;color:var(--accent);"></i>Data Servis</h3>
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="form-group">
          <label class="form-label">Tanggal Servis Terakhir</label>
          <input type="date" name="last_service_date" class="form-control"
            value="{{ old('last_service_date') }}">
          @error('last_service_date')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">KM Saat Servis Terakhir</label>
          <input type="number" name="last_service_km" class="form-control"
            placeholder="Cth: 15000"
            value="{{ old('last_service_km') }}" min="0">
          @error('last_service_km')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>
  </div>

  {{-- STATUS & CATATAN --}}
  <div class="card" style="margin-bottom:20px;">
    <div class="card-header">
      <h3><i class="ti ti-settings" style="margin-right:6px;color:var(--accent);"></i>Status & Catatan</h3>
    </div>
    <div class="card-body">
      <div class="form-group">
        <label class="form-label">Status <span class="required">*</span></label>
        <select name="status" class="form-control" required>
          @foreach(['Aktif','Dalam Perbaikan','Tidak Aktif'] as $s)
            <option value="{{ $s }}" {{ old('status','Aktif') == $s ? 'selected' : '' }}>{{ $s }}</option>
          @endforeach
        </select>
        @error('status')<div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Catatan</label>
        <textarea name="notes" class="form-control" rows="3"
          placeholder="Catatan tambahan tentang kendaraan...">{{ old('notes') }}</textarea>
      </div>
    </div>
  </div>

  <div style="display:flex;justify-content:flex-end;gap:10px;">
    <a href="{{ route('vehicle.index') }}" class="btn btn-outline">Batal</a>
    <button type="submit" class="btn btn-primary">
      <i class="ti ti-device-floppy"></i> Simpan
    </button>
  </div>

  </form>

  @push('scripts')
  <script>
    const ownershipSelect = document.getElementById('ownership');
    const rentalFields    = document.getElementById('rental-fields');

    function toggleRental() {
      if (ownershipSelect.value === 'Sewa') {
        rentalFields.classList.add('show');
      } else {
        rentalFields.classList.remove('show');
      }
    }

    ownershipSelect.addEventListener('change', toggleRental);
    toggleRental(); // run on load
  </script>
  @endpush

  @endsection