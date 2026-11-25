<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Data Akademik - SI-AKAD | Institut Informatika & Komputer</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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
           SIDEBAR
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

        .brand-text-col { display: flex; flex-direction: column; }
        .brand-title { font-size: 19px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; line-height: 1.2; }
        .brand-sub1 { font-size: 11px; font-weight: 600; color: rgba(255, 255, 255, 0.85); line-height: 1.3; }
        .brand-sub2 { font-size: 9.5px; font-weight: 400; color: rgba(255, 255, 255, 0.65); line-height: 1.2; }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
            list-style: none;
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 8px;
            color: var(--color-sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .nav-item:hover { background-color: var(--color-sidebar-hover); color: #ffffff; }
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: var(--color-sidebar-active-border);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            stroke-width: 1.8;
            opacity: 0.9;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout-sidebar {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fecaca;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-logout-sidebar:hover {
            background: rgba(239, 68, 68, 0.25);
            color: #ffffff;
        }

        /* ========================================================
           MAIN WORKSPACE
           ======================================================== */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background-color: #f8fafc;
        }

        .top-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 32px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--color-border);
            gap: 16px;
            flex-wrap: wrap;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-avatar-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #93c5fd 0%, #60a5fa 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e3a8a;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(96, 165, 250, 0.3);
        }

        .header-title-group { display: flex; flex-direction: column; }
        .header-welcome { font-size: 12px; font-weight: 500; color: var(--color-text-muted); margin-bottom: 2px; }
        .header-title { font-size: 23px; font-weight: 800; color: #0f172a; letter-spacing: -0.4px; line-height: 1.2; }
        .header-subtitle { font-size: 12.5px; color: var(--color-text-muted); margin-top: 3px; }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-search {
            position: relative;
            width: 300px;
        }

        .header-search svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #94a3b8;
            pointer-events: none;
        }

        .header-search input {
            width: 100%;
            padding: 9px 14px 9px 38px;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            background-color: #f8fafc;
            font-family: inherit;
            font-size: 12.5px;
            color: var(--color-text-dark);
            outline: none;
            transition: all 0.2s;
        }

        .header-search input:focus {
            background-color: #ffffff;
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
        }

        .notif-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notif-btn:hover { background: #f1f5f9; color: #0f172a; }
        .notif-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #ef4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
        }

        .profile-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 14px 5px 6px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .profile-initials {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #0e2954;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-name { font-size: 12.5px; font-weight: 600; color: #1e293b; }

        /* ========================================================
           PAGE CONTENT
           ======================================================== */
        .page-content {
            padding: 24px 32px 40px 32px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ACTION TOOLBAR */
        .action-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-primary-navy {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: #0e2954;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 3px 10px rgba(14, 41, 84, 0.18);
        }

        .btn-primary-navy:hover {
            background-color: #163b75;
            transform: translateY(-1px);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            background-color: #ffffff;
            color: #1e293b;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background-color: #f1f5f9;
            border-color: #94a3b8;
        }

        .dropdown-wrap {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            min-width: 210px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
            padding: 6px;
            z-index: 40;
            display: none;
        }

        .dropdown-menu.show { display: block; }

        .dropdown-item {
            width: 100%;
            text-align: left;
            padding: 9px 12px;
            border: none;
            background: transparent;
            border-radius: 7px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.15s;
        }

        .dropdown-item:hover { background: #f1f5f9; color: #0f172a; }
        .dropdown-item.active { background: #eff6ff; color: #0e2954; font-weight: 700; }

        /* STAT CARDS (3 COLUMNS) */
        .stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px 24px;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.6px;
            line-height: 1.1;
        }

        /* TABLE CARD */
        .table-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table.data-table thead th {
            padding: 15px 18px;
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        table.data-table tbody td {
            padding: 15px 18px;
            font-size: 13px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        table.data-table tbody tr:last-child td {
            border-bottom: none;
        }

        table.data-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pill.aktif {
            background-color: #dcfce7;
            color: #15803d;
        }

        .status-pill.nonaktif {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .action-icons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid transparent;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            color: #475569;
        }

        .icon-btn.edit:hover { background: #eff6ff; color: #0d6efd; border-color: #bfdbfe; }
        .icon-btn.delete:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }

        /* PAGINATION FOOTER */
        .pagination-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-info {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .page-btn {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #334155;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .page-btn:hover:not(:disabled) { background: #f1f5f9; border-color: #cbd5e1; }
        .page-btn.active { background: #0e2954; color: #ffffff; border-color: #0e2954; }
        .page-btn:disabled { opacity: 0.45; cursor: not-allowed; }

        /* MODAL */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
        }

        .modal-backdrop.show { display: flex; }

        .modal-box {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2);
            overflow: hidden;
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title { font-size: 17px; font-weight: 700; color: #0f172a; }
        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: #f1f5f9;
            color: #475569;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-close:hover { background: #e2e8f0; color: #0f172a; }

        .modal-body {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .form-label { font-size: 12.5px; font-weight: 600; color: #334155; }
        .form-control {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            color: #0f172a;
            outline: none;
            transition: border-color 0.2s;
            background: #ffffff;
        }
        .form-control:focus { border-color: #0e2954; box-shadow: 0 0 0 3px rgba(14, 41, 84, 0.1); }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            background: #f8fafc;
        }

        /* TOAST */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 200;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.25s ease-out;
        }

        .toast.success { background: #059669; }
        .toast.error { background: #dc2626; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .empty-state {
            padding: 48px 20px;
            text-align: center;
            color: #64748b;
            font-size: 13.5px;
        }

        @media (max-width: 1024px) {
            .stat-cards-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; min-height: auto; position: static; padding: 16px; }
            .nav-menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 6px; }
            .header-search { width: 100%; }
            .page-content { padding: 20px 16px; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
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

        <ul class="nav-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="nav-item">
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
                <a href="{{ route('admin.master-data') }}" class="nav-item active">
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
                    </svg>
                    <span>KRS & KHS</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
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
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <button type="button" class="btn-logout-sidebar" onclick="handleLogout()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Keluar</span>
            </button>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-wrapper">
        <header class="top-header">
            <div class="header-left">
                <div class="header-avatar-circle">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="header-title-group">
                    <span class="header-welcome">Selamat Datang, Admin</span>
                    <h1 class="header-title">Master Data Akademik</h1>
                    <p class="header-subtitle">Kelola data kurikulum, program studi, dan fakultas</p>
                </div>
            </div>

            <div class="header-right">
                <div class="header-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari program studi, mata kuliah, atau fakultas...">
                </div>

                <button class="notif-btn" type="button" title="Notifikasi">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="notif-badge">1</span>
                </button>

                <div class="profile-pill">
                    <div class="profile-initials">AA</div>
                    <span class="profile-name">Admin Akademik</span>
                </div>
            </div>
        </header>

        <main class="page-content">
            <!-- ACTION TOOLBAR -->
            <div class="action-toolbar">
                <div class="toolbar-left">
                    <button type="button" class="btn-primary-navy" onclick="openCreateModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        <span id="btnAddLabel">Tambah Data</span>
                    </button>
                </div>

                <div class="toolbar-right">
                    <div class="dropdown-wrap">
                        <button type="button" class="btn-outline" onclick="toggleDropdown('kategoriMenu')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                            </svg>
                            <span id="kategoriLabel">Kategori: Program Studi</span>
                        </button>
                        <div class="dropdown-menu" id="kategoriMenu">
                            <button type="button" class="dropdown-item active" data-cat="prodi" onclick="switchCategory('prodi')">
                                <span>Program Studi</span>
                            </button>
                            <button type="button" class="dropdown-item" data-cat="fakultas" onclick="switchCategory('fakultas')">
                                <span>Fakultas</span>
                            </button>
                            <button type="button" class="dropdown-item" data-cat="matkul" onclick="switchCategory('matkul')">
                                <span>Mata Kuliah</span>
                            </button>
                            <button type="button" class="dropdown-item" data-cat="kurikulum" onclick="switchCategory('kurikulum')">
                                <span>Kurikulum</span>
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn-outline" onclick="exportCsv()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <span>Ekspor data</span>
                    </button>
                </div>
            </div>

            <!-- 3 STAT CARDS -->
            <div class="stat-cards-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Fakultas</div>
                    <div class="stat-value" id="statFaculties">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Program Studi</div>
                    <div class="stat-value" id="statPrograms">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Mata Kuliah</div>
                    <div class="stat-value" id="statCourses">0</div>
                </div>
            </div>

            <!-- DATA TABLE -->
            <div class="table-card">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead id="tableHead"></thead>
                        <tbody id="tableBody">
                            <tr><td colspan="7" class="empty-state">Memuat data akademik...</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-footer">
                    <div class="pagination-info" id="paginationInfo">Menampilkan 0-0 dari 0 data</div>
                    <div class="pagination-controls" id="paginationControls"></div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL CREATE / EDIT -->
    <div class="modal-backdrop" id="formModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Data</h3>
                <button type="button" class="modal-close" onclick="closeModal('formModal')">&times;</button>
            </div>
            <form id="masterForm" onsubmit="submitForm(event)">
                <div class="modal-body" id="modalFields"></div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal('formModal')">Batal</button>
                    <button type="submit" class="btn-primary-navy" id="btnSubmit">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE CONFIRMATION -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" style="max-width: 420px;">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <button type="button" class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size: 13.5px; color: #475569; line-height: 1.6;">
                    Apakah Anda yakin ingin menghapus <strong id="deleteItemName" style="color: #0f172a;">data ini</strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('deleteModal')">Batal</button>
                <button type="button" class="btn-primary-navy" style="background: #dc2626;" onclick="confirmDelete()">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const state = {
            category: 'prodi',
            search: '',
            page: 1,
            perPage: 5,
            editingId: null,
            deletingId: null,
            faculties: [],
            educationLevels: [],
            studyPrograms: [],
            courses: [],
            curriculums: [],
        };

        const categoryNames = {
            prodi: 'Program Studi',
            fakultas: 'Fakultas',
            matkul: 'Mata Kuliah',
            kurikulum: 'Kurikulum',
        };

        document.addEventListener('DOMContentLoaded', () => {
            loadAllData();
            document.getElementById('searchInput').addEventListener('input', (e) => {
                state.search = e.target.value.toLowerCase().trim();
                state.page = 1;
                renderTable();
            });
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.dropdown-wrap')) {
                    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
                }
            });
        });

        async function apiFetch(url, options = {}) {
            const res = await fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    ...(options.headers || {}),
                },
                ...options,
            });
            const data = await res.json();
            if (!res.ok || data.success === false) {
                throw new Error(data.message || 'Terjadi kesalahan pada server.');
            }
            return data;
        }

        async function loadAllData() {
            try {
                const [facRes, lvlRes, prodiRes, courseRes, currRes] = await Promise.all([
                    apiFetch('/api/admin/faculties'),
                    apiFetch('/api/admin/education-levels'),
                    apiFetch('/api/admin/study-programs'),
                    apiFetch('/api/admin/courses'),
                    apiFetch('/api/admin/curriculums'),
                ]);

                state.faculties = facRes.data || [];
                state.educationLevels = lvlRes.data || [];
                state.studyPrograms = prodiRes.data || [];
                state.courses = courseRes.data || [];
                state.curriculums = currRes.data || [];

                document.getElementById('statFaculties').textContent = state.faculties.length;
                document.getElementById('statPrograms').textContent = state.studyPrograms.length;
                document.getElementById('statCourses').textContent = state.courses.length;

                renderTable();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function switchCategory(cat) {
            state.category = cat;
            state.page = 1;
            document.getElementById('kategoriLabel').textContent = 'Kategori: ' + categoryNames[cat];
            document.querySelectorAll('#kategoriMenu .dropdown-item').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.cat === cat);
            });
            document.getElementById('kategoriMenu').classList.remove('show');
            renderTable();
        }

        function toggleDropdown(id) {
            document.getElementById(id).classList.toggle('show');
        }

        function getFilteredItems() {
            const q = state.search;
            if (state.category === 'prodi') {
                return state.studyPrograms.filter(p => !q ||
                    (p.code || '').toLowerCase().includes(q) ||
                    (p.name || '').toLowerCase().includes(q) ||
                    (p.faculty?.name || '').toLowerCase().includes(q) ||
                    (p.education_level?.code || '').toLowerCase().includes(q)
                );
            }
            if (state.category === 'fakultas') {
                return state.faculties.filter(f => !q ||
                    (f.code || '').toLowerCase().includes(q) ||
                    (f.name || '').toLowerCase().includes(q) ||
                    (f.dean_name || '').toLowerCase().includes(q)
                );
            }
            if (state.category === 'matkul') {
                return state.courses.filter(c => !q ||
                    (c.code || '').toLowerCase().includes(q) ||
                    (c.name || '').toLowerCase().includes(q) ||
                    (c.study_program?.name || '').toLowerCase().includes(q)
                );
            }
            return state.curriculums.filter(c => !q ||
                (c.code || '').toLowerCase().includes(q) ||
                (c.name || '').toLowerCase().includes(q) ||
                (c.study_program?.name || '').toLowerCase().includes(q)
            );
        }

        function renderTable() {
            const thead = document.getElementById('tableHead');
            const tbody = document.getElementById('tableBody');
            const items = getFilteredItems();

            const total = items.length;
            const totalPages = Math.max(1, Math.ceil(total / state.perPage));
            if (state.page > totalPages) state.page = totalPages;

            const startIdx = (state.page - 1) * state.perPage;
            const pageItems = items.slice(startIdx, startIdx + state.perPage);

            if (state.category === 'prodi') {
                thead.innerHTML = `<tr>
                    <th style="width:60px;">No</th>
                    <th>Kode</th>
                    <th>Nama Program Studi</th>
                    <th>Jenjang</th>
                    <th>Fakultas</th>
                    <th>Status</th>
                    <th style="width:100px;">Aksi</th>
                </tr>`;
            } else if (state.category === 'fakultas') {
                thead.innerHTML = `<tr>
                    <th style="width:60px;">No</th>
                    <th>Kode</th>
                    <th>Nama Fakultas</th>
                    <th>Dekan</th>
                    <th>Jumlah Prodi</th>
                    <th>Status</th>
                    <th style="width:100px;">Aksi</th>
                </tr>`;
            } else if (state.category === 'matkul') {
                thead.innerHTML = `<tr>
                    <th style="width:60px;">No</th>
                    <th>Kode</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th>Program Studi</th>
                    <th>Status</th>
                    <th style="width:100px;">Aksi</th>
                </tr>`;
            } else {
                thead.innerHTML = `<tr>
                    <th style="width:60px;">No</th>
                    <th>Kode</th>
                    <th>Nama Kurikulum</th>
                    <th>Tahun</th>
                    <th>Program Studi</th>
                    <th>Status</th>
                    <th style="width:100px;">Aksi</th>
                </tr>`;
            }

            if (pageItems.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="empty-state">Belum ada data ${categoryNames[state.category]}. Klik <strong>+ Tambah Data</strong> untuk menambahkan.</td></tr>`;
            } else {
                tbody.innerHTML = pageItems.map((item, idx) => {
                    const no = startIdx + idx + 1;
                    const active = item.is_active !== false && item.is_active !== 0;
                    const statusBadge = `<span class="status-pill ${active ? 'aktif' : 'nonaktif'}">${active ? 'Aktif' : 'Nonaktif'}</span>`;
                    const actions = `<div class="action-icons">
                        <button type="button" class="icon-btn edit" title="Edit" onclick="openEditModal(${item.id})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button type="button" class="icon-btn delete" title="Hapus" onclick="openDeleteModal(${item.id}, '${escapeHtml(item.name || item.code)}')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>`;

                    if (state.category === 'prodi') {
                        return `<tr>
                            <td>${no}</td>
                            <td><strong>${escapeHtml(item.code)}</strong></td>
                            <td>${escapeHtml(item.name)}</td>
                            <td>${escapeHtml(item.education_level?.code || item.education_level?.name || '-')}</td>
                            <td>${escapeHtml(item.faculty?.name || '-')}</td>
                            <td>${statusBadge}</td>
                            <td>${actions}</td>
                        </tr>`;
                    }
                    if (state.category === 'fakultas') {
                        return `<tr>
                            <td>${no}</td>
                            <td><strong>${escapeHtml(item.code)}</strong></td>
                            <td>${escapeHtml(item.name)}</td>
                            <td>${escapeHtml(item.dean_name || '-')}</td>
                            <td>${item.study_programs_count ?? 0} Prodi</td>
                            <td>${statusBadge}</td>
                            <td>${actions}</td>
                        </tr>`;
                    }
                    if (state.category === 'matkul') {
                        return `<tr>
                            <td>${no}</td>
                            <td><strong>${escapeHtml(item.code)}</strong></td>
                            <td>${escapeHtml(item.name)}</td>
                            <td>${item.total_credits ?? ((item.credits_theory || 0) + (item.credits_practical || 0))} SKS</td>
                            <td>Semester ${item.semester ?? '-'}</td>
                            <td>${escapeHtml(item.study_program?.name || '-')}</td>
                            <td>${statusBadge}</td>
                            <td>${actions}</td>
                        </tr>`;
                    }
                    return `<tr>
                        <td>${no}</td>
                        <td><strong>${escapeHtml(item.code)}</strong></td>
                        <td>${escapeHtml(item.name)}</td>
                        <td>${item.year_start ?? '-'}</td>
                        <td>${escapeHtml(item.study_program?.name || '-')}</td>
                        <td>${statusBadge}</td>
                        <td>${actions}</td>
                    </tr>`;
                }).join('');
            }

            const from = total === 0 ? 0 : startIdx + 1;
            const to = Math.min(startIdx + state.perPage, total);
            document.getElementById('paginationInfo').textContent = `Menampilkan ${from}-${to} dari ${total} data`;

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const container = document.getElementById('paginationControls');
            let html = `<button type="button" class="page-btn" ${state.page <= 1 ? 'disabled' : ''} onclick="goToPage(${state.page - 1})">Sebelumnya</button>`;
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || Math.abs(i - state.page) <= 1) {
                    html += `<button type="button" class="page-btn ${i === state.page ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
                } else if (Math.abs(i - state.page) === 2) {
                    html += `<span style="padding:0 4px;color:#64748b;">...</span>`;
                }
            }
            html += `<button type="button" class="page-btn" ${state.page >= totalPages ? 'disabled' : ''} onclick="goToPage(${state.page + 1})">Selanjutnya</button>`;
            container.innerHTML = html;
        }

        function goToPage(p) {
            state.page = p;
            renderTable();
        }

        function openCreateModal() {
            state.editingId = null;
            document.getElementById('modalTitle').textContent = 'Tambah ' + categoryNames[state.category];
            renderModalFields({});
            openModal('formModal');
        }

        function openEditModal(id) {
            state.editingId = id;
            document.getElementById('modalTitle').textContent = 'Edit ' + categoryNames[state.category];
            let item = {};
            if (state.category === 'prodi') item = state.studyPrograms.find(x => x.id === id) || {};
            else if (state.category === 'fakultas') item = state.faculties.find(x => x.id === id) || {};
            else if (state.category === 'matkul') item = state.courses.find(x => x.id === id) || {};
            else item = state.curriculums.find(x => x.id === id) || {};
            renderModalFields(item);
            openModal('formModal');
        }

        function renderModalFields(item) {
            const container = document.getElementById('modalFields');
            const isActive = item.is_active !== false && item.is_active !== 0;

            if (state.category === 'prodi') {
                const facOptions = state.faculties.map(f => `<option value="${f.id}" ${item.faculty_id == f.id ? 'selected' : ''}>${escapeHtml(f.name)}</option>`).join('');
                const lvlOptions = state.educationLevels.map(l => `<option value="${l.id}" ${item.education_level_id == l.id ? 'selected' : ''}>${escapeHtml(l.code)} - ${escapeHtml(l.name)}</option>`).join('');
                container.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Kode Prodi *</label>
                            <input type="text" class="form-control" name="code" value="${escapeHtml(item.code || '')}" placeholder="Contoh: IF-01" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenjang *</label>
                            <select class="form-control" name="education_level_id" required>
                                <option value="">Pilih Jenjang</option>${lvlOptions}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Program Studi *</label>
                        <input type="text" class="form-control" name="name" value="${escapeHtml(item.name || '')}" placeholder="Contoh: Teknik Informatika" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fakultas *</label>
                        <select class="form-control" name="faculty_id" required>
                            <option value="">Pilih Fakultas</option>${facOptions}
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Akreditasi</label>
                            <input type="text" class="form-control" name="accreditation" value="${escapeHtml(item.accreditation || '')}" placeholder="Unggul / A / Baik Sekali">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="is_active">
                                <option value="1" ${isActive ? 'selected' : ''}>Aktif</option>
                                <option value="0" ${!isActive ? 'selected' : ''}>Nonaktif</option>
                            </select>
                        </div>
                    </div>`;
            } else if (state.category === 'fakultas') {
                container.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Kode Fakultas *</label>
                            <input type="text" class="form-control" name="code" value="${escapeHtml(item.code || '')}" placeholder="Contoh: FTI" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="is_active">
                                <option value="1" ${isActive ? 'selected' : ''}>Aktif</option>
                                <option value="0" ${!isActive ? 'selected' : ''}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Fakultas *</label>
                        <input type="text" class="form-control" name="name" value="${escapeHtml(item.name || '')}" placeholder="Contoh: Teknologi & Informatika" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Dekan</label>
                        <input type="text" class="form-control" name="dean_name" value="${escapeHtml(item.dean_name || '')}" placeholder="Contoh: Prof. Dr. Ahmad, M.T.">
                    </div>`;
            } else if (state.category === 'matkul') {
                const prodiOptions = state.studyPrograms.map(p => `<option value="${p.id}" ${item.study_program_id == p.id ? 'selected' : ''}>${escapeHtml(p.name)}</option>`).join('');
                container.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Kode Mata Kuliah *</label>
                            <input type="text" class="form-control" name="code" value="${escapeHtml(item.code || '')}" placeholder="Contoh: IF101" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Semester *</label>
                            <input type="number" class="form-control" name="semester" min="1" max="14" value="${item.semester || 1}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Mata Kuliah *</label>
                        <input type="text" class="form-control" name="name" value="${escapeHtml(item.name || '')}" placeholder="Contoh: Pemrograman Web" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">SKS Teori *</label>
                            <input type="number" class="form-control" name="credits_theory" min="0" value="${item.credits_theory ?? 2}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">SKS Praktik *</label>
                            <input type="number" class="form-control" name="credits_practical" min="0" value="${item.credits_practical ?? 1}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Program Studi</label>
                            <select class="form-control" name="study_program_id">
                                <option value="">Pilih Prodi</option>${prodiOptions}
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis *</label>
                            <select class="form-control" name="course_type" required>
                                <option value="Wajib" ${item.course_type === 'Wajib' ? 'selected' : ''}>Wajib</option>
                                <option value="Pilihan" ${item.course_type === 'Pilihan' ? 'selected' : ''}>Pilihan</option>
                                <option value="Wajib Peminatan" ${item.course_type === 'Wajib Peminatan' ? 'selected' : ''}>Wajib Peminatan</option>
                            </select>
                        </div>
                    </div>`;
            } else {
                const prodiOptions = state.studyPrograms.map(p => `<option value="${p.id}" ${item.study_program_id == p.id ? 'selected' : ''}>${escapeHtml(p.name)}</option>`).join('');
                container.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Kode Kurikulum *</label>
                            <input type="text" class="form-control" name="code" value="${escapeHtml(item.code || '')}" placeholder="Contoh: KUR-2025" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tahun Mulai *</label>
                            <input type="number" class="form-control" name="year_start" min="2000" max="2100" value="${item.year_start || 2025}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Kurikulum *</label>
                        <input type="text" class="form-control" name="name" value="${escapeHtml(item.name || '')}" placeholder="Contoh: Kurikulum Merdeka 2025" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Program Studi *</label>
                        <select class="form-control" name="study_program_id" required>
                            <option value="">Pilih Prodi</option>${prodiOptions}
                        </select>
                    </div>`;
            }
        }

        async function submitForm(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const payload = Object.fromEntries(formData.entries());
            if ('is_active' in payload) payload.is_active = payload.is_active === '1';

            const endpoints = {
                prodi: '/api/admin/study-programs',
                fakultas: '/api/admin/faculties',
                matkul: '/api/admin/courses',
                kurikulum: '/api/admin/curriculums',
            };
            const baseUrl = endpoints[state.category];
            const url = state.editingId ? `${baseUrl}/${state.editingId}` : baseUrl;
            const method = state.editingId ? 'PUT' : 'POST';

            try {
                const res = await apiFetch(url, { method, body: JSON.stringify(payload) });
                showToast(res.message || 'Data berhasil disimpan!', 'success');
                closeModal('formModal');
                await loadAllData();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function openDeleteModal(id, name) {
            state.deletingId = id;
            document.getElementById('deleteItemName').textContent = name;
            openModal('deleteModal');
        }

        async function confirmDelete() {
            const endpoints = {
                prodi: '/api/admin/study-programs',
                fakultas: '/api/admin/faculties',
                matkul: '/api/admin/courses',
                kurikulum: '/api/admin/curriculums',
            };
            try {
                const res = await apiFetch(`${endpoints[state.category]}/${state.deletingId}`, { method: 'DELETE' });
                showToast(res.message || 'Data berhasil dihapus!', 'success');
                closeModal('deleteModal');
                await loadAllData();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function exportCsv() {
            const items = getFilteredItems();
            if (!items.length) {
                showToast('Tidak ada data untuk diekspor.', 'error');
                return;
            }
            let headers = [], rows = [];
            if (state.category === 'prodi') {
                headers = ['No', 'Kode', 'Nama Program Studi', 'Jenjang', 'Fakultas', 'Status'];
                rows = items.map((p, i) => [i + 1, p.code, p.name, p.education_level?.code || '-', p.faculty?.name || '-', p.is_active ? 'Aktif' : 'Nonaktif']);
            } else if (state.category === 'fakultas') {
                headers = ['No', 'Kode', 'Nama Fakultas', 'Dekan', 'Status'];
                rows = items.map((f, i) => [i + 1, f.code, f.name, f.dean_name || '-', f.is_active ? 'Aktif' : 'Nonaktif']);
            } else if (state.category === 'matkul') {
                headers = ['No', 'Kode', 'Nama Mata Kuliah', 'SKS', 'Semester', 'Program Studi'];
                rows = items.map((c, i) => [i + 1, c.code, c.name, c.total_credits || 0, c.semester || '-', c.study_program?.name || '-']);
            } else {
                headers = ['No', 'Kode', 'Nama Kurikulum', 'Tahun Mulai', 'Program Studi'];
                rows = items.map((c, i) => [i + 1, c.code, c.name, c.year_start, c.study_program?.name || '-']);
            }
            const csv = [headers, ...rows].map(r => r.map(v => `"${String(v).replace(/"/g, '""')}"`).join(',')).join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `master-data-${state.category}.csv`;
            link.click();
        }

        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }

        function showToast(msg, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = msg;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3500);
        }

        function escapeHtml(str) {
            return String(str ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
        }

        async function handleLogout() {
            try {
                await fetch('/api/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
            } catch (_) {}
            window.location.href = '/login';
        }
    </script>
</body>
</html>
