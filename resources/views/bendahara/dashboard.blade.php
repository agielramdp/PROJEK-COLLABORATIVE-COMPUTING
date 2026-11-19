<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Bendahara - SI-AKAD | Institut Informatika & Komputer</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --color-sidebar-bg: #a17917; /* Warm Mustard Gold / Ochre SESUAI GAMBAR */
            --color-sidebar-hover: rgba(255, 255, 255, 0.12);
            --color-sidebar-active: rgba(255, 255, 255, 0.22);
            --color-sidebar-text: #fffbeb;
            --color-avatar-blue: #60a5fa;
            --color-bg-page: #f8fafc;
            --color-border: #e2e8f0;
            --color-text-dark: #1e293b;
            --color-text-muted: #64748b;
            --color-success: #10b981;
            --color-warning: #f59e0b;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--color-bg-page);
            color: var(--color-text-dark);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* SIDEBAR (GOLD / OCHRE THEME SESUAI GAMBAR) */
        .sidebar {
            width: 250px;
            background-color: var(--color-sidebar-bg);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            min-height: 100vh;
            position: sticky;
            top: 0;
            padding: 22px 14px 18px 14px;
            z-index: 50;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.08);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 8px 20px 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
            text-decoration: none;
            color: #ffffff;
        }

        .brand-logo-wrap {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-text-col {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-sub1 {
            font-size: 10.5px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.3;
        }

        .brand-sub2 {
            font-size: 9px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.2;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex-grow: 1;
            list-style: none;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--color-sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-item:hover {
            background-color: var(--color-sidebar-hover);
            color: #ffffff;
        }

        .nav-item.active {
            background-color: var(--color-sidebar-active);
            color: #ffffff;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.9;
        }

        .sidebar-footer {
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 14px;
            background: rgba(255, 255, 255, 0.12);
            border: none;
            color: #ffffff;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            color: #ffffff;
        }

        /* MAIN WRAPPER */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--color-bg-page);
            overflow-y: auto;
        }

        .top-navbar {
            height: 68px;
            padding: 0 36px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 20px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .header-search {
            position: relative;
            width: 320px;
        }

        .header-search input {
            width: 100%;
            padding: 8px 16px 8px 36px;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            font-family: inherit;
            font-size: 12.5px;
            color: var(--color-text-dark);
            outline: none;
        }

        .header-search .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 15px;
            color: #94a3b8;
        }

        .notif-btn {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: #334155;
            display: flex;
            border-radius: 50%;
        }

        .notif-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background-color: #ef4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ffffff;
        }

        .header-user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .header-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #0f172a;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        /* PAGE BODY */
        .page-content {
            padding: 28px 36px 60px 36px;
            max-width: 1380px;
            width: 100%;
        }

        /* PAGE HEADER BENDAHARA (SESUAI GAMBAR) */
        .page-header-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .avatar-silhouette-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background-color: var(--color-avatar-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(96, 165, 250, 0.35);
            flex-shrink: 0;
        }

        .page-header-text {
            display: flex;
            flex-direction: column;
        }

        .welcome-subtitle {
            font-size: 13.5px;
            font-weight: 500;
            color: #64748b;
        }

        .page-title {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.3px;
            line-height: 1.2;
            margin: 1px 0;
        }

        .page-meta-sub {
            font-size: 13px;
            font-weight: 500;
            color: #475569;
        }

        /* 4 STAT CARDS ATAS */
        .stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 16px 18px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
        }

        .stat-icon-square {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon-square.dark { background-color: #1e293b; color: #ffffff; }
        .stat-icon-square.purple { background-color: #7c3aed; color: #ffffff; }
        .stat-icon-square.yellow { background-color: #fef08a; color: #eab308; }
        .stat-icon-square.green { background-color: #dcfce7; color: #16a34a; }

        .stat-info-col {
            display: flex;
            flex-direction: column;
        }

        .stat-label-top {
            font-size: 11.5px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 2px;
        }

        .stat-number-main {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .stat-sub-text {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }

        /* 3 COLUMNS SECTION */
        .content-three-grid {
            display: grid;
            grid-template-columns: 40% 36% calc(24% - 24px);
            gap: 18px;
            margin-bottom: 24px;
        }

        .dashboard-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card-header-row {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title-group {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .card-badge-date {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }

        .card-body {
            padding: 16px 18px;
            flex-grow: 1;
        }

        /* JADWAL LIST */
        .jadwal-item {
            padding: 12px 14px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #f1f5f9;
        }

        .jadwal-item:last-child {
            margin-bottom: 0;
        }

        .jadwal-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .jadwal-time {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
        }

        .badge-berlangsung {
            font-size: 10.5px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
            background-color: #d1fae5;
            color: #065f46;
        }

        .jadwal-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .jadwal-loc {
            font-size: 11px;
            color: #64748b;
        }

        /* CIRCULAR PROGRESS KRS */
        .krs-circle-box {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .krs-round-badge {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid #10b981;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #0f172a;
            flex-shrink: 0;
            font-size: 14px;
        }

        .krs-round-badge span {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
        }

        .krs-stats-col {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 11.5px;
            color: #475569;
        }

        .krs-stats-col span.val {
            font-weight: 700;
            color: #0f172a;
        }

        .krs-approved-box {
            padding: 10px 14px;
            background: #ede9fe;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: #5b21b6;
        }

        /* INFORMASI LIST */
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: #334155;
            line-height: 1.4;
        }

        .info-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #10b981;
            margin-top: 5px;
            flex-shrink: 0;
        }

        /* BOTTOM 3 COLUMNS */
        .task-item {
            padding: 10px 12px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 12px;
            border: 1px solid #f1f5f9;
        }

        .task-item:last-child {
            margin-bottom: 0;
        }

        .task-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .task-due {
            font-size: 10.5px;
            color: #64748b;
        }

        .keuangan-box {
            padding: 16px;
            text-align: center;
            font-size: 12.5px;
            color: #475569;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        @media (max-width: 1100px) {
            .stat-cards-grid { grid-template-columns: repeat(2, 1fr); }
            .content-three-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; min-height: auto; position: static; }
            .header-search { display: none; }
            .page-content { padding: 16px; }
            .stat-cards-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR BENDAHARA (WARM GOLD / OCHRE THEME) -->
    <aside class="sidebar">
        <a href="{{ route('bendahara.dashboard') }}" class="brand-header">
            <div class="brand-logo-wrap">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" width="38" height="38">
                    <path d="M12 30C12 30 25 38 50 38C75 38 88 30 88 30C88 30 92 68 50 94C8 68 12 30 12 30Z" fill="#ffffff" fill-opacity="0.12"/>
                    <path d="M22 40C22 40 32 44 47 45V78C32 77 22 72 22 72V40Z" fill="#FFFFFF"/>
                    <path d="M78 40C78 40 68 44 53 45V78C68 77 78 72 78 72V40Z" fill="#FFFFFF"/>
                    <path d="M50 8L62 23H38L50 8Z" fill="#F5C036"/>
                    <circle cx="50" cy="24" r="3.5" fill="#F5C036"/>
                    <path d="M48 45H52V76H48V45Z" fill="#F5C036"/>
                </svg>
            </div>
            <div class="brand-text-col">
                <span class="brand-title">SI-AKAD</span>
                <span class="brand-sub1">Sistem Informasi Akademik</span>
                <span class="brand-sub2">Institut Informatika & Komputer</span>
            </div>
        </a>

        <ul class="nav-menu">
            <li>
                <a href="{{ route('bendahara.dashboard') }}" class="nav-item active">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Beranda</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span>KRS</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>Jadwal kuliah</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    <span>Nilai</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    <span>Kartu Hasil Studi (KHS)</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <span>Data Akademik</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    <span>Keuangan</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <span>Pengajuan Surat</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <button type="button" class="logout-btn" onclick="handleLogout()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Keluar Sistem</span>
            </button>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-wrapper">
        <header class="top-navbar">
            <div class="header-search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" placeholder="Cari Informasi...">
            </div>

            <button type="button" class="notif-btn" title="Notifikasi">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="notif-badge">1</span>
            </button>

            <div class="header-user-profile">
                <div class="header-avatar">M</div>
                <span class="header-user-name">Mahasiswa</span>
            </div>
        </header>

        <div class="page-content">
            <!-- PAGE HEADER BENDAHARA (SESUAI GAMBAR) -->
            <div class="page-header-row">
                <div class="avatar-silhouette-circle">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="page-header-text">
                    <span class="welcome-subtitle">Selamat Datang,</span>
                    <h1 class="page-title">Bendahara</h1>
                    <div class="page-meta-sub">S1 Sistem Informasi &nbsp;|&nbsp; NPM 11523000</div>
                </div>
            </div>

            <!-- 4 STAT CARDS ATAS -->
            <div class="stat-cards-grid">
                <div class="stat-card">
                    <div class="stat-icon-square dark">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    <div class="stat-info-col">
                        <span class="stat-label-top">Total SKS Diambil</span>
                        <span class="stat-number-main">22 SKS</span>
                        <span class="stat-sub-text">dari 22 SKS</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-square purple">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </div>
                    <div class="stat-info-col">
                        <span class="stat-label-top">Mata Kuliah Aktif</span>
                        <span class="stat-number-main">7</span>
                        <span class="stat-sub-text">Semester 7</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-square yellow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <div class="stat-info-col">
                        <span class="stat-label-top">IPK Semester Ini</span>
                        <span class="stat-number-main">4.00</span>
                        <span class="stat-sub-text">Dari 4.00</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-square green">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="stat-info-col">
                        <span class="stat-label-top">Status Akademik</span>
                        <span class="stat-number-main" style="color: #16a34a;">Aktif</span>
                        <span class="stat-sub-text">Semester Ganjil 2026/2027</span>
                    </div>
                </div>
            </div>

            <!-- MIDDLE 3 COLUMNS -->
            <div class="content-three-grid">
                <!-- 1. JADWAL KULIAH HARI INI -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span>Jadwal Kuliah Hari Ini</span>
                        </div>
                        <span class="card-badge-date">Kamis, 15 Okt 2026</span>
                    </div>
                    <div class="card-body">
                        <div class="jadwal-item">
                            <div class="jadwal-top">
                                <span class="jadwal-time">14:00 - 16:30</span>
                                <span class="badge-berlangsung">Berlangsung</span>
                            </div>
                            <div class="jadwal-title">COLLABORATIVE COMPUTING</div>
                            <div class="jadwal-loc">Offline - Gedung SH - Lab C<br>Sesi 5 | Hartanto Satyo Nugraha</div>
                        </div>
                        <div class="jadwal-item">
                            <div class="jadwal-top">
                                <span class="jadwal-time">14:00 - 16:30</span>
                                <span class="badge-berlangsung">Berlangsung</span>
                            </div>
                            <div class="jadwal-title">COLLABORATIVE COMPUTING</div>
                            <div class="jadwal-loc">Offline - Gedung SH - Lab C<br>Sesi 5 | Hartanto Satyo Nugraha</div>
                        </div>
                    </div>
                </div>

                <!-- 2. KRS SEMESTER GANJIL -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <span>KRS Semester Ganjil 2026-2027</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="krs-circle-box">
                            <div class="krs-round-badge">
                                22
                                <span>SKS</span>
                            </div>
                            <div class="krs-stats-col">
                                <div>Matkul Diambil: <span class="val">7</span></div>
                                <div>SKS Terpenuhi: <span class="val">22</span></div>
                                <div>SKS Maksimal: <span class="val">22</span></div>
                            </div>
                        </div>
                        <div class="krs-approved-box">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <div>
                                <div style="font-weight: 700;">KRS Sudah Disetujui</div>
                                <div style="font-size: 10.5px; opacity: 0.85;">Terakhir diperbarui 15 Okt 2026, 14:00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. INFORMASI -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            <span>Informasi</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="info-list">
                            <div class="info-item">
                                <span class="info-dot"></span>
                                <span>Ada Materi baru, TI SERVICE MANAGEMENT sesi 5</span>
                            </div>
                            <div class="info-item">
                                <span class="info-dot"></span>
                                <span>Ada Materi baru, SOCIAL RESPONSIBILITY sesi 6</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTTOM 3 COLUMNS -->
            <div class="content-three-grid">
                <!-- 1. IPK & PRESTASI -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                            <span>IPK & Prestasi Akademik</span>
                        </div>
                    </div>
                    <div class="card-body" style="display: flex; gap: 14px; align-items: center;">
                        <div style="flex-grow: 1; height: 110px; position: relative;">
                            <canvas id="ipkChartBendahara"></canvas>
                        </div>
                        <div style="padding: 10px 14px; background: #fef3c7; border-radius: 8px; text-align: center; flex-shrink: 0;">
                            <div style="font-size: 10.5px; color: #92400e; font-weight: 600;">IPK saat ini</div>
                            <div style="font-size: 20px; font-weight: 800; color: #78350f;">3.90</div>
                            <div style="font-size: 10px; color: #b45309; font-weight: 700;">↑ 0.10</div>
                        </div>
                    </div>
                </div>

                <!-- 2. TUGAS -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <span>Tugas yang perlu Di kerjakan</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="task-item">
                            <div class="task-title">1. Studi Kasus COLLABORATIVE COMPUTING</div>
                            <div class="task-due">Batas waktu: Kamis, 15 Okt 2026, 14:00</div>
                        </div>
                        <div class="task-item">
                            <div class="task-title">2. Praktik SOCIAL RESPONSIBILITY</div>
                            <div class="task-due">Batas waktu: Kamis, 30 Okt 2026, 08:00</div>
                        </div>
                    </div>
                </div>

                <!-- 3. KEUANGAN -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                            <span>Keuangan</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="keuangan-box">
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.8" style="margin-bottom: 8px;">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                                <line x1="12" y1="6" x2="12" y2="8"/>
                                <line x1="12" y1="16" x2="12" y2="18"/>
                            </svg>
                            <p style="font-weight: 500;">Anda sudah melakukan pembayaran pada semester ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('ipkChartBendahara').getContext('2d');
            const goldGradient = ctx.createLinearGradient(0, 0, 0, 110);
            goldGradient.addColorStop(0, 'rgba(217, 119, 6, 0.25)');
            goldGradient.addColorStop(1, 'rgba(217, 119, 6, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Smt 1', 'Smt 2', 'Smt 3', 'Smt 4', 'Smt 5'],
                    datasets: [{
                        data: [3.4, 3.55, 3.6, 3.8, 3.9],
                        borderColor: '#d97706',
                        backgroundColor: goldGradient,
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { display: false },
                        y: { min: 3.0, max: 4.0, display: false }
                    }
                }
            });
        });

        function handleLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari sistem SI-AKAD?')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                fetch('/api/logout', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                }).finally(() => {
                    window.location.href = '/login';
                });
            }
        }
    </script>
</body>
</html>
