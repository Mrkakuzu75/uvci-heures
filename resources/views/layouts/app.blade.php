<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','UVCI') — Gestion des heures</title>
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="192x192" href="/uvci_logo_192.png">
<link rel="icon" type="image/png" sizes="512x512" href="/uvci_logo_512.png">
<link rel="apple-touch-icon" href="/uvci_logo_192.png">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --green:#00C07F; --green-dark:#009962; --green-light:#E6FBF3;
    --orange:#FF6B35; --navy:#0D1B2A; --navy-mid:#1A2E42;
    --muted:#6B7A8D; --border:#E2E8F0; --bg:#F4F6FA; --white:#ffffff;
  }
  body { font-family:'Segoe UI',Arial,sans-serif; background:var(--bg); color:var(--navy); min-height:100vh; }

  /* ── SIDEBAR ───────────────────────────────────────── */
  .sidebar {
    width:240px; background:var(--navy); display:flex; flex-direction:column;
    position:fixed; top:0; left:0; bottom:0; z-index:100;
    transition:transform .3s ease;
  }
  .sidebar-logo {
    padding:18px 16px; display:flex; align-items:center; gap:10px;
    border-bottom:1px solid rgba(255,255,255,.07);
  }
  .logo-mark { width:36px;height:36px;background:var(--green);border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:14px;color:var(--navy);flex-shrink:0; }
  .logo-info { flex:1;min-width:0; }
  .logo-name { font-weight:700;font-size:14px;color:#fff;display:block; }
  .logo-role { font-size:10px;color:rgba(255,255,255,.35);display:block;margin-top:2px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap; }
  .sidebar-close { display:none;background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;padding:4px; }

  nav { flex:1;padding:12px 10px;overflow-y:auto;display:flex;flex-direction:column;gap:2px; }
  .nav-link {
    display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;
    font-size:13.5px;color:rgba(255,255,255,.55);text-decoration:none;
    transition:all .15s;position:relative;
  }
  .nav-link:hover { background:rgba(255,255,255,.07);color:rgba(255,255,255,.85); }
  .nav-link.active { background:rgba(0,192,127,.15);color:var(--green); }
  .nav-link svg { width:16px;height:16px;flex-shrink:0; }
  .nav-link .badge { margin-left:auto;background:var(--orange);color:#fff;font-size:10px;font-weight:600;padding:1px 6px;border-radius:20px; }

  .sidebar-user {
    padding:10px;border-top:1px solid rgba(255,255,255,.07);
  }
  .user-box { display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;transition:background .15s; }
  .user-box:hover { background:rgba(255,255,255,.07); }
  .user-avatar { width:34px;height:34px;border-radius:10px;background:var(--green);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:var(--navy);flex-shrink:0; }
  .user-name { font-size:13px;font-weight:500;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:120px; }
  .user-role { font-size:10px;color:rgba(255,255,255,.35);text-transform:capitalize;margin-top:1px; }
  .logout-btn { background:none;border:none;cursor:pointer;color:rgba(255,255,255,.3);padding:4px;border-radius:6px;transition:color .15s;flex-shrink:0; }
  .logout-btn:hover { color:#f87171; }

  /* ── MAIN ──────────────────────────────────────────── */
  .main { margin-left:240px; min-height:100vh; display:flex;flex-direction:column; }

  /* ── TOPBAR ────────────────────────────────────────── */
  .topbar {
    background:#fff;border-bottom:1px solid var(--border);height:64px;
    display:flex;align-items:center;justify-content:space-between;
    padding:0 28px;position:sticky;top:0;z-index:50;
  }
  .topbar-left { display:flex;align-items:center;gap:12px; }
  .burger-btn { display:none;background:none;border:none;cursor:pointer;padding:6px;border-radius:8px; }
  .burger-btn:hover { background:var(--bg); }
  .page-title { font-weight:700;font-size:17px;color:var(--navy); }
  .page-sub { font-size:12px;color:var(--muted);margin-top:1px; }
  .topbar-actions { display:flex;align-items:center;gap:8px; }

  /* ── FLASH MESSAGES ────────────────────────────────── */
  .flash { margin:16px 28px 0;padding:12px 16px;border-radius:12px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:8px; }
  .flash-success { background:var(--green-light);border:1px solid rgba(0,192,127,.3);color:var(--green-dark); }
  .flash-error   { background:#FEF2F2;border:1px solid #FCA5A5;color:#DC2626; }

  /* ── CONTENT ───────────────────────────────────────── */
  .content { padding:24px 28px;flex:1; }

  /* ── CARDS ─────────────────────────────────────────── */
  .card { background:#fff;border-radius:16px;border:1px solid var(--border); }

  /* ── KPI ────────────────────────────────────────────── */
  .kpi-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px; }
  .kpi-card { background:#fff;border-radius:16px;border:1px solid var(--border);padding:20px 24px;transition:transform .15s,box-shadow .15s; }
  .kpi-card:hover { transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.08); }
  .kpi-icon { font-size:24px;margin-bottom:12px; }
  .kpi-value { font-weight:700;font-size:28px;color:var(--navy);line-height:1; }
  .kpi-label { font-size:12px;color:var(--muted);margin-top:4px; }
  .kpi-bar { margin-top:12px;height:4px;background:var(--border);border-radius:4px;overflow:hidden; }
  .kpi-fill { height:100%;border-radius:4px; }

  /* ── TABLES ────────────────────────────────────────── */
  table { width:100%;border-collapse:collapse; }
  thead tr { background:#FAFBFC; }
  th { padding:10px 16px;text-align:left;font-size:11px;font-weight:500;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;white-space:nowrap; }
  td { padding:12px 16px;border-top:1px solid #F0F2F5;font-size:13.5px; }
  tbody tr:hover { background:#FAFBFC; }
  .table-wrap { overflow-x:auto; }

  /* ── BADGES ─────────────────────────────────────────── */
  .badge-green  { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500;background:var(--green-light);color:var(--green-dark); }
  .badge-blue   { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500;background:#EBF3FF;color:#1A6FE0; }
  .badge-orange { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500;background:#FFF0EB;color:var(--orange); }
  .badge-purple { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500;background:#F5F0FF;color:#7C3AED; }
  .badge-gray   { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500;background:#F0F2F5;color:var(--muted); }

  /* ── AVATAR ─────────────────────────────────────────── */
  .avatar { width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:#fff;flex-shrink:0; }
  .av-green  { background:var(--green);color:var(--navy); }
  .av-blue   { background:#3B82F6; }
  .av-purple { background:#7C3AED; }
  .av-orange { background:var(--orange); }
  .av-teal   { background:#14B8A6; }

  /* ── BOUTONS ─────────────────────────────────────────── */
  .btn { display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;text-decoration:none;transition:all .15s;white-space:nowrap; }
  .btn-navy   { background:var(--navy);color:#fff; }
  .btn-navy:hover { background:var(--navy-mid); }
  .btn-green  { background:var(--green);color:var(--navy); }
  .btn-green:hover { background:var(--green-dark); }
  .btn-outline { background:transparent;color:var(--navy);border:1.5px solid var(--border); }
  .btn-outline:hover { border-color:var(--green);color:var(--green); }
  .btn-sm { padding:5px 12px;font-size:12px; }
  .btn-danger { background:transparent;color:#EF4444;border:1.5px solid var(--border); }
  .btn-danger:hover { border-color:#EF4444; }
  .btn svg { width:15px;height:15px; }

  /* ── FORMULAIRES ─────────────────────────────────────── */
  .form-label { display:block;font-size:13px;font-weight:500;color:var(--navy);margin-bottom:6px; }
  .form-input {
    width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
    font-size:14px;color:var(--navy);background:#FAFAFA;outline:none;transition:all .2s;
    font-family:inherit;
  }
  .form-input:focus { border-color:var(--green);background:#fff;box-shadow:0 0 0 3px rgba(0,192,127,.1); }
  .form-input::placeholder { color:#B0BAC7; }
  select.form-input { cursor:pointer; }
  .form-error { color:#EF4444;font-size:12px;margin-top:4px; }

  /* Role selector in forms */
  .role-selector { display:grid;grid-template-columns:repeat(3,1fr);gap:8px; }
  .role-selector label { cursor:pointer; }
  .role-selector input[type=radio] { display:none; }
  .rs-card { display:flex;flex-direction:column;align-items:center;padding:10px;border:1.5px solid var(--border);border-radius:10px;background:#FAFAFA;transition:all .2s; }
  .role-selector input:checked + .rs-card { border-color:var(--green);background:var(--green-light); }
  .role-selector label:hover .rs-card { border-color:var(--green); }
  .rs-icon { font-size:20px;margin-bottom:3px; }
  .rs-name { font-size:11px;font-weight:500;color:var(--muted); }
  .role-selector input:checked + .rs-card .rs-name { color:var(--green-dark);font-weight:600; }

  /* Grids */
  .grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
  .grid-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px; }
  .grid-auto { display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px; }

  /* Section card header */
  .card-header { padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px; }
  .card-header h3 { font-weight:700;font-size:15px;color:var(--navy); }
  .card-body { padding:20px; }
  .card-footer { padding:14px 20px;background:#FAFBFC;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between; }

  /* Pagination override */
  .pagination { display:flex;gap:4px;align-items:center;flex-wrap:wrap; }
  .pagination a, .pagination span { padding:5px 10px;border-radius:6px;font-size:12px;text-decoration:none;border:1px solid var(--border);color:var(--navy); }
  .pagination a:hover { border-color:var(--green);color:var(--green); }
  .pagination .active span, .pagination span[aria-current="page"] span { background:var(--navy);color:#fff;border-color:var(--navy); }

  /* Overlay mobile */
  .sidebar-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:99; }

  /* ── RESPONSIVE ──────────────────────────────────────── */
  @media(max-width:1023px) {
    .sidebar { transform:translateX(-100%); }
    .sidebar.open { transform:translateX(0); }
    .sidebar-close { display:block; }
    .sidebar-overlay.show { display:block; }
    .main { margin-left:0; }
    .burger-btn { display:flex;align-items:center;justify-content:center; }
    .topbar { padding:0 16px; }
    .content { padding:16px; }
    .kpi-grid { grid-template-columns:1fr 1fr; }
    .grid-2,.grid-3 { grid-template-columns:1fr; }
    .hide-mobile { display:none; }
  }
  @media(max-width:640px) {
    .kpi-grid { grid-template-columns:1fr 1fr; }
    .hide-sm { display:none; }
    .btn-text { display:none; }
  }
</style>
@stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

{{-- ════ SIDEBAR ════════════════════════════════════════════ --}}
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-mark">UV</div>
    <div class="logo-info">
      <span class="logo-name">UVCI</span>
      <span class="logo-role">@yield('sidebar-role','Gestion')</span>
    </div>
    <button class="sidebar-close" onclick="closeSidebar()">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>

  <nav>
    @yield('sidebar-nav')
  </nav>

  <div class="sidebar-user">
    <div class="user-box">
      <div class="user-avatar">
        {{ auth()->user()?->enseignant?->initiales ?? strtoupper(substr(auth()->user()?->login ?? 'U',0,2)) }}
      </div>
      <div style="flex:1;min-width:0;">
        <div class="user-name">{{ auth()->user()?->enseignant?->nom_complet ?? auth()->user()?->login }}</div>
        <div class="user-role">{{ auth()->user()?->role }}</div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn" title="Déconnexion">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
        </button>
      </form>
    </div>
  </div>
</aside>

{{-- ════ MAIN ════════════════════════════════════════════════ --}}
<div class="main">

  <header class="topbar">
    <div class="topbar-left">
      <button class="burger-btn" onclick="openSidebar()">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <div>
        <div class="page-title">@yield('page-title','Dashboard')</div>
        @hasSection('page-subtitle')
        <div class="page-sub">@yield('page-subtitle')</div>
        @endif
      </div>
    </div>
    <div class="topbar-actions">
      @yield('topbar-actions')
    </div>
  </header>

  @if(session('success'))
  <div class="flash flash-success">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div class="flash flash-error">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    {{ session('error') }}
  </div>
  @endif

  <div class="content">
    @yield('content')
  </div>
</div>

<script>
function openSidebar()  {
  document.getElementById('sidebar').classList.add('open');
  document.getElementById('overlay').classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('show');
  document.body.style.overflow = '';
}
</script>
@stack('scripts')
</body>
</html>
