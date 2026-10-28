<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Mahasiswa – SI-AKAD</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-900: #14532d;
            --green-800: #166534;
            --green-700: #15803d;
            --green-600: #16a34a;
            --green-500: #22c55e;
            --green-400: #4ade80;
            --green-300: #86efac;
            --green-200: #bbf7d0;
            --green-100: #dcfce7;
            --green-50:  #f0fdf4;
            --teal-700:  #0f766e;
            --teal-600:  #0d9488;
            --teal-500:  #14b8a6;
            --bg:        #f0f5f1;
            --white:     #ffffff;
            --text-dark: #0f172a;
            --text-mid:  #334155;
            --text-muted:#64748b;
            --border:    #e2e8f0;
            --radius:    12px;
            --sidebar-w: 220px;
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

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--green-900);
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
            padding: 20px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.12);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-icon svg { width: 20px; height: 20px; color: #fff; }

        .brand-texts { line-height: 1.2; }
        .brand-texts .b1 { font-size: 15px; font-weight: 800; color: #fff; letter-spacing: -0.3px; }
        .brand-texts .b2 { font-size: 9px; font-weight: 500; color: rgba(255,255,255,0.55); line-height: 1.35; }

        .sidebar-nav { flex: 1; padding: 14px 0; overflow-y: auto; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.6);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s ease;
            position: relative;
            margin: 2px 10px;
            border-radius: 8px;
        }
        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }

        .nav-item.active {
            background: var(--green-600);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(22,163,74,0.3);
        }

        /* ===================== MAIN WRAPPER ===================== */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===================== TOPBAR ===================== */
        .topbar {
            background: var(--white);
            height: 72px;
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 0 28px;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-welcome { flex: 1; }
        .topbar-welcome .tw-greeting { font-size: 13px; color: var(--text-muted); font-weight: 500; }
        .topbar-welcome .tw-name { font-size: 22px; font-weight: 900; color: var(--green-900); letter-spacing: -0.3px; }
        .topbar-welcome .tw-sub { font-size: 12px; color: var(--text-muted); margin-top: 1px; }

        .topbar-search { position: relative; display: flex; align-items: center; }
        .topbar-search svg {
            position: absolute; left: 12px;
            width: 16px; height: 16px; color: var(--text-muted);
        }
        .topbar-search input {
            width: 260px; height: 38px;
            padding: 0 14px 0 38px;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            font-size: 13px; font-family: inherit;
            color: var(--text-dark); background: var(--bg);
            outline: none; transition: all 0.2s;
        }
        .topbar-search input::placeholder { color: var(--text-muted); }
        .topbar-search input:focus { border-color: var(--green-600); background: #fff; }

        .topbar-user {
            display: flex; align-items: center; gap: 10px;
            cursor: pointer; padding: 6px 10px; border-radius: 10px;
            transition: background 0.2s;
        }
        .topbar-user:hover { background: var(--bg); }

        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--green-700), var(--teal-500));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 800; color: #fff;
            flex-shrink: 0;
        }

        .user-info { line-height: 1.3; }
        .user-info .uname { font-size: 13px; font-weight: 700; color: var(--text-dark); }
        .user-info .urole { font-size: 11px; color: var(--text-muted); }

        /* ===================== CONTENT ===================== */
        .page-content { flex: 1; padding: 24px 28px; max-width: 1360px; width: 100%; }

        /* HEADER ROW */
        .welcome-header {
            display: flex; align-items: center; gap: 16px; margin-bottom: 22px;
        }
        .wh-avatar {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--green-700), var(--teal-500));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 21px; font-weight: 900; color: #fff; flex-shrink: 0;
        }
        .wh-text .wh1 { font-size: 13px; color: var(--text-muted); }
        .wh-text .wh2 { font-size: 26px; font-weight: 900; color: var(--green-900); letter-spacing: -0.4px; }
        .wh-text .wh3 { font-size: 12.5px; color: var(--text-muted); margin-top: 2px; }

        /* ===================== STAT CARDS ===================== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 18px 20px;
            border: 1px solid var(--border);
            display: flex; align-items: center; gap: 14px;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); transform: translateY(-2px); }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon svg { width: 24px; height: 24px; }

        .si-green  { background: var(--green-100); color: var(--green-700); }
        .si-teal   { background: #ccfbf1; color: var(--teal-700); }
        .si-amber  { background: #fef3c7; color: #b45309; }
        .si-emerald{ background: #d1fae5; color: #047857; }

        .stat-info .val { font-size: 24px; font-weight: 900; color: var(--text-dark); letter-spacing: -0.5px; line-height: 1; }
        .stat-info .label { font-size: 11.5px; font-weight: 600; color: var(--text-muted); margin-top: 3px; }
        .stat-info .sub-label { font-size: 10.5px; color: var(--text-muted); margin-top: 1px; }

        .stat-status-badge {
            font-size: 13px; font-weight: 800; color: var(--green-700);
            display: flex; align-items: center; gap: 6px;
        }
        .stat-status-badge svg { width: 22px; height: 22px; }

        /* ===================== MAIN GRID (3 columns) ===================== */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 260px;
            gap: 14px;
            margin-bottom: 18px;
        }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px 0;
        }
        .card-title {
            font-size: 13.5px; font-weight: 800; color: var(--text-dark);
            display: flex; align-items: center; gap: 8px;
        }
        .card-title .dot { width: 8px; height: 8px; background: var(--green-600); border-radius: 50%; }
        .card-badge {
            font-size: 11px; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
            border: 1px solid var(--border); color: var(--text-muted); background: var(--bg);
        }
        .card-body { padding: 14px 20px 18px; }

        /* ===================== JADWAL HARI INI ===================== */
        .jadwal-list { display: flex; flex-direction: column; gap: 10px; }

        .jadwal-item {
            background: #f8faf9;
            border: 1px solid #e8f0eb;
            border-radius: 10px;
            padding: 14px 16px;
            transition: box-shadow 0.15s;
        }
        .jadwal-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

        .jadwal-time {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 6px;
        }
        .jadwal-time .jt-range { font-size: 12px; font-weight: 700; color: var(--text-dark); }

        .badge-berlangsung {
            font-size: 10px; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
            background: #fef3c7; color: #b45309;
            border: 1px solid #fde68a;
        }

        .jadwal-matkul { font-size: 13px; font-weight: 800; color: var(--green-800); margin-bottom: 4px; letter-spacing: -0.2px; }
        .jadwal-detail { font-size: 11.5px; color: var(--text-muted); line-height: 1.5; }
        .jadwal-detail span { color: var(--text-mid); font-weight: 600; }

        /* ===================== KRS SECTION ===================== */
        .krs-content {
            display: flex; flex-direction: column; align-items: center;
            gap: 14px; padding: 14px 20px 18px;
        }

        .krs-circle {
            width: 100px; height: 100px;
            border-radius: 50%;
            border: 6px solid var(--teal-500);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            background: var(--white);
        }
        .krs-circle .kc-val { font-size: 24px; font-weight: 900; color: var(--teal-700); line-height: 1; }
        .krs-circle .kc-label { font-size: 10px; font-weight: 700; color: var(--text-muted); }

        .krs-details { width: 100%; display: flex; flex-direction: column; gap: 6px; }
        .krs-row {
            display: flex; justify-content: space-between;
            font-size: 12px; color: var(--text-mid);
        }
        .krs-row .kr-label { font-weight: 500; }
        .krs-row .kr-val { font-weight: 800; color: var(--text-dark); }

        .krs-status {
            display: flex; align-items: center; gap: 8px;
            background: var(--green-100);
            border: 1px solid var(--green-200);
            border-radius: 8px;
            padding: 10px 14px;
            width: 100%;
        }
        .krs-status svg { width: 18px; height: 18px; color: var(--green-700); flex-shrink: 0; }
        .krs-status .ks-text { font-size: 11.5px; font-weight: 700; color: var(--green-800); line-height: 1.3; }
        .krs-status .ks-sub { font-size: 10px; font-weight: 500; color: var(--text-muted); }

        /* ===================== INFORMASI ===================== */
        .info-panel { padding: 14px 18px 18px; }

        .info-item {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-item:last-child { border-bottom: none; }

        .info-dot {
            width: 8px; height: 8px;
            background: var(--green-500);
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 4px;
        }
        .info-text { font-size: 12px; color: var(--text-mid); font-weight: 500; line-height: 1.5; }
        .info-text strong { color: var(--text-dark); font-weight: 700; }

        /* ===================== BOTTOM ROW ===================== */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 260px;
            gap: 14px;
        }

        /* IPK Chart */
        .ipk-wrap {
            display: flex; align-items: center; gap: 20px;
            padding: 14px 20px 18px;
        }
        .ipk-chart-box { flex: 1; height: 160px; }

        .ipk-score-box {
            display: flex; flex-direction: column; align-items: center;
            background: #f1fdf6;
            border: 1px solid var(--green-200);
            border-radius: 12px;
            padding: 16px 20px;
            min-width: 100px;
        }
        .ipk-score-label { font-size: 10px; font-weight: 700; color: var(--text-muted); margin-bottom: 4px; }
        .ipk-score-val { font-size: 32px; font-weight: 900; color: var(--green-800); line-height: 1; }

        /* TUGAS */
        .tugas-list { padding: 14px 20px 18px; display: flex; flex-direction: column; gap: 10px; }

        .tugas-item {
            background: #fafbfc;
            border: 1px solid #eef1f5;
            border-radius: 10px;
            padding: 12px 14px;
        }
        .tugas-num { font-size: 11px; font-weight: 800; color: var(--green-700); margin-bottom: 3px; }
        .tugas-type { font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; }
        .tugas-name { font-size: 12.5px; font-weight: 800; color: var(--text-dark); margin: 2px 0; }
        .tugas-deadline { font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 4px; }
        .tugas-deadline svg { width: 12px; height: 12px; }

        /* KEUANGAN */
        .keuangan-panel { padding: 14px 18px 18px; }
        .keuangan-status {
            background: var(--green-100);
            border: 1px solid var(--green-200);
            border-radius: 10px;
            padding: 18px 16px;
            text-align: center;
        }
        .keuangan-status svg {
            width: 36px; height: 36px;
            color: var(--green-700);
            margin-bottom: 8px;
        }
        .keuangan-status p { font-size: 12.5px; font-weight: 600; color: var(--green-800); line-height: 1.5; }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .content-grid, .bottom-grid { grid-template-columns: 1fr; }
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
            <div class="b2">Sistem Informasi Akademik<br>Institut Informatika &amp; Komputer</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-item active" href="/dashboard-mahasiswa" id="nav-beranda">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Beranda
        </a>
        <a class="nav-item" href="#" id="nav-krs">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            KRS
        </a>
        <a class="nav-item" href="#" id="nav-jadwal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Jadwal Kuliah
        </a>
        <a class="nav-item" href="#" id="nav-nilai">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Nilai
        </a>
        <a class="nav-item" href="#" id="nav-khs">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Kartu Hasil Studi (KHS)
        </a>
        <a class="nav-item" href="#" id="nav-data-akademik">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Data Akademik
        </a>
        <a class="nav-item" href="#" id="nav-keuangan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Keuangan
        </a>
        <a class="nav-item" href="#" id="nav-surat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            Pengajuan Surat
        </a>
        <a class="nav-item" href="#" id="nav-pengaturan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            Pengaturan
        </a>
    </nav>
</aside>

<!-- ======== MAIN ======== -->
<div class="main-wrap">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-welcome">
            <div class="tw-greeting">Selamat Datang,</div>
            <div class="tw-name">Mahasiswa</div>
            <div class="tw-sub">S1 Sistem Informasi &nbsp;|&nbsp; NPM 11523000</div>
        </div>

        <div class="topbar-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="search-input" placeholder="Cari informasi…">
        </div>

        <div class="topbar-user" id="topbar-user-menu">
            <div class="user-avatar">M</div>
            <div class="user-info">
                <div class="uname">Mahasiswa</div>
                <div class="urole">S1 Sistem Informasi</div>
            </div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        <!-- Welcome Header -->
        <div class="welcome-header">
            <div class="wh-avatar">M</div>
            <div class="wh-text">
                <div class="wh1">Selamat Datang,</div>
                <div class="wh2">Mahasiswa</div>
                <div class="wh3">S1 Sistem Informasi &nbsp;|&nbsp; NPM 11523000</div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-grid">
            <div class="stat-card" id="stat-sks">
                <div class="stat-icon si-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <div class="stat-info">
                    <div class="val">22 SKS</div>
                    <div class="label">Total SKS Diambil</div>
                    <div class="sub-label">dari 22 SKS</div>
                </div>
            </div>

            <div class="stat-card" id="stat-matkul">
                <div class="stat-icon si-teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="stat-info">
                    <div class="val">7</div>
                    <div class="label">Mata Kuliah Aktif</div>
                    <div class="sub-label">Semester 7</div>
                </div>
            </div>

            <div class="stat-card" id="stat-ipk">
                <div class="stat-icon si-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <div class="stat-info">
                    <div class="val">4.00</div>
                    <div class="label">IPK Semester Ini</div>
                    <div class="sub-label">Dari 4.00</div>
                </div>
            </div>

            <div class="stat-card" id="stat-status">
                <div class="stat-icon si-emerald">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-status-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Aktif
                    </div>
                    <div class="label">Status Akademik</div>
                    <div class="sub-label">Semester Ganjil 2026/2027</div>
                </div>
            </div>
        </div>

        <!-- MIDDLE ROW: Jadwal + KRS + Informasi -->
        <div class="content-grid">
            <!-- Jadwal Kuliah Hari Ini -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><span class="dot"></span> Jadwal Kuliah Hari Ini</div>
                    <div class="card-badge">Kamis, 15 Okt 2026</div>
                </div>
                <div class="card-body">
                    <div class="jadwal-list">
                        <div class="jadwal-item">
                            <div class="jadwal-time">
                                <span class="jt-range">14:00 – 16:30</span>
                                <span class="badge-berlangsung">Berlangsung</span>
                            </div>
                            <div class="jadwal-matkul">COLLABORATIVE COMPUTING</div>
                            <div class="jadwal-detail">
                                Offline – Gedung SH – Lab C<br>
                                Sesi 5 &nbsp;| &nbsp;<span>Hartanto Satyo Nugraha</span>
                            </div>
                        </div>
                        <div class="jadwal-item">
                            <div class="jadwal-time">
                                <span class="jt-range">14:00 – 16:30</span>
                                <span class="badge-berlangsung">Berlangsung</span>
                            </div>
                            <div class="jadwal-matkul">COLLABORATIVE COMPUTING</div>
                            <div class="jadwal-detail">
                                Offline – Gedung SH – Lab C<br>
                                Sesi 5 &nbsp;| &nbsp;<span>Hartanto Satyo Nugraha</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KRS Semester -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><span class="dot"></span> KRS Semester Ganjil 2026–2027</div>
                </div>
                <div class="krs-content">
                    <div class="krs-circle">
                        <div class="kc-val">22</div>
                        <div class="kc-label">SKS</div>
                    </div>
                    <div class="krs-details">
                        <div class="krs-row"><span class="kr-label">Matkul Diambil</span><span class="kr-val">7</span></div>
                        <div class="krs-row"><span class="kr-label">SKS Terpenuhi</span><span class="kr-val">22</span></div>
                        <div class="krs-row"><span class="kr-label">SKS Maksimal</span><span class="kr-val">22</span></div>
                    </div>
                    <div class="krs-status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <div>
                            <div class="ks-text">KRS Sudah Disetujui</div>
                            <div class="ks-sub">Terakhir diperbarui 15 Okt 2026, 14.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><span class="dot"></span> Informasi</div>
                </div>
                <div class="info-panel">
                    <div class="info-item">
                        <div class="info-dot"></div>
                        <div class="info-text">Ada Materi baru, <strong>TI SERVICE MANAGEMENT</strong> sesi 6</div>
                    </div>
                    <div class="info-item">
                        <div class="info-dot"></div>
                        <div class="info-text">Ada Materi baru, <strong>SOCIAL RESPONSIBILITY</strong> sesi 6</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTTOM ROW: IPK Chart + Tugas + Keuangan -->
        <div class="bottom-grid">
            <!-- IPK & Prestasi -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><span class="dot"></span> IPK &amp; Prestasi Akademik</div>
                </div>
                <div class="ipk-wrap">
                    <div class="ipk-chart-box">
                        <canvas id="ipkChart"></canvas>
                    </div>
                    <div class="ipk-score-box">
                        <div class="ipk-score-label">IPK saat ini</div>
                        <div class="ipk-score-val">3.90</div>
                    </div>
                </div>
            </div>

            <!-- Tugas -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><span class="dot"></span> Tugas yang perlu Di kerjakan</div>
                </div>
                <div class="tugas-list">
                    <div class="tugas-item">
                        <div class="tugas-num">1. Studi Kasus</div>
                        <div class="tugas-name">COLLABORATIVE COMPUTING</div>
                        <div class="tugas-deadline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Batas waktu: Kamis, 15 Okt 2026, 14.00
                        </div>
                    </div>
                    <div class="tugas-item">
                        <div class="tugas-num">2. Praktik</div>
                        <div class="tugas-name">SOCIAL RESPONSIBILITY</div>
                        <div class="tugas-deadline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Batas waktu: Kamis, 30 Okt 2026, 00.00
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keuangan -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><span class="dot"></span> Keuangan</div>
                </div>
                <div class="keuangan-panel">
                    <div class="keuangan-status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <p>Anda sudah melakukan pembayaran pada semester ini</p>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
// ========== IPK LINE CHART ==========
(function () {
    const ctx = document.getElementById('ipkChart').getContext('2d');
    const labels = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6', 'Sem 7'];
    const data   = [3.45, 3.55, 3.60, 3.72, 3.80, 3.85, 3.90];

    const gradient = ctx.createLinearGradient(0, 0, 0, 160);
    gradient.addColorStop(0, 'rgba(22,163,74,0.25)');
    gradient.addColorStop(1, 'rgba(22,163,74,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'IPK',
                data,
                borderColor: '#16a34a',
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#16a34a',
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
                    backgroundColor: '#14532d',
                    titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
                    bodyFont:  { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => ' IPK: ' + ctx.parsed.y.toFixed(2)
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#64748b' }
                },
                y: {
                    min: 3.0,
                    max: 4.0,
                    grid: { color: '#f1f5f9', lineWidth: 1 },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#64748b',
                             stepSize: 0.2,
                             callback: v => v.toFixed(2) }
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
</script>
</body>
</html>
