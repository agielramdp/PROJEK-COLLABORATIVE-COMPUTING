<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Dosen Wali - SI-AKAD | Institut Informatika & Komputer</title>

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
            --color-sidebar-bg: #5c2a82;
            --color-sidebar-hover: rgba(255, 255, 255, 0.1);
            --color-sidebar-active: rgba(255, 255, 255, 0.16);
            --color-sidebar-text: #f1e6fa;
            --color-avatar-blue: #60a5fa;
            --color-bg-page: #f8fafc;
            --color-border: #e2e8f0;
            --color-text-dark: #1e293b;
            --color-text-muted: #64748b;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-teal: #14b8a6;
            --color-blue: #3b82f6;
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

        /* SIDEBAR (PURPLE THEME SESUAI GAMBAR) */
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
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
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
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.3;
        }

        .brand-sub2 {
            font-size: 9px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.7);
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
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.9;
        }

        .sidebar-footer {
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 14px;
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: #fce7f3;
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ffffff;
        }

        /* MAIN CONTENT */
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

        /* HEADER DOSEN WALI (SESUAI GAMBAR) */
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
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .stat-card.purple-border { border-color: #d8b4fe; }
        .stat-card.green-border { border-color: #a7f3d0; }
        .stat-card.yellow-border { border-color: #fde68a; }
        .stat-card.blue-border { border-color: #bfdbfe; }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
        }

        .stat-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon-box.purple { background-color: #5c2a82; color: #ffffff; }
        .stat-icon-box.green { background-color: #10b981; color: #ffffff; }
        .stat-icon-box.yellow { background-color: #f59e0b; color: #ffffff; }
        .stat-icon-box.blue { background-color: #2563eb; color: #ffffff; }

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
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .stat-sub-text {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }

        /* ROW 2: TABLE & DONUT (58% - 42%) */
        .mid-grid {
            display: grid;
            grid-template-columns: 58% calc(42% - 18px);
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
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title-group {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .link-see-all {
            font-size: 12px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
        }

        .card-body {
            padding: 16px 20px;
            flex-grow: 1;
        }

        /* TABLE DAFTAR MAHASISWA BIMBINGAN */
        .bimbingan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .bimbingan-table thead th {
            text-align: left;
            padding: 8px 10px;
            font-weight: 700;
            color: #334155;
            border-bottom: 1.5px solid #e2e8f0;
            font-size: 12px;
        }

        .bimbingan-table tbody td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
        }

        .bimbingan-table tbody tr:last-child td {
            border-bottom: none;
        }

        .user-avatar-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background-color: #60a5fa;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            margin-right: 8px;
        }

        .badge-status-krs {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-align: center;
        }

        .badge-status-krs.disetujui {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-status-krs.belum-disetujui {
            background-color: #fef3c7;
            color: #92400e;
        }

        .btn-detail-pill {
            padding: 3px 12px;
            background: #e2e8f0;
            border: none;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-detail-pill:hover {
            background: #cbd5e1;
            color: #0f172a;
        }

        /* DONUT STATUS KRS */
        .donut-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            height: 100%;
        }

        .donut-canvas-box {
            position: relative;
            width: 150px;
            height: 150px;
            flex-shrink: 0;
        }

        .donut-center-lbl {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .donut-center-val {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
        }

        .donut-center-sub {
            font-size: 10.5px;
            font-weight: 600;
            color: #64748b;
        }

        .donut-legend-col {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .donut-legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .donut-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        /* ROW 3: 3 CARDS */
        .bottom-three-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 18px;
        }

        /* SCHEDULE ITEM */
        .schedule-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .schedule-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .schedule-date-box {
            width: 40px;
            height: 40px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .schedule-date-box .sdb-d { font-size: 14px; font-weight: 800; line-height: 1; }
        .schedule-date-box .sdb-m { font-size: 9px; font-weight: 700; text-transform: uppercase; }

        .schedule-info {
            display: flex;
            flex-direction: column;
        }

        .schedule-title {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .schedule-time-room {
            font-size: 11px;
            color: #64748b;
        }

        /* ANNOUNCEMENTS */
        .announcement-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .announcement-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: #1e293b;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .announcement-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .ann-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #2563eb;
            margin-top: 6px;
            flex-shrink: 0;
        }

        .ann-dot.orange { background-color: #f97316; }
        .ann-dot.yellow { background-color: #eab308; }
        .ann-dot.teal { background-color: #0d9488; }

        .ann-text-group {
            display: flex;
            flex-direction: column;
        }

        .ann-title {
            font-weight: 600;
            color: #0f172a;
        }

        .ann-date {
            font-size: 10.5px;
            color: #94a3b8;
        }

        /* STAT BOX IN CHART */
        .chart-stat-floating {
            margin-top: 10px;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
        }

        .csf-val {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        @media (max-width: 1100px) {
            .stat-cards-grid { grid-template-columns: repeat(2, 1fr); }
            .mid-grid, .bottom-three-grid { grid-template-columns: 1fr; }
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

    <!-- SIDEBAR DOSEN WALI (PURPLE THEME) -->
    <aside class="sidebar">
        <a href="{{ route('dosen-wali.dashboard') }}" class="brand-header">
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
                <a href="{{ route('dosen-wali.dashboard') }}" class="nav-item active">
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
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>Mahasiswa bimbingan</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span>KRS Mahasiswa</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    <span>Nilai Mahasiswa</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                        <line x1="8" y1="21" x2="16" y2="21"/>
                        <line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
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
            <!-- PAGE HEADER (SESUAI GAMBAR) -->
            <div class="page-header-row">
                <div class="avatar-silhouette-circle">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="page-header-text">
                    <span class="welcome-subtitle">Selamat Datang,</span>
                    <h1 class="page-title">DOSEN WALI</h1>
                    <div class="page-meta-sub">Program studi &nbsp;:&nbsp; Sistem Informasi</div>
                    <div class="page-meta-sub">Tahun akademik &nbsp;:&nbsp; 2026/2027 genap</div>
                </div>
            </div>

            <!-- 4 STAT CARDS -->
            <div class="stat-cards-grid">
                <!-- 1 -->
                <div class="stat-card purple-border">
                    <div class="stat-icon-box purple">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div class="stat-info-col">
                        <span class="stat-label-top">Total mahasiswa bimbingan</span>
                        <span class="stat-number-main">24</span>
                        <span class="stat-sub-text">mahasiswa aktif</span>
                    </div>
                </div>

                <!-- 2 -->
                <div class="stat-card green-border">
                    <div class="stat-icon-box green">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <polyline points="9 15 11 17 15 13"/>
                        </svg>
                    </div>
                    <div class="stat-info-col">
                        <span class="stat-label-top">KRS disetujui</span>
                        <span class="stat-number-main">18</span>
                        <span class="stat-sub-text">dari 24 mahasiswa</span>
                    </div>
                </div>

                <!-- 3 -->
                <div class="stat-card yellow-border">
                    <div class="stat-icon-box yellow">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="stat-info-col">
                        <span class="stat-label-top">KRS belum disetujui</span>
                        <span class="stat-number-main">6</span>
                        <span class="stat-sub-text">perlu diperiksa</span>
                    </div>
                </div>

                <!-- 4 -->
                <div class="stat-card blue-border">
                    <div class="stat-icon-box blue">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <div class="stat-info-col">
                        <span class="stat-label-top">Rata-rata Ipk bimbingan</span>
                        <span class="stat-number-main">3,70</span>
                        <span class="stat-sub-text">dari 24 mahasiswa</span>
                    </div>
                </div>
            </div>

            <!-- ROW 2: TABLE & DONUT (SESUAI GAMBAR) -->
            <div class="mid-grid">
                <!-- DAFTAR MAHASISWA BIMBINGAN -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                            <span>daftar mahasiswa bimbingan</span>
                        </div>
                        <a href="#" class="link-see-all">Lihat semua &rsaquo;</a>
                    </div>
                    <div class="card-body" style="padding: 10px 16px;">
                        <table class="bimbingan-table">
                            <thead>
                                <tr>
                                    <th style="width: 35px;">No</th>
                                    <th>Nama mahasiswa</th>
                                    <th>NPM</th>
                                    <th>PROGRAM STUDI</th>
                                    <th>STATUS KRS</th>
                                    <th style="text-align: center;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><span class="user-avatar-dot">Y</span> MUHAMMAD YUSUF</td>
                                    <td>11523009</td>
                                    <td>Sistem Informasi</td>
                                    <td><span class="badge-status-krs disetujui">disetujui</span></td>
                                    <td style="text-align: center;"><button class="btn-detail-pill">detail</button></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><span class="user-avatar-dot">A</span> Azra Deliana P</td>
                                    <td>11523005</td>
                                    <td>Sistem Informasi</td>
                                    <td><span class="badge-status-krs disetujui">disetujui</span></td>
                                    <td style="text-align: center;"><button class="btn-detail-pill">detail</button></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td><span class="user-avatar-dot">M</span> Marlinaawati</td>
                                    <td>11523008</td>
                                    <td>Sistem Informasi</td>
                                    <td><span class="badge-status-krs disetujui">disetujui</span></td>
                                    <td style="text-align: center;"><button class="btn-detail-pill">detail</button></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td><span class="user-avatar-dot">B</span> Budi</td>
                                    <td>11523006</td>
                                    <td>Sistem Informasi</td>
                                    <td><span class="badge-status-krs belum-disetujui">belum disetujui</span></td>
                                    <td style="text-align: center;"><button class="btn-detail-pill">detail</button></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td><span class="user-avatar-dot">A</span> Andi</td>
                                    <td>11523006</td>
                                    <td>Sistem Informasi</td>
                                    <td><span class="badge-status-krs belum-disetujui">belum disetujui</span></td>
                                    <td style="text-align: center;"><button class="btn-detail-pill">detail</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- STATUS KRS MAHASISWA BIMBINGAN (DONUT) -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            </svg>
                            <span>STATUS KRS MAHASISWA BIMBINGAN</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="donut-wrap">
                            <div class="donut-canvas-box">
                                <canvas id="krsDonutChart"></canvas>
                                <div class="donut-center-lbl">
                                    <span class="donut-center-val">24</span>
                                    <span class="donut-center-sub">Mahasiswa</span>
                                </div>
                            </div>
                            <div class="donut-legend-col">
                                <div class="donut-legend-item">
                                    <span class="donut-legend-dot" style="background-color: #14b8a6;"></span>
                                    <span>Disetujui</span>
                                    <span style="margin-left: auto; color: #64748b;">18 &nbsp;(75%)</span>
                                </div>
                                <div class="donut-legend-item">
                                    <span class="donut-legend-dot" style="background-color: #f59e0b;"></span>
                                    <span>Belum Disetujui</span>
                                    <span style="margin-left: auto; color: #64748b;">6 &nbsp;(25%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROW 3: 3 CARDS (SESUAI GAMBAR) -->
            <div class="bottom-three-grid">
                <!-- 1. PERKEMBANGAN IPK -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                            <span style="font-size: 13.5px;">Perkembangan IPK Mahasiswa Bimbingan</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="height: 140px; position: relative;">
                            <canvas id="ipkLineChart"></canvas>
                        </div>
                        <div class="chart-stat-floating">
                            <div>
                                <div style="font-size: 11px; color: #64748b;">IPK Rata-rata</div>
                                <div class="csf-val">3.42</div>
                            </div>
                            <div style="color: #10b981; font-weight: 700;">
                                ↑ 0.12 <span style="font-size: 10px; font-weight: 500; color: #64748b;">dari semester lalu</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. JADWAL BIMBINGAN AKADEMIK -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span style="font-size: 13.5px;">Jadwal Bimbingan Akademik</span>
                        </div>
                        <a href="#" class="link-see-all">Lihat Semua &rsaquo;</a>
                    </div>
                    <div class="card-body">
                        <div class="schedule-item">
                            <div class="schedule-date-box">
                                <span class="sdb-d">17</span>
                                <span class="sdb-m">Jun</span>
                            </div>
                            <div class="schedule-info">
                                <span class="schedule-title">Bimbingan KRS</span>
                                <span class="schedule-time-room">10:00 - 12:00 | Ruang Dosen 2</span>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-date-box">
                                <span class="sdb-d">19</span>
                                <span class="sdb-m">Jun</span>
                            </div>
                            <div class="schedule-info">
                                <span class="schedule-title">Bimbingan Akademik</span>
                                <span class="schedule-time-room">13:00 - 15:00 | Ruang Dosen 1</span>
                            </div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-date-box">
                                <span class="sdb-d">24</span>
                                <span class="sdb-m">Jun</span>
                            </div>
                            <div class="schedule-info">
                                <span class="schedule-title">Evaluasi Perkembangan Mahasiswa</span>
                                <span class="schedule-time-room">09:00 - 11:00 | Ruang Dosen 2</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. PENGUMUMAN -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-title-group">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            <span style="font-size: 13.5px;">Pengumuman</span>
                        </div>
                        <a href="#" class="link-see-all">Lihat Semua &rsaquo;</a>
                    </div>
                    <div class="card-body">
                        <div class="announcement-list">
                            <div class="announcement-item">
                                <span class="ann-dot"></span>
                                <div class="ann-text-group">
                                    <span class="ann-title">Jadwal Pengisian KRS Semester Genap 2024/2025</span>
                                    <span class="ann-date">15 Jan 2025</span>
                                </div>
                            </div>
                            <div class="announcement-item">
                                <span class="ann-dot orange"></span>
                                <div class="ann-text-group">
                                    <span class="ann-title">Batas Akhir Perubahan KRS</span>
                                    <span class="ann-date">30 Jan 2025</span>
                                </div>
                            </div>
                            <div class="announcement-item">
                                <span class="ann-dot yellow"></span>
                                <div class="ann-text-group">
                                    <span class="ann-title">Informasi Beasiswa Prestasi</span>
                                    <span class="ann-date">2 Feb 2025</span>
                                </div>
                            </div>
                            <div class="announcement-item">
                                <span class="ann-dot teal"></span>
                                <div class="ann-text-group">
                                    <span class="ann-title">Perubahan Jadwal Kuliah</span>
                                    <span class="ann-date">24 Mar 2025</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Donut Chart KRS
            const krsCtx = document.getElementById('krsDonutChart').getContext('2d');
            new Chart(krsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Disetujui', 'Belum Disetujui'],
                    datasets: [{
                        data: [18, 6],
                        backgroundColor: ['#14b8a6', '#f59e0b'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // 2. Line Chart IPK
            const ipkCtx = document.getElementById('ipkLineChart').getContext('2d');
            const blueGradient = ipkCtx.createLinearGradient(0, 0, 0, 140);
            blueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
            blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ipkCtx, {
                type: 'line',
                data: {
                    labels: ['Smt 1', 'Smt 2', 'Smt 3', 'Smt 4', 'Smt 5', 'Smt 6'],
                    datasets: [{
                        data: [3.20, 3.35, 3.28, 3.45, 3.38, 3.50],
                        borderColor: '#3b82f6',
                        backgroundColor: blueGradient,
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#3b82f6',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#94a3b8' } },
                        y: { min: 3.0, max: 4.0, ticks: { font: { size: 10 }, color: '#94a3b8', stepSize: 0.5 }, grid: { color: '#f1f5f9' } }
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
