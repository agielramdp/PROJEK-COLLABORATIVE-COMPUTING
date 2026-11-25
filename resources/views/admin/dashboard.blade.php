<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - SI-AKAD | Institut Informatika & Komputer</title>

    <!-- Google Fonts: Plus Jakarta Sans for crisp, modern typography -->
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
            --color-sidebar-bg: #0e2954;
            --color-sidebar-hover: rgba(255, 255, 255, 0.08);
            --color-sidebar-active-border: rgba(255, 255, 255, 0.55);
            --color-sidebar-text: #e2e8f0;
            --color-primary-blue: #0d6efd;
            --color-primary-hover: #0b5ed7;
            --color-avatar-blue: #60a5fa;
            --color-bg-page: #f8fafc;
            --color-border: #e2e8f0;
            --color-text-dark: #1e293b;
            --color-text-muted: #64748b;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
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

        /* ========================================================
           LAYOUT: SIDEBAR (KIRI)
           ======================================================== */
        .sidebar {
            width: 270px;
            background-color: var(--color-sidebar-bg);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            min-height: 100vh;
            position: sticky;
            top: 0;
            padding: 24px 16px 20px 16px;
            z-index: 50;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.08);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 10px 24px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            text-decoration: none;
            color: #ffffff;
        }

        .brand-logo-wrap {
            width: 44px;
            height: 44px;
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
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-sub1 {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.3;
        }

        .brand-sub2 {
            font-size: 9.5px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.2;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-grow: 1;
            list-style: none;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 16px;
            border-radius: 8px;
            color: var(--color-sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1.2px solid transparent;
        }

        .nav-item:hover {
            background-color: var(--color-sidebar-hover);
            color: #ffffff;
        }

        /* ACTIVE MENU ON DASHBOARD (SESUAI GAMBAR) */
        .nav-item.active {
            border: 1.2px solid var(--color-sidebar-active-border);
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-weight: 600;
        }

        .nav-icon {
            width: 19px;
            height: 19px;
            stroke-width: 2;
            flex-shrink: 0;
            opacity: 0.9;
        }

        .nav-item.active .nav-icon {
            opacity: 1;
        }

        .sidebar-footer {
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 10px 16px;
            background: transparent;
            border: none;
            color: #fca5a5;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #ffffff;
        }

        /* ========================================================
           LAYOUT: MAIN CONTENT (KANAN)
           ======================================================== */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--color-bg-page);
            overflow-y: auto;
        }

        /* TOP NAVBAR */
        .top-navbar {
            height: 68px;
            padding: 0 40px;
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
            width: 340px;
            max-width: 100%;
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
            transition: all 0.2s ease;
        }

        .header-search input:focus {
            background-color: #ffffff;
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
        }

        .header-search .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 15px;
            color: #94a3b8;
            pointer-events: none;
        }

        .notif-btn {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s ease;
        }

        .notif-btn:hover {
            background-color: #f1f5f9;
        }

        .notif-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background-color: #ef4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #ffffff;
        }

        .header-user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 9999px;
            transition: background 0.2s ease;
        }

        .header-user-profile:hover {
            background-color: #f1f5f9;
        }

        .header-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: var(--color-sidebar-bg);
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

        /* PAGE BODY CONTAINER */
        .page-content {
            padding: 32px 40px 60px 40px;
            max-width: 1400px;
            width: 100%;
        }

        /* PAGE HEADER (SESUAI GAMBAR) */
        .page-header-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }

        .admin-avatar-lg {
            width: 52px;
            height: 52px;
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
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 2px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.4px;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .page-desc {
            font-size: 13.5px;
            font-weight: 500;
            color: #64748b;
        }

        /* ========================================================
           ROW 1: 4 STAT CARDS (SESUAI GAMBAR)
           ======================================================== */
        .stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px 22px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            border-color: #cbd5e1;
        }

        .stat-card-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon-circle.indigo {
            background-color: #5850ec;
            color: #ffffff;
        }

        .stat-icon-circle.pink {
            background-color: #f472b6;
            color: #ffffff;
        }

        .stat-icon-circle.slate {
            background-color: #334155;
            color: #ffffff;
        }

        .stat-icon-circle.dark {
            background-color: #0f172a;
            color: #ffffff;
        }

        .stat-info-col {
            display: flex;
            flex-direction: column;
        }

        .stat-header-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 2px;
        }

        .stat-main-number {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
            margin-bottom: 4px;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .stat-trend.up {
            color: #10b981;
        }

        .stat-trend.neutral {
            color: #64748b;
        }

        .stat-trend.down {
            color: #10b981; /* Sesuai mock bertuliskan hijau */
        }

        .stat-card-arrow {
            color: #94a3b8;
            transition: transform 0.2s ease;
        }

        .stat-card:hover .stat-card-arrow {
            transform: translateX(3px);
            color: var(--color-primary-blue);
        }

        /* ========================================================
           ROW 2: CHARTS SECTION (GRID 58% - 42%)
           ======================================================== */
        .charts-grid {
            display: grid;
            grid-template-columns: 58% calc(42% - 20px);
            gap: 20px;
            margin-bottom: 28px;
        }

        .dashboard-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card-header-row {
            padding: 18px 24px 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header-icon {
            color: #0f172a;
        }

        .card-header-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .card-dropdown-select {
            appearance: none;
            -webkit-appearance: none;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            padding: 6px 28px 6px 14px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            outline: none;
            position: relative;
        }

        .card-dropdown-wrap {
            position: relative;
        }

        .card-dropdown-wrap svg {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            color: #64748b;
            pointer-events: none;
        }

        .card-body {
            padding: 20px 24px;
            flex-grow: 1;
        }

        /* DONUT CHART LAYOUT */
        .donut-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            height: 100%;
        }

        .donut-chart-box {
            position: relative;
            width: 170px;
            height: 170px;
            flex-shrink: 0;
        }

        .donut-center-info {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            text-align: center;
        }

        .donut-center-sub {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }

        .donut-center-val {
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
        }

        .donut-legend-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            flex-grow: 1;
        }

        .legend-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 10px;
        }

        .legend-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .legend-name-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .legend-dot-circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .legend-name {
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
        }

        .legend-percent {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
        }

        /* ========================================================
           ROW 3: BOTTOM ACTIVITY & CALENDAR (GRID 58% - 42%)
           ======================================================== */
        .bottom-grid {
            display: grid;
            grid-template-columns: 58% calc(42% - 20px);
            gap: 20px;
        }

        .view-all-link {
            font-size: 12.5px;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color 0.2s ease;
        }

        .view-all-link:hover {
            color: var(--color-primary-blue);
        }

        /* AKTIVITAS LIST */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .activity-icon-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
        }

        .activity-icon-badge.green { background-color: #22c55e; }
        .activity-icon-badge.blue { background-color: #3b82f6; }
        .activity-icon-badge.purple { background-color: #8b5cf6; }
        .activity-icon-badge.orange { background-color: #f97316; }

        .activity-text-col {
            display: flex;
            flex-direction: column;
        }

        .activity-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .activity-subtitle {
            font-size: 12px;
            color: #64748b;
            line-height: 1.35;
        }

        .activity-time {
            font-size: 11.5px;
            color: #94a3b8;
            font-weight: 500;
            text-align: right;
            white-space: nowrap;
        }

        /* KALENDER AKADEMIK LIST */
        .calendar-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .calendar-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .calendar-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .calendar-item-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .date-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: #f1f5f9;
            border-radius: 8px;
            flex-shrink: 0;
            color: #0f172a;
        }

        .date-box .db-day {
            font-size: 15px;
            font-weight: 800;
            line-height: 1;
        }

        .date-box .db-month {
            font-size: 9.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .calendar-details {
            display: flex;
            flex-direction: column;
        }

        .calendar-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .calendar-sub {
            font-size: 11.5px;
            color: #64748b;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            font-weight: 600;
            white-space: nowrap;
        }

        .cat-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .category-badge.akademik { color: #2563eb; }
        .category-badge.akademik .cat-dot { background-color: #2563eb; }

        .category-badge.akademik-warn { color: #d97706; }
        .category-badge.akademik-warn .cat-dot { background-color: #d97706; }

        .category-badge.ujian { color: #16a34a; }
        .category-badge.ujian .cat-dot { background-color: #16a34a; }

        .category-badge.administrasi { color: #7c3aed; }
        .category-badge.administrasi .cat-dot { background-color: #7c3aed; }

        /* RESPONSIVE DESIGN */
        @media (max-width: 1200px) {
            .stat-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .charts-grid, .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                min-height: auto;
                position: static;
                padding: 16px;
            }
            .nav-menu {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 8px;
            }
            .stat-cards-grid {
                grid-template-columns: 1fr;
            }
            .header-search {
                display: none;
            }
            .page-content {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- ========================================================
         SIDEBAR (SESUAI DOKUMEN & GAMBAR PROJEK)
         ======================================================== -->
    <aside class="sidebar">
        <!-- BRAND LOGO -->
        <a href="{{ route('admin.dashboard') }}" class="brand-header">
            <div class="brand-logo-wrap">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" width="40" height="40">
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

        <!-- NAVIGATION MENU ITEMS -->
        <ul class="nav-menu">
            <li>
                <!-- DASHBOARD ACTIVE SESUAI GAMBAR -->
                <a href="{{ route('admin.dashboard') }}" class="nav-item active">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>Manajemen Pengguna</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.master-data') }}" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    <span>Master Data Akademik</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.schedules.index') }}" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>Jadwal Kuliah & Ujian</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    <span>Penugasan Dosen</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    <span>KRS & KHS</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span>Pengumuman</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span>Audit Log</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <button type="button" class="logout-btn" onclick="handleLogout()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Keluar Sistem</span>
            </button>
        </div>
    </aside>

    <!-- ========================================================
         MAIN CONTENT AREA (KANAN)
         ======================================================== -->
    <main class="main-wrapper">
        <!-- TOP NAVBAR -->
        <header class="top-navbar">
            <div class="header-search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" placeholder="Cari mahasiswa, dosen atau program studi...">
            </div>

            <button type="button" class="notif-btn" title="Notifikasi">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="notif-badge">1</span>
            </button>

            <div class="header-user-profile" title="Profil Admin">
                <div class="header-avatar">AA</div>
                <span class="header-user-name">Admin Akademik</span>
            </div>
        </header>

        <!-- PAGE CONTENT CONTAINER -->
        <div class="page-content">
            <!-- PAGE HEADER (SESUAI GAMBAR) -->
            <div class="page-header-row">
                <div class="admin-avatar-lg">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="page-header-text">
                    <div class="welcome-subtitle">Selamat Datang, Admin</div>
                    <h1 class="page-title">Dashboard Admin</h1>
                    <div class="page-desc">Ringkasan aktivitas akademik</div>
                </div>
            </div>

            <!-- ROW 1: 4 STAT CARDS (SESUAI GAMBAR) -->
            <div class="stat-cards-grid">
                <!-- 1. Total Mahasiswa -->
                <a href="{{ route('admin.users.index') }}" class="stat-card">
                    <div class="stat-card-left">
                        <div class="stat-icon-circle indigo">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                        <div class="stat-info-col">
                            <span class="stat-header-label">Total Mahasiswa</span>
                            <span class="stat-main-number" id="statTotalMahasiswa">0</span>
                            <span class="stat-trend up">
                                <span>Data Aktif</span> dari database
                            </span>
                        </div>
                    </div>
                    <div class="stat-card-arrow">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>

                <!-- 2. Total Dosen -->
                <a href="{{ route('admin.users.index') }}" class="stat-card">
                    <div class="stat-card-left">
                        <div class="stat-icon-circle pink">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="stat-info-col">
                            <span class="stat-header-label">Total Dosen</span>
                            <span class="stat-main-number" id="statTotalDosen">0</span>
                            <span class="stat-trend up">
                                <span>Data Aktif</span> dari database
                            </span>
                        </div>
                    </div>
                    <div class="stat-card-arrow">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>

                <!-- 3. Program Studi -->
                <a href="{{ route('admin.master-data') }}" class="stat-card">
                    <div class="stat-card-left">
                        <div class="stat-icon-circle slate">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                        </div>
                        <div class="stat-info-col">
                            <span class="stat-header-label">Program Studi</span>
                            <span class="stat-main-number" id="statProgramStudi">0</span>
                            <span class="stat-trend up">
                                <span id="statFakultasSub">0 Fakultas</span> terdaftar
                            </span>
                        </div>
                    </div>
                    <div class="stat-card-arrow">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>

                <!-- 4. Mata Kuliah -->
                <a href="{{ route('admin.master-data') }}" class="stat-card">
                    <div class="stat-card-left">
                        <div class="stat-icon-circle dark">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                        <div class="stat-info-col">
                            <span class="stat-header-label">Mata Kuliah</span>
                            <span class="stat-main-number" id="statMataKuliah">0</span>
                            <span class="stat-trend neutral">
                                <span id="statJadwalSub">0 Jadwal</span> aktif
                            </span>
                        </div>
                    </div>
                    <div class="stat-card-arrow">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- ROW 2: CHARTS SECTION (SESUAI GAMBAR) -->
            <div class="charts-grid">
                <!-- KIRI: LINE CHART STATISTIK MAHASISWA -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-header-left">
                            <svg class="card-header-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                                <polyline points="17 6 23 6 23 12"/>
                            </svg>
                            <h2 class="card-header-title">Statistik Mahasiswa</h2>
                        </div>
                        <div class="card-dropdown-wrap">
                            <select class="card-dropdown-select">
                                <option>6 Bulan Terakhir</option>
                                <option>1 Tahun Terakhir</option>
                                <option>Semester Ini</option>
                            </select>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="lineChartCanvas"></canvas>
                        </div>
                    </div>
                </div>

                <!-- KANAN: DONUT CHART STATISTIK MAHASISWA -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-header-left">
                            <svg class="card-header-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"/>
                                <path d="M22 12A10 10 0 0 0 12 2v10z"/>
                            </svg>
                            <h2 class="card-header-title">Distribusi Program Studi</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="donut-container">
                            <div class="donut-chart-box">
                                <canvas id="donutChartCanvas"></canvas>
                                <div class="donut-center-info">
                                    <span class="donut-center-sub">Total Prodi</span>
                                    <span class="donut-center-val" id="donutCenterVal">0</span>
                                </div>
                            </div>
                            <div class="donut-legend-list" id="donutLegendList">
                                <div class="legend-row">
                                    <span class="legend-name" style="color:#64748b;">Memuat data fakultas...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROW 3: BOTTOM SECTION (SESUAI GAMBAR) -->
            <div class="bottom-grid">
                <!-- KIRI: AKTIVITAS TERBARU -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-header-left">
                            <svg class="card-header-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <h2 class="card-header-title">Aktivitas Terbaru</h2>
                        </div>
                        <a href="#" class="view-all-link">
                            <span>Lihat Semua</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="activity-list" id="activityList">
                            <div style="font-size:13px; color:#64748b; padding:12px 0;">Memuat aktivitas terbaru...</div>
                        </div>
                    </div>
                </div>

                <!-- KANAN: KALENDER AKADEMIK -->
                <div class="dashboard-card">
                    <div class="card-header-row">
                        <div class="card-header-left">
                            <svg class="card-header-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <h2 class="card-header-title">Kalender Akademik</h2>
                        </div>
                        <a href="#" class="view-all-link">
                            <span>Lihat Semua</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="calendar-list" id="calendarList">
                            <div style="font-size:13px; color:#64748b; padding:12px 0;">Memuat kalender akademik...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ========================================================
         JAVASCRIPT: CHARTS INITIALIZATION
         ======================================================== -->
    <script>
        function escapeHtml(str) {
            return String(str ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
        }

        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const res = await fetch('/api/admin/dashboard/stats', { headers: { 'Accept': 'application/json' } });
                const json = await res.json();
                if (!res.ok || !json.success) throw new Error(json.message || 'Gagal memuat data');
                const d = json.data;

                // 1. Stat Cards (100% database)
                document.getElementById('statTotalMahasiswa').textContent = (d.total_mahasiswa ?? 0).toLocaleString('id-ID');
                document.getElementById('statTotalDosen').textContent = (d.total_dosen ?? 0).toLocaleString('id-ID');
                document.getElementById('statProgramStudi').textContent = (d.program_studi ?? 0).toLocaleString('id-ID');
                document.getElementById('statFakultasSub').textContent = `${d.total_fakultas ?? 0} Fakultas`;
                document.getElementById('statMataKuliah').textContent = (d.mata_kuliah ?? 0).toLocaleString('id-ID');
                document.getElementById('statJadwalSub').textContent = `${d.total_jadwal ?? 0} Jadwal`;

                // 2. Line Chart "Statistik Mahasiswa"
                const lineCtx = document.getElementById('lineChartCanvas').getContext('2d');
                const blueGradient = lineCtx.createLinearGradient(0, 0, 0, 260);
                blueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
                blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: d.monthly_students?.labels || [],
                        datasets: [{
                            label: 'Jumlah Mahasiswa',
                            data: d.monthly_students?.counts || [],
                            borderColor: '#2563eb',
                            borderWidth: 2.5,
                            backgroundColor: blueGradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b' } },
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b' },
                                grid: { color: '#f1f5f9' }
                            }
                        }
                    }
                });

                // 3. Donut Chart & Legend (Distribusi Prodi per Fakultas)
                document.getElementById('donutCenterVal').textContent = (d.program_studi ?? 0).toLocaleString('id-ID');
                const dist = d.faculty_distribution || [];
                const palette = ['#0e2954', '#3b82f6', '#fde68a', '#10b981', '#8b5cf6', '#f97316'];
                const donutCtx = document.getElementById('donutChartCanvas').getContext('2d');
                new Chart(donutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: dist.length ? dist.map(x => x.label) : ['Belum ada data'],
                        datasets: [{
                            data: dist.length ? dist.map(x => x.count) : [1],
                            backgroundColor: dist.length ? dist.map((_, i) => palette[i % palette.length]) : ['#e2e8f0'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => dist.length ? ` ${ctx.label}: ${ctx.raw} Prodi` : ' Belum ada data'
                                }
                            }
                        }
                    }
                });

                const legendEl = document.getElementById('donutLegendList');
                if (!dist.length) {
                    legendEl.innerHTML = `<div class="legend-row"><span class="legend-name" style="color:#64748b;">Belum ada data fakultas.</span></div>`;
                } else {
                    legendEl.innerHTML = dist.map((item, i) => `
                        <div class="legend-row">
                            <div class="legend-name-group">
                                <div class="legend-dot-circle" style="background-color: ${palette[i % palette.length]};"></div>
                                <span class="legend-name">${escapeHtml(item.label)}</span>
                            </div>
                            <span class="legend-percent">${item.percentage}%</span>
                        </div>
                    `).join('');
                }

                // 4. Aktivitas Terbaru (Audit Logs)
                const actEl = document.getElementById('activityList');
                const acts = d.recent_activities || [];
                if (!acts.length) {
                    actEl.innerHTML = `<div style="font-size:13px; color:#64748b; padding:16px 0; text-align:center;">Belum ada aktivitas tercatat di database.</div>`;
                } else {
                    const badges = ['green', 'blue', 'purple', 'orange'];
                    actEl.innerHTML = acts.map((a, i) => `
                        <div class="activity-item">
                            <div class="activity-left">
                                <div class="activity-icon-badge ${badges[i % badges.length]}">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"/>
                                        <polyline points="12 7 12 12 15 14"/>
                                    </svg>
                                </div>
                                <div class="activity-text-col">
                                    <span class="activity-title">${escapeHtml(a.action)}</span>
                                    <span class="activity-subtitle">${escapeHtml(a.module)} • oleh ${escapeHtml(a.user_name)}</span>
                                </div>
                            </div>
                            <div class="activity-time">
                                <div>${escapeHtml(a.created_at)}</div>
                                <div>${escapeHtml(a.time)}</div>
                            </div>
                        </div>
                    `).join('');
                }

                // 5. Kalender Akademik
                const calEl = document.getElementById('calendarList');
                const cals = d.calendar_events || [];
                if (!cals.length) {
                    calEl.innerHTML = `<div style="font-size:13px; color:#64748b; padding:16px 0; text-align:center;">Belum ada agenda kalender akademik di database.</div>`;
                } else {
                    calEl.innerHTML = cals.map(ev => `
                        <div class="calendar-item">
                            <div class="calendar-item-left">
                                <div class="date-box">
                                    <span class="db-day">${escapeHtml(ev.day)}</span>
                                    <span class="db-month">${escapeHtml(ev.month)}</span>
                                </div>
                                <div class="calendar-details">
                                    <span class="calendar-title">${escapeHtml(ev.title)}</span>
                                    <span class="calendar-sub">${escapeHtml(ev.range)}</span>
                                </div>
                            </div>
                            <span class="category-badge akademik">
                                <span class="cat-dot"></span>
                                ${escapeHtml(ev.event_type)}
                            </span>
                        </div>
                    `).join('');
                }
            } catch (err) {
                console.error(err);
            }
        });

        function handleLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari sistem SI-AKAD?')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                }).finally(() => {
                    window.location.href = '/login';
                });
            }
        }
    </script>
</body>
</html>
