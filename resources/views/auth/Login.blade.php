<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VehiclePool — Masuk</title>
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
  overflow: hidden;
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
  width: 52%;
  background: var(--ore);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 48px 56px;
  position: relative;
  border-right: 1px solid var(--line);
  z-index: 1;
}
.left-panel::after {
  content: '';
  position: absolute;
  top: 0; right: -1px; bottom: 0;
  width: 3px;
  background: linear-gradient(to bottom, transparent 0%, var(--rust) 30%, var(--rust) 70%, transparent 100%);
}
.brand { display: flex; align-items: center; gap: 14px; }
.brand-icon {
  width: 48px; height: 48px;
  background: var(--rust);
  clip-path: polygon(0 20%, 100% 0, 100% 80%, 0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  color: #fff;
  flex-shrink: 0;
}
.brand-name {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 28px;
  letter-spacing: 3px;
  color: var(--chalk);
  line-height: 1;
}
.brand-sub {
  font-family: 'Space Mono', monospace;
  font-size: 10px;
  color: var(--mist);
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-top: 3px;
}
.hero-text { margin-top: auto; margin-bottom: auto; }
.hero-label {
  font-family: 'Space Mono', monospace;
  font-size: 11px;
  color: var(--rust);
  letter-spacing: 3px;
  text-transform: uppercase;
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 24px;
}
.hero-label::before {
  content: '';
  display: block;
  width: 32px;
  height: 1px;
  background: var(--rust);
}
.hero-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 86px;
  line-height: 0.9;
  letter-spacing: 2px;
  color: var(--chalk);
  margin-bottom: 24px;
}
.hero-title span { color: var(--rust); display: block; }
.hero-desc {
  font-size: 14px;
  line-height: 1.8;
  color: var(--mist);
  max-width: 340px;
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
.form-box { width: 100%; max-width: 380px; }
.form-headline { margin-bottom: 40px; }
.form-headline h2 {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 38px;
  letter-spacing: 2px;
  color: var(--chalk);
  line-height: 1;
  margin-bottom: 8px;
}
.form-headline p { font-size: 13px; color: var(--mist); }
.form-headline p a { color: var(--rust); text-decoration: none; font-weight: 500; }
.form-headline p a:hover { text-decoration: underline; }
.field { margin-bottom: 18px; }
.field label {
  display: block;
  font-family: 'Space Mono', monospace;
  font-size: 10px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--mist);
  margin-bottom: 8px;
}
.input-wrap { position: relative; }
.input-wrap i.icon-left {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 16px;
  color: var(--mist);
  pointer-events: none;
  transition: color 0.2s;
}
.input-wrap input {
  width: 100%;
  padding: 13px 14px 13px 42px;
  background: var(--vein);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 6px;
  color: var(--chalk);
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.input-wrap input:focus {
  border-color: var(--rust);
  box-shadow: 0 0 0 3px rgba(200, 68, 10, 0.15);
}
.eye-btn {
  position: absolute;
  right: 12px;
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
.form-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
}
.checkbox-wrap {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}
.checkbox-wrap input[type="checkbox"] {
  width: 15px;
  height: 15px;
  accent-color: var(--rust);
  cursor: pointer;
}
.checkbox-wrap span { font-size: 12.5px; color: var(--mist); }
.forgot-link { font-size: 12.5px; color: var(--rust); text-decoration: none; }
.forgot-link:hover { text-decoration: underline; }
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
.alert-error {
  background: rgba(200,68,10,0.12);
  border: 1px solid rgba(200,68,10,0.3);
  border-radius: 6px;
  padding: 10px 14px;
  font-size: 13px;
  color: #f0a070;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.scan-line {
  position: fixed;
  left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(200,68,10,0.3), transparent);
  animation: scan 8s linear infinite;
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
  <div class="hero-text">
    <div class="hero-label">Fleet Management</div>
    <div class="hero-title">KENDALI<span>ARMADA.</span></div>
    <p class="hero-desc">Sistem manajemen kendaraan terpadu untuk operasional tambang nikel. Monitor konsumsi BBM, jadwal servis, dan setiap perjalanan secara real-time.</p>
  </div>

</div>

<div class="right-panel">
  <div class="form-box">
    <div class="form-headline">
      <h2>MASUK</h2>
      <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    </div>

    @if($errors->any())
    <div class="alert-error">
      <i class="ti ti-alert-circle" style="font-size:18px;flex-shrink:0;"></i>
      <div>{{ $errors->first() }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error">
      <i class="ti ti-alert-circle" style="font-size:18px;flex-shrink:0;"></i>
      <div>{{ session('error') }}</div>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="field">
        <label>Email</label>
        <div class="input-wrap">
          <i class="ti ti-mail icon-left"></i>
          <input type="email" name="email" value="{{ old('email') }}"
            placeholder="akun@gmail.com" required autocomplete="email">
        </div>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="input-wrap">
          <i class="ti ti-lock icon-left"></i>
          <input type="password" name="password" id="passInput"
            placeholder="••••••••" required autocomplete="current-password">
          <button type="button" class="eye-btn" onclick="togglePass()">
            <i class="ti ti-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="form-meta">
        <label class="checkbox-wrap">
          <input type="checkbox" name="remember">
          <span>Ingat saya</span>
        </label>
        <a href="#" class="forgot-link">Lupa password?</a>
      </div>

      <button type="submit" class="btn-submit">
        <i class="ti ti-login"></i>
        MASUK SEKARANG
      </button>
    </form>

  </div>
</div>

<script>
function togglePass() {
  const input = document.getElementById('passInput');
  const icon  = document.getElementById('eyeIcon');
  input.type  = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'ti ti-eye' : 'ti ti-eye-off';
}
</script>
</body>
</html>