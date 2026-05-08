<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VehiclePool — Daftar</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.0.0/dist/tabler-icons.min.css">
<style>
:root {
  --coal: #0e0f11;
  --ore:  #1a1c22;
  --vein: #262932;
  --rust: #c8440a;
  --rust-dim: #a33608;
  --gold: #e8a730;
  --chalk: #f0ede6;
  --mist: #8c8f99;
  --line: rgba(255,255,255,0.06);
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'DM Sans', sans-serif;
  background: var(--coal);
  color: var(--chalk);
  min-height: 100vh;
  display: flex;
  overflow-x: hidden;
}
body::before {
  content: '';
  position: fixed;
  inset: 0;
  background-image:
    linear-gradient(var(--line) 1px, transparent 1px),
    linear-gradient(90deg, var(--line) 1px, transparent 1px);
  background-size: 60px 60px;
  pointer-events: none;
  z-index: 0;
}
.left-panel {
  width: 38%;
  background: var(--ore);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 48px;
  position: relative;
  border-right: 1px solid var(--line);
  z-index: 1;
}
.left-panel::after {
  content: '';
  position: absolute;
  top: 0; right: -1px; bottom: 0;
  width: 3px;
  background: linear-gradient(to bottom, transparent, var(--rust) 40%, var(--rust) 60%, transparent);
}
.brand { display: flex; align-items: center; gap: 14px; }
.brand-icon {
  width: 44px; height: 44px;
  background: var(--rust);
  clip-path: polygon(0 20%, 100% 0, 100% 80%, 0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: #fff;
}
.brand-name {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 26px;
  letter-spacing: 3px;
  line-height: 1;
}
.brand-sub {
  font-family: 'Space Mono', monospace;
  font-size: 9px;
  color: var(--mist);
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-top: 3px;
}
.middle-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.step-label {
  font-family: 'Space Mono', monospace;
  font-size: 10px;
  color: var(--rust);
  letter-spacing: 3px;
  text-transform: uppercase;
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}
.step-label::before {
  content: '';
  width: 28px; height: 1px;
  background: var(--rust);
}
.display-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 62px;
  line-height: 0.9;
  letter-spacing: 2px;
  margin-bottom: 20px;
}
.display-title span { color: var(--rust); display: block; }
.desc { font-size: 13px; line-height: 1.8; color: var(--mist); margin-bottom: 24px; }
.role-info { display: flex; flex-direction: column; gap: 8px; }
.ri-card {
  background: var(--vein);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 8px;
  padding: 10px 14px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.ri-icon {
  width: 32px; height: 32px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 15px;
  flex-shrink: 0;
}
.ri-icon.admin       { background: rgba(200,68,10,0.15);   color: #e87040; }
.ri-icon.admin_trans { background: rgba(200,68,10,0.10);   color: #f0a070; }
.ri-icon.manager     { background: rgba(232,167,48,0.15);  color: #e8a730; }
.ri-icon.driver      { background: rgba(99,179,237,0.15);  color: #63b3ed; }
.ri-icon.karyawan    { background: rgba(29,158,117,0.15);  color: #1d9e75; }
.ri-title {
  font-family: 'Space Mono', monospace;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--chalk);
  display: block;
}
.ri-desc { font-size: 11px; color: var(--mist); margin-top: 2px; line-height: 1.4; }
.bottom-note {
  font-family: 'Space Mono', monospace;
  font-size: 10px;
  color: rgba(140,143,153,0.5);
  letter-spacing: 1px;
}
.right-panel {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 48px 56px;
  z-index: 1;
  overflow-y: auto;
}
.form-box { width: 100%; max-width: 400px; }
.form-headline { margin-bottom: 32px; }
.form-headline h2 {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 36px;
  letter-spacing: 2px;
  line-height: 1;
  margin-bottom: 8px;
}
.form-headline p { font-size: 13px; color: var(--mist); }
.form-headline p a { color: var(--rust); text-decoration: none; font-weight: 500; }
.form-headline p a:hover { text-decoration: underline; }
.field { margin-bottom: 16px; }
.field label {
  display: block;
  font-family: 'Space Mono', monospace;
  font-size: 10px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--mist);
  margin-bottom: 7px;
}
.input-wrap { position: relative; }
.input-wrap i.icon-left {
  position: absolute;
  left: 13px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 16px;
  color: var(--mist);
  pointer-events: none;
}
.input-wrap input,
.input-wrap select {
  width: 100%;
  padding: 12px 13px 12px 40px;
  background: var(--vein);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 6px;
  color: var(--chalk);
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  appearance: none;
  -webkit-appearance: none;
}
.input-wrap select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%238c8f99' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 12px center;
  background-size: 12px;
  padding-right: 36px;
}
.input-wrap select option { background: var(--ore); }
.input-wrap input:focus,
.input-wrap select:focus {
  border-color: var(--rust);
  box-shadow: 0 0 0 3px rgba(200,68,10,0.15);
}
.eye-btn {
  position: absolute;
  right: 11px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: var(--mist);
  font-size: 16px;
  padding: 4px;
  transition: color 0.2s;
}
.eye-btn:hover { color: var(--chalk); }
.pw-strength {
  margin-top: 8px;
  display: flex;
  gap: 4px;
  align-items: center;
}
.pw-bar {
  flex: 1;
  height: 3px;
  background: rgba(255,255,255,0.08);
  border-radius: 2px;
  transition: background 0.3s;
}
.pw-bar.weak   { background: #e74c3c; }
.pw-bar.medium { background: var(--gold); }
.pw-bar.strong { background: #1d9e75; }
.pw-label {
  font-family: 'Space Mono', monospace;
  font-size: 10px;
  color: var(--mist);
  width: 52px;
  text-align: right;
  letter-spacing: 1px;
}
.alert-error {
  background: rgba(200,68,10,0.12);
  border: 1px solid rgba(200,68,10,0.3);
  border-radius: 6px;
  padding: 10px 14px;
  font-size: 13px;
  color: #f0a070;
  margin-bottom: 20px;
  display: flex;
  align-items: flex-start;
  gap: 8px;
}
.terms-wrap {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 24px;
  margin-top: 4px;
}
.terms-wrap input[type="checkbox"] {
  width: 15px;
  height: 15px;
  margin-top: 2px;
  accent-color: var(--rust);
  cursor: pointer;
  flex-shrink: 0;
}
.terms-wrap span { font-size: 12px; color: var(--mist); line-height: 1.5; }
.terms-wrap span a { color: var(--rust); text-decoration: none; }
.btn-submit {
  width: 100%;
  padding: 14px;
  background: var(--rust);
  color: #fff;
  border: none;
  border-radius: 6px;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 18px;
  letter-spacing: 3px;
  cursor: pointer;
  transition: background 0.2s, transform 0.1s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}
.btn-submit:hover { background: var(--rust-dim); }
.btn-submit:active { transform: scale(0.98); }
.selected-role {
  margin-top: 8px;
  padding: 8px 12px;
  border-radius: 6px;
  font-size: 12px;
  color: var(--mist);
  border: 1px solid rgba(255,255,255,0.06);
  background: var(--vein);
  display: none;
  align-items: center;
  gap: 8px;
}
.selected-role.show { display: flex; }
.selected-role.admin       { border-color: rgba(200,68,10,0.3);   color: #e87040; }
.selected-role.admin_trans { border-color: rgba(200,68,10,0.2);   color: #f0a070; }
.selected-role.manager     { border-color: rgba(232,167,48,0.3);  color: #e8a730; }
.selected-role.driver      { border-color: rgba(99,179,237,0.3);  color: #63b3ed; }
.selected-role.karyawan    { border-color: rgba(29,158,117,0.3);  color: #1d9e75; }
.scan-line {
  position: fixed;
  left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(200,68,10,0.3), transparent);
  animation: scan 10s linear infinite;
  pointer-events: none;
  z-index: 0;
}
@keyframes scan {
  0%   { top: -2px; opacity: 0; }
  5%   { opacity: 1; }
  95%  { opacity: 1; }
  100% { top: 100vh; opacity: 0; }
}
</style>
</head>
<body>
<div class="scan-line"></div>

<div class="left-panel">
  <div class="brand">
    <div class="brand-icon"><i class="ti ti-truck"></i></div>
    <div>
      <div class="brand-name">VehiclePool</div>
      <div class="brand-sub">Mining Transport System</div>
    </div>
  </div>
  <div class="middle-content">
    <div class="step-label">Buat Akun</div>
    <div class="display-title">BERGABUNG<span>BERSAMA.</span></div>
    <p class="desc">Pilih role yang sesuai dengan jabatan Anda di perusahaan tambang nikel kami.</p>
    <div class="role-info">
      <div class="ri-card">
        <div class="ri-icon admin"><i class="ti ti-shield-check"></i></div>
        <div>
          <span class="ri-title">Admin</span>
          <div class="ri-desc">Kelola semua data sistem, user, dan konfigurasi</div>
        </div>
      </div>
      <div class="ri-card">
        <div class="ri-icon admin_trans"><i class="ti ti-truck-delivery"></i></div>
        <div>
          <span class="ri-title">Admin Transportasi</span>
          <div class="ri-desc">Assign driver & kendaraan, kelola trip dan laporan</div>
        </div>
      </div>
      <div class="ri-card">
        <div class="ri-icon manager"><i class="ti ti-user-check"></i></div>
        <div>
          <span class="ri-title">Manager</span>
          <div class="ri-desc">Menyetujui atau menolak permohonan trip secara berjenjang</div>
        </div>
      </div>
      <div class="ri-card">
        <div class="ri-icon driver"><i class="ti ti-steering-wheel"></i></div>
        <div>
          <span class="ri-title">Driver</span>
          <div class="ri-desc">Menjalankan trip yang telah diassign dan dilaporkan</div>
        </div>
      </div>
      <div class="ri-card">
        <div class="ri-icon karyawan"><i class="ti ti-user"></i></div>
        <div>
          <span class="ri-title">Karyawan</span>
          <div class="ri-desc">Buat pemesanan kendaraan dan pantau status perjalanan</div>
        </div>
      </div>
    </div>
  </div>
  <div class="bottom-note">© 2025 VehiclePool — Mining Transport</div>
</div>

<div class="right-panel">
  <div class="form-box">
    <div class="form-headline">
      <h2>DAFTAR</h2>
      <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </div>

    @if($errors->any())
    <div class="alert-error">
      <i class="ti ti-alert-circle" style="font-size:18px;flex-shrink:0;margin-top:1px;"></i>
      <div>
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="field">
        <label>Nama Lengkap</label>
        <div class="input-wrap">
          <i class="ti ti-user icon-left"></i>
          <input type="text" name="nama_user" value="{{ old('nama_user') }}"
            placeholder="John Doe" required autocomplete="name">
        </div>
      </div>

      <div class="field">
        <label>Email</label>
        <div class="input-wrap">
          <i class="ti ti-mail icon-left"></i>
          <input type="email" name="email_user" value="{{ old('email_user') }}"
            placeholder="nama@perusahaan.com" required autocomplete="email">
        </div>
      </div>

      <div class="field">
        <label>Role / Jabatan</label>
        <div class="input-wrap">
          <i class="ti ti-briefcase icon-left"></i>
          <select name="role_user" required onchange="updateRoleHint(this.value)">
            <option value="">-- Pilih Role --</option>
            <option value="admin"       {{ old('role_user') == 'admin'       ? 'selected' : '' }}>Admin</option>
            <option value="admin_trans" {{ old('role_user') == 'admin_trans' ? 'selected' : '' }}>Admin Transportasi</option>
            <option value="manager"     {{ old('role_user') == 'manager'     ? 'selected' : '' }}>Manager / Approver</option>
            <option value="driver"      {{ old('role_user') == 'driver'      ? 'selected' : '' }}>Driver / Sopir</option>
            <option value="karyawan"    {{ old('role_user') == 'karyawan'    ? 'selected' : '' }}>Karyawan / Staff</option>
          </select>
        </div>
        <div class="selected-role" id="roleHint">
          <i id="roleHintIcon" class="ti ti-info-circle"></i>
          <span id="roleHintText"></span>
        </div>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="input-wrap">
          <i class="ti ti-lock icon-left"></i>
          <input type="password" name="pass_user" id="passInput"
            placeholder="Min. 8 karakter" required autocomplete="new-password"
            oninput="checkStrength(this.value)">
          <button type="button" class="eye-btn" onclick="togglePass('passInput','eyeIcon1')">
            <i class="ti ti-eye" id="eyeIcon1"></i>
          </button>
        </div>
        <div class="pw-strength">
          <div class="pw-bar" id="bar1"></div>
          <div class="pw-bar" id="bar2"></div>
          <div class="pw-bar" id="bar3"></div>
          <span class="pw-label" id="pwLabel">—</span>
        </div>
      </div>

      <div class="field">
        <label>Konfirmasi Password</label>
        <div class="input-wrap">
          <i class="ti ti-lock-check icon-left"></i>
          <input type="password" name="pass_user_confirmation" id="passInput2"
            placeholder="Ulangi password" required autocomplete="new-password">
          <button type="button" class="eye-btn" onclick="togglePass('passInput2','eyeIcon2')">
            <i class="ti ti-eye" id="eyeIcon2"></i>
          </button>
        </div>
      </div>

      <label class="terms-wrap">
        <input type="checkbox" required>
        <span>Saya menyetujui <a href="#">syarat & ketentuan</a> penggunaan sistem VehiclePool</span>
      </label>

      <button type="submit" class="btn-submit">
        <i class="ti ti-user-plus"></i>
        BUAT AKUN
      </button>
    </form>
  </div>
</div>

<script>
function togglePass(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon  = document.getElementById(iconId);
  input.type  = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'ti ti-eye' : 'ti ti-eye-off';
}

function checkStrength(val) {
  const b1  = document.getElementById('bar1');
  const b2  = document.getElementById('bar2');
  const b3  = document.getElementById('bar3');
  const lbl = document.getElementById('pwLabel');
  let score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  b1.className = 'pw-bar'; b2.className = 'pw-bar'; b3.className = 'pw-bar';
  if (score === 1) {
    b1.className = 'pw-bar weak'; lbl.textContent = 'Lemah';
  } else if (score === 2) {
    b1.className = 'pw-bar medium'; b2.className = 'pw-bar medium'; lbl.textContent = 'Sedang';
  } else if (score === 3) {
    b1.className = 'pw-bar strong'; b2.className = 'pw-bar strong'; b3.className = 'pw-bar strong'; lbl.textContent = 'Kuat';
  } else {
    lbl.textContent = '—';
  }
}

const roleHints = {
  admin:       { icon: 'ti-shield-check',    text: 'Dapat mengelola semua data sistem, user, dan konfigurasi',        cls: 'admin'       },
  admin_trans: { icon: 'ti-truck-delivery',  text: 'Assign driver & kendaraan, kelola trip dan export laporan',       cls: 'admin_trans' },
  manager:     { icon: 'ti-user-check',      text: 'Bertanggung jawab menyetujui atau menolak permohonan trip',       cls: 'manager'     },
  driver:      { icon: 'ti-steering-wheel',  text: 'Menjalankan trip yang diassign dan melaporkan perjalanan',        cls: 'driver'      },
  karyawan:    { icon: 'ti-user',            text: 'Dapat membuat pemesanan dan memantau status kendaraan',           cls: 'karyawan'    },
};

function updateRoleHint(val) {
  const hint     = document.getElementById('roleHint');
  const icon     = document.getElementById('roleHintIcon');
  const text     = document.getElementById('roleHintText');
  hint.className = 'selected-role';
  if (!val || !roleHints[val]) return;
  const r = roleHints[val];
  icon.className    = 'ti ' + r.icon;
  text.textContent  = r.text;
  hint.classList.add('show', r.cls);
}
</script>
</body>
</html>