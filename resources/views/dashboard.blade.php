<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin – SI-AKAD</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:       #0e2954;
            --navy-light: #1a3a6b;
            --navy-mid:   #16305a;
            --accent:     #3b82f6;
            --accent-2:   #60a5fa;
            --bg:         #f0f4fa;
            --white:      #ffffff;
            --text-dark:  #0f172a;
            --text-mid:   #334155;
            --text-muted: #64748b;
            --border:     #e2e8f0;
            --radius:     12px;
            --sidebar-w:  240px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
            user-select: none;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .brand-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.12);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg { width: 22px; height: 22px; color: #fff; }

        .brand-texts { line-height: 1.2; }
        .brand-texts .b1 { font-size: 16px; font-weight: 800; color: #fff; letter-spacing: -0.3px; }
        .brand-texts .b2 { font-size: 10px; font-weight: 500; color: rgba(255,255,255,0.6); }

        .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; }

        .nav-section-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1px;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            padding: 14px 20px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
            cursor: pointer;
            border-radius: 0;
            text-decoration: none;
            transition: all 0.18s ease;
            position: relative;
            margin: 2px 10px;
            border-radius: 8px;
        }

        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }

        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .nav-item.active {
            background: rgba(59,130,246,0.25);
            color: #93c5fd;
            font-weight: 700;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--accent-2);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            cursor: pointer;
            transition: background 0.2s;
        }

        .sidebar-user:hover { background: rgba(255,255,255,0.1); }

        .user-avatar-sm {
            width: 34px; height: 34px;
            background: var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }

        .user-info-sm { flex: 1; min-width: 0; }
        .user-info-sm .uname { font-size: 12.5px; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-info-sm .urole { font-size: 11px; color: rgba(255,255,255,0.5); }

        /* ===== MAIN ===== */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: var(--white);
            height: 64px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 0 28px;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-muted);
            flex: 1;
        }

        .topbar-breadcrumb .sep { opacity: 0.5; }
        .topbar-breadcrumb .current { font-weight: 700; color: var(--text-dark); }

        .topbar-search {
            position: relative;
            display: flex;
            align-items: center;
        }

        .topbar-search svg {
            position: absolute;
            left: 12px;
            width: 16px; height: 16px;
            color: var(--text-muted);
        }

        .topbar-search input {
            width: 280px;
            height: 38px;
            padding: 0 14px 0 38px;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text-dark);
            background: var(--bg);
            outline: none;
            transition: all 0.2s;
        }

        .topbar-search input::placeholder { color: var(--text-muted); }
        .topbar-search input:focus { border-color: var(--accent); background: #fff; }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-btn {
            width: 38px; height: 38px;
            border: 1.5px solid var(--border);
            border-radius: 50%;
            background: var(--white);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .icon-btn svg { width: 17px; height: 17px; color: var(--text-muted); }
        .icon-btn:hover { border-color: var(--accent); background: #eff6ff; }

        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 10px;
            transition: background 0.2s;
        }

        .topbar-user:hover { background: var(--bg); }

        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13.5px; font-weight: 800; color: #fff;
        }

        .user-info { line-height: 1.3; }
        .user-info .uname { font-size: 13px; font-weight: 700; color: var(--text-dark); }
        .user-info .urole { font-size: 11px; color: var(--text-muted); }

        /* ===== CONTENT ===== */
        .page-content {
            flex: 1;
            padding: 28px;
            max-width: 1340px;
            width: 100%;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 24px;
        }

        .page-header-avatar {
            width: 54px; height: 54px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 800; color: #fff;
            flex-shrink: 0;
        }

        .page-header-text .title { font-size: 24px; font-weight: 900; color: var(--text-dark); letter-spacing: -0.4px; }
        .page-header-text .subtitle { font-size: 13.5px; color: var(--text-muted); margin-top: 2px; }

        /* ===== STAT CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 20px 22px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: box-shadow 0.2s, transform 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg { width: 26px; height: 26px; }

        .si-blue   { background: #dbeafe; color: #1d4ed8; }
        .si-pink   { background: #fce7f3; color: #be185d; }
        .si-orange { background: #ffedd5; color: #c2410c; }
        .si-green  { background: #dcfce7; color: #15803d; }

        .stat-info .val { font-size: 26px; font-weight: 900; color: var(--text-dark); letter-spacing: -0.5px; line-height: 1; }
        .stat-info .label { font-size: 12.5px; font-weight: 600; color: var(--text-muted); margin-top: 4px; }
        .stat-info .trend {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
            padding: 2px 8px;
            border-radius: 20px;
        }
        .trend-up   { background: #dcfce7; color: #15803d; }
        .trend-down { background: #fee2e2; color: #b91c1c; }

        .stat-card-arrow {
            margin-left: auto;
            color: var(--text-muted);
            opacity: 0.4;
        }

        .stat-card-arrow svg { width: 18px; height: 18px; }

        /* ===== MIDDLE ROW ===== */
        .mid-row {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 16px;
            margin-bottom: 24px;
        }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px 0;
        }

        .card-title { font-size: 14.5px; font-weight: 800; color: var(--text-dark); display: flex; align-items: center; gap: 8px; }
        .card-title .dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; }

        .card-badge {
            font-size: 11px; font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid var(--border);
            color: var(--text-muted);
            background: var(--bg);
            display: flex; align-items: center; gap: 5px;
            cursor: default;
        }

        .card-body { padding: 16px 22px 20px; }

        /* chart container */
        .chart-wrap { position: relative; height: 220px; }

        /* ===== DONUT STATS ===== */
        .donut-wrap { display: flex; align-items: center; gap: 24px; padding: 16px 22px 22px; }
        .donut-canvas-wrap { position: relative; width: 160px; height: 160px; flex-shrink: 0; }
        .donut-center-label {
            position: absolute; inset: 0;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            pointer-events: none;
        }
        .donut-center-label .dcval { font-size: 22px; font-weight: 900; color: var(--text-dark); line-height: 1; }
        .donut-center-label .dclabel { font-size: 10px; font-weight: 600; color: var(--text-muted); }

        .donut-legend { flex: 1; display: flex; flex-direction: column; gap: 10px; }
        .legend-item { display: flex; align-items: center; gap: 10px; }
        .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .legend-text .lname { font-size: 12.5px; font-weight: 700; color: var(--text-dark); }
        .legend-text .lpct { font-size: 11.5px; color: var(--text-muted); }

        /* ===== BOTTOM ROW ===== */
        .bottom-row {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 16px;
        }

        /* ===== AKTIVITAS ===== */
        .activity-list { display: flex; flex-direction: column; gap: 0; }
        .activity-item {
            display: grid;
            grid-template-columns: 40px 1fr auto;
            gap: 12px;
            align-items: start;
            padding: 14px 22px;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background: #f8fafc; }

        .activity-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12.5px; font-weight: 700;
            flex-shrink: 0;
            color: #fff;
        }

        .activity-text .atitle { font-size: 13px; font-weight: 600; color: var(--text-dark); line-height: 1.4; }
        .activity-text .adesc { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; line-height: 1.4; }
        .activity-time { font-size: 11px; color: var(--text-muted); white-space: nowrap; padding-top: 2px; }

        .see-all-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            cursor: pointer;
        }
        .see-all-link svg { width: 13px; height: 13px; }
        .see-all-link:hover { text-decoration: underline; }

        /* ===== KALENDER ===== */
        .calendar-wrap { padding: 4px 22px 20px; }

        .calendar-event {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .calendar-event:last-child { border-bottom: none; }

        .cal-date {
            width: 42px;
            text-align: center;
            flex-shrink: 0;
        }
        .cal-date .cday { font-size: 20px; font-weight: 900; color: var(--text-dark); line-height: 1; }
        .cal-date .cmon { font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }

        .cal-info { flex: 1; min-width: 0; }
        .cal-info .cname { font-size: 12.5px; font-weight: 700; color: var(--text-dark); line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .cal-info .ctime { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        .cal-badge {
            font-size: 10.5px; font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            flex-shrink: 0;
        }
        .cb-blue   { background: #dbeafe; color: #1d4ed8; }
        .cb-red    { background: #fee2e2; color: #b91c1c; }
        .cb-green  { background: #dcfce7; color: #15803d; }
        .cb-orange { background: #ffedd5; color: #c2410c; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .mid-row, .bottom-row { grid-template-columns: 1fr; }
        }

        @media (max-width: 720px) {
            :root { --sidebar-w: 0px; }
            .sidebar { display: none; }
            .stats-grid { grid-template-columns: 1fr; }
            .page-content { padding: 16px; }
        }
    </style>
</head>
<body>

<!-- ======== SIDEBAR ======== -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>
        <div class="brand-texts">
            <div class="b1">SI-AKAD</div>
            <div class="b2">Institut Informatika &amp; Komputer</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-item active" href="/dashboard" id="nav-dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>

        <div class="nav-section-label">Manajemen</div>

        <a class="nav-item" href="#" id="nav-pengguna">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Manajemen Pengguna
        </a>

        <a class="nav-item" href="#" id="nav-master">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Master Data Akademik
        </a>

        <a class="nav-item" href="#" id="nav-gedung">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            Manajemen Gedung
        </a>

        <div class="nav-section-label">Akademik</div>

        <a class="nav-item" href="#" id="nav-penugasan-dosen">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Penugasan Dosen
        </a>

        <a class="nav-item" href="#" id="nav-jadwal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Jadwal Kuliah &amp; Ujian
        </a>

        <a class="nav-item" href="#" id="nav-krs">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            KRS &amp; KHS
        </a>

        <div class="nav-section-label">Sistem</div>

        <a class="nav-item" href="#" id="nav-pengumuman">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3z"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            Pengumuman
        </a>

        <a class="nav-item" href="#" id="nav-audit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Audit Log
        </a>

        <a class="nav-item" href="#" id="nav-pengaturan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 1.41 1.41M4.93 4.93a10 10 0 0 0-1.41 1.41M4.93 19.07a10 10 0 0 1-1.41-1.41M19.07 19.07a10 10 0 0 0 1.41-1.41M12 2v2M12 20v2M2 12H0M24 12h-2"/></svg>
            Pengaturan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user" id="sidebar-logout-btn" title="Logout">
            <div class="user-avatar-sm">AA</div>
            <div class="user-info-sm">
                <div class="uname">Admin Akademik</div>
                <div class="urole">Administrator</div>
            </div>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </div>
    </div>
</aside>

<!-- ======== MAIN ======== -->
<div class="main-wrap">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-breadcrumb">
            <span>Beranda</span>
            <span class="sep">/</span>
            <span class="current">Dashboard</span>
        </div>

        <div class="topbar-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="topbar-search-input" placeholder="Cari mahasiswa, dosen atau program studi…">
        </div>

        <div class="topbar-actions">
            <div class="icon-btn" id="topbar-notif-btn" title="Notifikasi">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3z"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span class="notif-dot"></span>
            </div>

            <div class="topbar-user" id="topbar-user-menu">
                <div class="user-avatar">AA</div>
                <div class="user-info">
                    <div class="uname">Admin Akademik</div>
                    <div class="urole">Administrator</div>
                </div>
            </div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-avatar">AA</div>
            <div class="page-header-text">
                <div class="title">Dashboard Admin</div>
                <div class="subtitle">Ringkasan aktivitas akademik</div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-grid">
            <a class="stat-card" href="#" id="stat-mahasiswa">
                <div class="stat-icon si-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <div class="stat-info">
                    <div class="val" id="sv-mahasiswa">2.450</div>
                    <div class="label">Total Mahasiswa</div>
                    <div class="trend trend-up">↑ 5% dari semester lalu</div>
                </div>
                <div class="stat-card-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></div>
            </a>

            <a class="stat-card" href="#" id="stat-dosen">
                <div class="stat-icon si-pink">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="stat-info">
                    <div class="val" id="sv-dosen">128</div>
                    <div class="label">Total Dosen</div>
                    <div class="trend trend-up">↑ 3% dari semester lalu</div>
                </div>
                <div class="stat-card-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></div>
            </a>

            <a class="stat-card" href="#" id="stat-prodi">
                <div class="stat-icon si-orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <div class="stat-info">
                    <div class="val" id="sv-prodi">12</div>
                    <div class="label">Program Studi</div>
                    <div class="trend trend-up">↑ 8% dari semester lalu</div>
                </div>
                <div class="stat-card-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></div>
            </a>

            <a class="stat-card" href="#" id="stat-matkul">
                <div class="stat-icon si-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <div class="stat-info">
                    <div class="val" id="sv-matkul">186</div>
                    <div class="label">Mata Kuliah</div>
                    <div class="trend trend-down">↓ 1% dari semester lalu</div>
                </div>
                <div class="stat-card-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></div>
            </a>
        </div>

        <!-- MID ROW: LINE CHART + DONUT -->
        <div class="mid-row">
            <!-- Line Chart -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="dot"></span>
                        Statistik Mahasiswa
                    </div>
                    <div class="card-badge">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        6 Bulan Terakhir
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-wrap">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Donut Chart -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="dot"></span>
                        Statistik Mahasiswa
                    </div>
                </div>
                <div class="donut-wrap">
                    <div class="donut-canvas-wrap">
                        <canvas id="donutChart"></canvas>
                        <div class="donut-center-label">
                            <div class="dcval">2.450</div>
                            <div class="dclabel">Total</div>
                        </div>
                    </div>
                    <div class="donut-legend">
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#1d4ed8;"></div>
                            <div class="legend-text">
                                <div class="lname">Teknik Informatika</div>
                                <div class="lpct">32%</div>
                            </div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#60a5fa;"></div>
                            <div class="legend-text">
                                <div class="lname">Sistem Informasi</div>
                                <div class="lpct">32%</div>
                            </div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#bfdbfe;"></div>
                            <div class="legend-text">
                                <div class="lname">Manajemen</div>
                                <div class="lpct">36%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTTOM ROW: AKTIVITAS + KALENDER -->
        <div class="bottom-row">
            <!-- Aktivitas Terbaru -->
            <div class="card">
                <div class="card-header" style="padding-bottom: 4px;">
                    <div class="card-title">
                        <span class="dot"></span>
                        Aktivitas Terbaru
                    </div>
                    <a class="see-all-link" href="#" id="link-lihat-semua-aktivitas">
                        Lihat semua
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-avatar" style="background:#3b82f6;">MH</div>
                        <div class="activity-text">
                            <div class="atitle">Mahasiswa baru ditambahkan</div>
                            <div class="adesc">Mahasiswa Daftar: FCG3008 – telah ditambahkan ke program studi sistem informasi</div>
                        </div>
                        <div class="activity-time">19 Okt 2025<br>09:14</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background:#8b5cf6;">DS</div>
                        <div class="activity-text">
                            <div class="atitle">Dosen baru ditambahkan</div>
                            <div class="adesc">Dr. Ali Munawar, S.T., M.Kom. telah ditambahkan sebagai dosen pengajar</div>
                        </div>
                        <div class="activity-time">18 Okt 2025<br>14:22</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background:#10b981;">JD</div>
                        <div class="activity-text">
                            <div class="atitle">Jadwal Kuliah diperbarui</div>
                            <div class="adesc">Jadwal kuliah semester 2024/2025 sudah diperbarui</div>
                        </div>
                        <div class="activity-time">17 Okt 2025<br>11:15</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background:#f59e0b;">NK</div>
                        <div class="activity-text">
                            <div class="atitle">Nilai diinput</div>
                            <div class="adesc">Nilai mata kuliah pemrograman Web telah diinput oleh dosen</div>
                        </div>
                        <div class="activity-time">16 Okt 2025<br>08:52</div>
                    </div>
                </div>
            </div>

            <!-- Kalender Akademik -->
            <div class="card">
                <div class="card-header" style="padding-bottom: 4px;">
                    <div class="card-title">
                        <span class="dot"></span>
                        Kalender Akademik
                    </div>
                    <a class="see-all-link" href="#" id="link-lihat-semua-kalender">
                        Lihat Semua
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>
                <div class="calendar-wrap">
                    <div class="calendar-event">
                        <div class="cal-date">
                            <div class="cday">16</div>
                            <div class="cmon">Okt</div>
                        </div>
                        <div class="cal-info">
                            <div class="cname">Pembukaan Semester Genap 2025/2026</div>
                            <div class="ctime">All Day</div>
                        </div>
                        <div class="cal-badge cb-blue">Akademik</div>
                    </div>
                    <div class="calendar-event">
                        <div class="cal-date">
                            <div class="cday">18</div>
                            <div class="cmon">Okt</div>
                        </div>
                        <div class="cal-info">
                            <div class="cname">Batas Akhir Pendaftaran KRS</div>
                            <div class="ctime">17:00 WIB</div>
                        </div>
                        <div class="cal-badge cb-red">Deadline</div>
                    </div>
                    <div class="calendar-event">
                        <div class="cal-date">
                            <div class="cday">1</div>
                            <div class="cmon">Nov</div>
                        </div>
                        <div class="cal-info">
                            <div class="cname">Ujian Tengah Semester (UTS)</div>
                            <div class="ctime">Sesuai Jadwal</div>
                        </div>
                        <div class="cal-badge cb-orange">Ujian</div>
                    </div>
                    <div class="calendar-event">
                        <div class="cal-date">
                            <div class="cday">5</div>
                            <div class="cmon">Nov</div>
                        </div>
                        <div class="cal-info">
                            <div class="cname">Batas akhir input nilai UAS</div>
                            <div class="ctime">Hingga 23:59</div>
                        </div>
                        <div class="cal-badge cb-green">Nilai</div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div><!-- end main-wrap -->

<script>
// ========== LINE CHART ==========
(function () {
    const ctx = document.getElementById('lineChart').getContext('2d');
    const labels = ['Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'];
    const data   = [2180, 2240, 2300, 2350, 2400, 2450];

    const gradient = ctx.createLinearGradient(0, 0, 0, 220);
    gradient.addColorStop(0, 'rgba(59,130,246,0.25)');
    gradient.addColorStop(1, 'rgba(59,130,246,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data,
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
                    bodyFont:  { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.y.toLocaleString('id') + ' mahasiswa'
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 12 }, color: '#64748b' }
                },
                y: {
                    beginAtZero: false,
                    min: 2000,
                    grid: { color: '#f1f5f9', lineWidth: 1 },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b',
                             callback: v => v.toLocaleString('id') }
                }
            }
        }
    });
})();

// ========== DONUT CHART ==========
(function () {
    const ctx = document.getElementById('donutChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Teknik Informatika', 'Sistem Informasi', 'Manajemen'],
            datasets: [{
                data: [784, 784, 882],
                backgroundColor: ['#1d4ed8', '#60a5fa', '#bfdbfe'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverBorderColor: '#fff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
                    bodyFont:  { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.toLocaleString('id') + ' mahasiswa'
                    }
                }
            }
        }
    });
})();

// ========== NAV ACTIVE ==========
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function (e) {
        if (this.getAttribute('href') === '#') e.preventDefault();
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        this.classList.add('active');
    });
});

// ========== LOGOUT ==========
document.getElementById('sidebar-logout-btn').addEventListener('click', function () {
    if (!confirm('Apakah Anda yakin ingin keluar dari SI-AKAD?')) return;
    fetch('/api/logout', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' }
    }).then(() => { window.location.href = '/login'; }).catch(() => { window.location.href = '/login'; });
});
</script>
</body>
</html>
