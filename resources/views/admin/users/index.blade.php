<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Pengguna - SI-AKAD | Institut Informatika & Komputer</title>

    <!-- Google Fonts: Plus Jakarta Sans for crisp, modern typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            --color-sidebar-muted: #94a3b8;
            --color-primary-blue: #0d6efd;
            --color-primary-hover: #0b5ed7;
            --color-avatar-blue: #60a5fa;
            --color-bg-page: #f8fafc;
            --color-table-header-bg: #cfd4dc;
            --color-table-row-bg: #e2e5eb;
            --color-table-row-hover: #d7dbe3;
            --color-border: #cbd5e1;
            --color-text-dark: #1e293b;
            --color-text-muted: #64748b;
            --color-danger: #ef4444;
            --color-danger-hover: #dc2626;
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

        /* BRAND LOGO HEADER */
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

        /* NAVIGATION MENU */
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
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background-color: var(--color-sidebar-hover);
            color: #ffffff;
        }

        /* ACTIVE ITEM ACCORDING TO SCREENSHOT (Rounded border outline with subtle background) */
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

        /* SIDEBAR FOOTER (LOGOUT) */
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

        /* GLOBAL SEARCH IN HEADER (sesuai gambar) */
        .header-search {
            position: relative;
            width: 320px;
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

        /* NOTIFICATION BELL */
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

        /* USER PROFILE BADGE */
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
            max-width: 1380px;
            width: 100%;
        }

        /* PAGE TITLE & WELCOME SECTION */
        .page-header-section {
            margin-bottom: 28px;
        }

        .welcome-subtitle {
            font-size: 14.5px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 2px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.4px;
            margin-bottom: 12px;
        }

        .admin-meta-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .admin-circle-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--color-avatar-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(96, 165, 250, 0.35);
        }

        .admin-info-text {
            font-size: 15px;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-nip-badge {
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
        }

        /* API STATUS INDICATOR BAR */
        .api-status-banner {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 14px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--color-success);
        }

        .status-dot.simulated {
            background-color: var(--color-warning);
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }

        /* ========================================================
           CONTROLS: SEARCH, FILTER, & ACTION BAR
           ======================================================== */
        .action-toolbar {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        /* SEARCH INPUT DI KONTEN (sesuai gambar) */
        .content-search-box {
            position: relative;
            flex: 1;
            min-width: 280px;
            max-width: 440px;
        }

        .content-search-box input {
            width: 100%;
            padding: 11px 18px 11px 44px;
            background: #ffffff;
            border: 1.2px solid #cbd5e1;
            border-radius: 12px;
            font-family: inherit;
            font-size: 13.5px;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .content-search-box input:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .content-search-box .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
        }

        /* ROLE FILTER DROPDOWN (sesuai gambar "semua role v") */
        .filter-dropdown-wrapper {
            position: relative;
        }

        .filter-select {
            appearance: none;
            -webkit-appearance: none;
            padding: 11px 38px 11px 18px;
            background-color: #ffffff;
            border: 1.2px solid #cbd5e1;
            border-radius: 12px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .filter-select:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .filter-dropdown-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: #64748b;
            pointer-events: none;
        }

        /* BUTTON TAMBAH PENGGUNA */
        .btn-add-user {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            background-color: var(--color-primary-blue);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        }

        .btn-add-user:hover {
            background-color: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(13, 110, 253, 0.35);
        }

        .btn-add-user:active {
            transform: translateY(0);
        }

        /* ========================================================
           TABLE CONTAINER & STYLING (sesuai gambar)
           ======================================================== */
        .table-card {
            background: #d8dbe0;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .users-table thead th {
            background-color: #cfd3d9;
            color: #1e293b;
            font-size: 14.5px;
            font-weight: 700;
            padding: 16px 20px;
            border-bottom: 1px solid #bcc2cb;
            white-space: nowrap;
        }

        .users-table thead th.col-no {
            width: 60px;
            text-align: center;
        }

        .users-table thead th.col-actions {
            width: 140px;
            text-align: center;
        }

        .users-table tbody tr {
            background-color: #e2e5eb;
            transition: background-color 0.15s ease;
            border-bottom: 1px solid #d1d6de;
        }

        .users-table tbody tr:last-child {
            border-bottom: none;
        }

        .users-table tbody tr:hover {
            background-color: #d7dbe3;
        }

        .users-table tbody td {
            padding: 15px 20px;
            font-size: 14px;
            color: #1e293b;
            vertical-align: middle;
        }

        .users-table tbody td.col-no {
            text-align: center;
            font-weight: 600;
            color: #475569;
        }

        .user-name-cell {
            font-weight: 600;
            color: #0f172a;
        }

        .user-username-cell {
            font-family: inherit;
            color: #334155;
            font-weight: 500;
        }

        /* BADGES FOR ROLES */
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .role-badge.admin {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .role-badge.mahasiswa {
            background-color: #dcfce7;
            color: #166534;
        }

        .role-badge.dosen-pengajar {
            background-color: #ede9fe;
            color: #5b21b6;
        }

        .role-badge.dosen-wali {
            background-color: #fef3c7;
            color: #92400e;
        }

        .role-badge.bendahara {
            background-color: #ffedd5;
            color: #9a3412;
        }

        /* BADGES FOR STATUS */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.aktif {
            background-color: #dcfce7;
            color: #15803d;
        }

        .status-badge.nonaktif {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .status-pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: currentColor;
        }

        /* ACTION BUTTONS IN TABLE */
        .action-btn-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .btn-action.edit {
            color: var(--color-primary-blue);
        }

        .btn-action.edit:hover {
            background-color: var(--color-primary-blue);
            color: #ffffff;
            transform: scale(1.05);
        }

        .btn-action.delete {
            color: var(--color-danger);
        }

        .btn-action.delete:hover {
            background-color: var(--color-danger);
            color: #ffffff;
            transform: scale(1.05);
        }

        /* EMPTY STATE IN TABLE */
        .empty-table-state {
            padding: 48px 24px;
            text-align: center;
            color: #64748b;
        }

        .empty-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px auto;
            color: #94a3b8;
            stroke-width: 1.5;
        }

        /* SKELETON / LOADING IN TABLE */
        .skeleton-row td {
            padding: 18px 20px;
        }

        .skeleton-bar {
            height: 16px;
            background: linear-gradient(90deg, #d1d5db 25%, #e5e7eb 50%, #d1d5db 75%);
            background-size: 200% 100%;
            animation: skeletonLoading 1.5s infinite;
            border-radius: 4px;
        }

        @keyframes skeletonLoading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ========================================================
           PAGINATION SECTION
           ======================================================== */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 4px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .pagination-info {
            font-size: 13.5px;
            color: #64748b;
            font-weight: 500;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .page-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .page-btn:hover:not(:disabled) {
            border-color: var(--color-primary-blue);
            color: var(--color-primary-blue);
            background: #f8fafc;
        }

        .page-btn.active {
            background-color: var(--color-primary-blue);
            border-color: var(--color-primary-blue);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.25);
        }

        .page-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* ========================================================
           MODALS (TAMBAH, EDIT, HAPUS)
           ======================================================== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-dialog {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 540px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transform: scale(0.95);
            transition: transform 0.25s ease;
        }

        .modal-overlay.active .modal-dialog {
            transform: scale(1);
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-icon-badge {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background-color: #dbeafe;
            color: var(--color-primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-icon-badge.danger {
            background-color: #fee2e2;
            color: var(--color-danger);
        }

        .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .modal-close-btn:hover {
            background: #f1f5f9;
            color: #334155;
        }

        .modal-body {
            padding: 24px;
            max-height: 75vh;
            overflow-y: auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-col-full {
            grid-column: span 2;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }

        .form-label span.req {
            color: var(--color-danger);
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            background-color: #ffffff;
            border: 1.2px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13.5px;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--color-primary-blue);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .password-input-wrap {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle-btn:hover {
            color: #334155;
        }

        .form-hint {
            font-size: 11.5px;
            color: #64748b;
        }

        .modal-footer {
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-modal-cancel {
            padding: 9px 18px;
            background: #ffffff;
            border: 1.2px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-modal-cancel:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .btn-modal-submit {
            padding: 9px 20px;
            background: var(--color-primary-blue);
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modal-submit:hover {
            background: var(--color-primary-hover);
        }

        .btn-modal-submit.danger {
            background: var(--color-danger);
        }

        .btn-modal-submit.danger:hover {
            background: var(--color-danger-hover);
        }

        /* DELETE CONFIRMATION BODY */
        .delete-box-content {
            text-align: center;
            padding: 12px 10px;
        }

        .delete-icon-big {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background-color: #fee2e2;
            color: var(--color-danger);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px auto;
        }

        .delete-main-text {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .delete-sub-text {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.5;
        }

        .delete-target-highlight {
            font-weight: 600;
            color: #0f172a;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
            margin: 6px 0;
        }

        /* TOAST NOTIFICATION */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 200;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05);
            color: #1e293b;
            font-size: 13.5px;
            font-weight: 500;
            min-width: 320px;
            max-width: 440px;
            pointer-events: auto;
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .toast-item.show {
            transform: translateX(0);
        }

        .toast-item.success {
            border-left: 4px solid var(--color-success);
        }

        .toast-item.error {
            border-left: 4px solid var(--color-danger);
        }

        .toast-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .toast-item.success .toast-icon {
            color: var(--color-success);
        }

        .toast-item.error .toast-icon {
            color: var(--color-danger);
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 1024px) {
            .page-content {
                padding: 24px 20px 40px 20px;
            }
            .top-navbar {
                padding: 0 20px;
            }
            .sidebar {
                width: 230px;
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
            .action-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-add-user {
                margin-left: 0;
                justify-content: center;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-col-full {
                grid-column: span 1;
            }
            .header-search {
                display: none;
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
        <a href="{{ route('admin.users.index') }}" class="brand-header">
            <div class="brand-logo-wrap">
                <!-- Accurate SI-AKAD Academic Emblem SVG -->
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" width="40" height="40">
                    <!-- Outer Shield Crest -->
                    <path d="M12 30C12 30 25 38 50 38C75 38 88 30 88 30C88 30 92 68 50 94C8 68 12 30 12 30Z" fill="#ffffff" fill-opacity="0.12"/>
                    <!-- Open Book Left Page -->
                    <path d="M22 40C22 40 32 44 47 45V78C32 77 22 72 22 72V40Z" fill="#FFFFFF"/>
                    <!-- Open Book Right Page -->
                    <path d="M78 40C78 40 68 44 53 45V78C68 77 78 72 78 72V40Z" fill="#FFFFFF"/>
                    <!-- Golden Crown / Crest Accent -->
                    <path d="M50 8L62 23H38L50 8Z" fill="#F5C036"/>
                    <circle cx="50" cy="24" r="3.5" fill="#F5C036"/>
                    <!-- Center Quill / Pillar -->
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
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <!-- ACTIVE MENU SESUAI GAMBAR & HALAMAN -->
                <a href="{{ route('admin.users.index') }}" class="nav-item active">
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
                <a href="#" class="nav-item">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    <span>Master Data Akademik</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
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

        <!-- LOGOUT FOOTER -->
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
            <!-- PENCARIAN GLOBAL HEADER (sesuai gambar) -->
            <div class="header-search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" placeholder="Cari mahasiswa, dosen atau program studi...">
            </div>

            <!-- NOTIFIKASI LONCENG DENGAN BADGE (1) -->
            <button type="button" class="notif-btn" title="Notifikasi">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="notif-badge">1</span>
            </button>

            <!-- PROFILE AVATAR "AA" ADMIN AKADEMIK (sesuai gambar) -->
            <div class="header-user-profile" title="Profil Admin">
                <div class="header-avatar">AA</div>
                <span class="header-user-name">Admin Akademik</span>
            </div>
        </header>

        <!-- PAGE CONTENT CONTAINER -->
        <div class="page-content">
            <!-- PAGE TITLE & ADMIN GREETING (sesuai gambar) -->
            <div class="page-header-section">
                <div class="welcome-subtitle">Selamat Datang, Admin</div>
                <h1 class="page-title">Manajemen Pengguna</h1>

                <!-- ROW AVATAR BIRU MUDA & NIP (sesuai gambar) -->
                <div class="admin-meta-row">
                    <div class="admin-circle-avatar">
                        <!-- Silhouette Icon -->
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <div class="admin-info-text">
                        <span id="displayAdminName">Nama Admin</span>
                        <span>|</span>
                        <span class="admin-nip-badge" id="displayAdminNip">NIP</span>
                    </div>
                </div>

                <!-- API CONNECTION INDICATOR -->
                <div class="api-status-banner" id="apiStatusBadge">
                    <span class="status-dot" id="apiStatusDot"></span>
                    <span id="apiStatusText">Menghubungkan ke API Backend...</span>
                </div>
            </div>

            <!-- CONTROLS: SEARCH BAR, FILTER ROLE, & TAMBAH PENGGUNA -->
            <div class="action-toolbar">
                <!-- SEARCH INPUT (sesuai gambar "cari nama atau username...") -->
                <div class="content-search-box">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="cari nama atau username..." autocomplete="off">
                </div>

                <!-- FILTER DROPDOWN (sesuai gambar "semua role v") -->
                <div class="filter-dropdown-wrapper">
                    <select id="roleFilter" class="filter-select">
                        <option value="">semua role</option>
                        <option value="Admin">Admin</option>
                        <option value="Mahasiswa">Mahasiswa</option>
                        <option value="Dosen Pengajar">Dosen Pengajar</option>
                        <option value="Dosen Wali">Dosen Wali</option>
                        <option value="Bendahara">Bendahara</option>
                    </select>
                    <svg class="filter-dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </div>

                <!-- BUTTON TAMBAH PENGGUNA -->
                <button type="button" class="btn-add-user" onclick="openAddModal()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    <span>Tambah Pengguna</span>
                </button>
            </div>

            <!-- TABLE CONTAINER (sesuai gambar dengan header No, Nama pengguna, Username, Role, Status, Aksi) -->
            <div class="table-card">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Nama pengguna</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="col-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        <!-- SKELETON LOADING INITIAL STATE -->
                        <tr class="skeleton-row">
                            <td><div class="skeleton-bar" style="width: 20px; margin: 0 auto;"></div></td>
                            <td><div class="skeleton-bar" style="width: 160px;"></div></td>
                            <td><div class="skeleton-bar" style="width: 120px;"></div></td>
                            <td><div class="skeleton-bar" style="width: 80px;"></div></td>
                            <td><div class="skeleton-bar" style="width: 60px;"></div></td>
                            <td><div class="skeleton-bar" style="width: 70px; margin: 0 auto;"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION CONTAINER -->
            <div class="pagination-container">
                <div class="pagination-info" id="paginationInfo">
                    Memuat data pengguna...
                </div>
                <div class="pagination-controls" id="paginationControls">
                    <!-- Dinamis via JavaScript -->
                </div>
            </div>
        </div>
    </main>

    <!-- ========================================================
         MODAL 1: TAMBAH PENGGUNA BARU
         ======================================================== -->
    <div class="modal-overlay" id="addUserModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-title-row">
                    <div class="modal-icon-badge">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <line x1="20" y1="8" x2="20" y2="14"/>
                            <line x1="23" y1="11" x2="17" y2="11"/>
                        </svg>
                    </div>
                    <h3 class="modal-title">Tambah Pengguna Baru</h3>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeModal('addUserModal')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form id="addUserForm" onsubmit="handleCreateUser(event)">
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group form-col-full">
                            <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" id="addName" class="form-control" placeholder="cth: Drs. H. Hendra Wijaya, M.Kom" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username <span class="req">*</span></label>
                            <input type="text" id="addUsername" class="form-control" placeholder="cth: hendra.wijaya" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email <span class="req">*</span></label>
                            <input type="email" id="addEmail" class="form-control" placeholder="cth: hendra@siakad.ac.id" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role Pengguna <span class="req">*</span></label>
                            <select id="addRole" class="form-control" required>
                                <option value="Admin">Admin</option>
                                <option value="Mahasiswa" selected>Mahasiswa</option>
                                <option value="Dosen Pengajar">Dosen Pengajar</option>
                                <option value="Dosen Wali">Dosen Wali</option>
                                <option value="Bendahara">Bendahara</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">NPM / NIP / NIDN</label>
                            <input type="text" id="addNpm" class="form-control" placeholder="cth: 198501012010121001">
                        </div>
                        <div class="form-group form-col-full">
                            <label class="form-label">Status Akun</label>
                            <select id="addStatus" class="form-control">
                                <option value="Aktif" selected>Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi <span class="req">*</span></label>
                            <div class="password-input-wrap">
                                <input type="password" id="addPassword" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('addPassword')">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Sandi <span class="req">*</span></label>
                            <div class="password-input-wrap">
                                <input type="password" id="addPasswordConfirm" class="form-control" placeholder="Ulangi kata sandi" required minlength="6">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('addPasswordConfirm')">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('addUserModal')">Batal</button>
                    <button type="submit" class="btn-modal-submit" id="btnAddSubmit">
                        <span>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================
         MODAL 2: EDIT PENGGUNA
         ======================================================== -->
    <div class="modal-overlay" id="editUserModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-title-row">
                    <div class="modal-icon-badge">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </div>
                    <h3 class="modal-title">Edit Data Pengguna</h3>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeModal('editUserModal')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form id="editUserForm" onsubmit="handleUpdateUser(event)">
                <input type="hidden" id="editUserId">
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group form-col-full">
                            <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" id="editName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username <span class="req">*</span></label>
                            <input type="text" id="editUsername" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email <span class="req">*</span></label>
                            <input type="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role Pengguna <span class="req">*</span></label>
                            <select id="editRole" class="form-control" required>
                                <option value="Admin">Admin</option>
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Dosen Pengajar">Dosen Pengajar</option>
                                <option value="Dosen Wali">Dosen Wali</option>
                                <option value="Bendahara">Bendahara</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">NPM / NIP / NIDN</label>
                            <input type="text" id="editNpm" class="form-control">
                        </div>
                        <div class="form-group form-col-full">
                            <label class="form-label">Status Akun</label>
                            <select id="editStatus" class="form-control">
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="form-group form-col-full">
                            <label class="form-label">Kata Sandi Baru (Opsional)</label>
                            <div class="password-input-wrap">
                                <input type="password" id="editPassword" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah sandi">
                                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('editPassword')">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="form-hint">Hanya isi kolom ini jika pengguna ingin mengubah kata sandinya (minimal 6 karakter).</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('editUserModal')">Batal</button>
                    <button type="submit" class="btn-modal-submit" id="btnEditSubmit">
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================
         MODAL 3: KONFIRMASI HAPUS PENGGUNA
         ======================================================== -->
    <div class="modal-overlay" id="deleteUserModal">
        <div class="modal-dialog" style="max-width: 440px;">
            <div class="modal-header">
                <div class="modal-title-row">
                    <div class="modal-icon-badge danger">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </div>
                    <h3 class="modal-title">Hapus Akun Pengguna</h3>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeModal('deleteUserModal')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteUserId">
                <div class="delete-box-content">
                    <div class="delete-icon-big">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div class="delete-main-text">Apakah Anda yakin ingin menghapus akun ini?</div>
                    <div class="delete-sub-text">
                        Akun pengguna <span class="delete-target-highlight" id="deleteUserName">Nama Pengguna</span> akan dihapus dari sistem. Tindakan ini tidak dapat dibatalkan.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('deleteUserModal')">Batal</button>
                <button type="button" class="btn-modal-submit danger" id="btnDeleteSubmit" onclick="confirmDeleteUser()">
                    <span>Ya, Hapus Akun</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================
         TOAST CONTAINER
         ======================================================== -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- ========================================================
         JAVASCRIPT: FRONTEND LOGIC & BACKEND API INTEGRATION
         ======================================================== -->
    <script>
        // ========================================================
        // 1. STATE & CONFIGURATION
        // ========================================================
        const API_CONFIG = {
            baseUrl: '/api/admin/users',
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };

        const state = {
            users: [],
            currentPage: 1,
            perPage: 5,
            totalPages: 1,
            totalItems: 0,
            searchQuery: '',
            selectedRole: '',
            isLoading: false,
            apiConnected: false,
            // Mock fallback dataset (memastikan frontend tetap 100% interaktif saat backend masih proses coding)
            localMockUsers: [
                {
                    id: 1,
                    name: 'Admin Akademik',
                    username: 'Admin.akademik',
                    email: 'admin@siakad.ac.id',
                    role: 'Admin',
                    npm: '198501012010121001',
                    status: 'Aktif'
                },
                {
                    id: 2,
                    name: 'Dr. Ir. Budi Santoso, M.T.',
                    username: 'budi.santoso',
                    email: 'budi@siakad.ac.id',
                    role: 'Dosen Pengajar',
                    npm: '0412088501',
                    status: 'Aktif'
                },
                {
                    id: 3,
                    name: 'Siti Rahmawati, S.Kom., M.Cs.',
                    username: 'siti.rahmawati',
                    email: 'siti.wali@siakad.ac.id',
                    role: 'Dosen Wali',
                    npm: '0425097902',
                    status: 'Aktif'
                },
                {
                    id: 4,
                    name: 'Dewi Lestari, S.E., M.Ak.',
                    username: 'dewi.lestari',
                    email: 'dewi.keuangan@siakad.ac.id',
                    role: 'Bendahara',
                    npm: 'STAF-091',
                    status: 'Aktif'
                },
                {
                    id: 5,
                    name: 'Agiel Ramadhan Pratama',
                    username: 'agiel.ramadhan',
                    email: 'agiel@student.siakad.ac.id',
                    role: 'Mahasiswa',
                    npm: '2201010142',
                    status: 'Aktif'
                },
                {
                    id: 6,
                    name: 'Rian Hidayat',
                    username: 'rian.hidayat',
                    email: 'rian@student.siakad.ac.id',
                    role: 'Mahasiswa',
                    npm: '2201010048',
                    status: 'Nonaktif'
                },
                {
                    id: 7,
                    name: 'Anisa Maharani',
                    username: 'anisa.maharani',
                    email: 'anisa@student.siakad.ac.id',
                    role: 'Mahasiswa',
                    npm: '2201010099',
                    status: 'Aktif'
                }
            ]
        };

        // ========================================================
        // 2. BACKEND API SERVICE LAYER
        // ========================================================
        const userApi = {
            /**
             * GET /api/admin/users?search=...&role=...&page=...&per_page=...
             */
            async getUsers(params = {}) {
                const query = new URLSearchParams({
                    search: params.search || '',
                    role: params.role || '',
                    page: params.page || 1,
                    per_page: params.perPage || 5
                }).toString();

                try {
                    const response = await fetch(`${API_CONFIG.baseUrl}?${query}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': API_CONFIG.csrfToken
                        }
                    });

                    if (response.ok) {
                        const json = await response.json();
                        return { ok: true, data: json };
                    }
                    throw new Error(`HTTP ${response.status}`);
                } catch (err) {
                    console.warn('Backend API connection warning, using frontend client-state:', err.message);
                    return { ok: false, error: err.message };
                }
            },

            /**
             * POST /api/admin/users
             */
            async createUser(payload) {
                try {
                    const response = await fetch(API_CONFIG.baseUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': API_CONFIG.csrfToken
                        },
                        body: JSON.stringify(payload)
                    });

                    const json = await response.json();
                    if (!response.ok) {
                        return { ok: false, message: json.message || 'Gagal menambahkan pengguna' };
                    }
                    return { ok: true, data: json };
                } catch (err) {
                    return { ok: false, error: err.message };
                }
            },

            /**
             * PUT /api/admin/users/{id}
             */
            async updateUser(id, payload) {
                try {
                    const response = await fetch(`${API_CONFIG.baseUrl}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': API_CONFIG.csrfToken
                        },
                        body: JSON.stringify(payload)
                    });

                    const json = await response.json();
                    if (!response.ok) {
                        return { ok: false, message: json.message || 'Gagal memperbarui pengguna' };
                    }
                    return { ok: true, data: json };
                } catch (err) {
                    return { ok: false, error: err.message };
                }
            },

            /**
             * DELETE /api/admin/users/{id}
             */
            async deleteUser(id) {
                try {
                    const response = await fetch(`${API_CONFIG.baseUrl}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': API_CONFIG.csrfToken
                        }
                    });

                    const json = await response.json();
                    if (!response.ok) {
                        return { ok: false, message: json.message || 'Gagal menghapus pengguna' };
                    }
                    return { ok: true, data: json };
                } catch (err) {
                    return { ok: false, error: err.message };
                }
            }
        };

        // ========================================================
        // 3. UI RENDERING & CONTROLLER
        // ========================================================
        async function fetchUsers() {
            state.isLoading = true;
            renderSkeletonLoading();

            const res = await userApi.getUsers({
                search: state.searchQuery,
                role: state.selectedRole,
                page: state.currentPage,
                perPage: state.perPage
            });

            if (res.ok && res.data.success) {
                // Backend API aktif & berhasil
                updateApiStatus(true, res.data.source === 'database' ? 'API Backend Terhubung (Database Live)' : 'API Backend Terhubung (Data Siap)');
                state.users = res.data.data;
                state.currentPage = res.data.meta.current_page;
                state.totalPages = res.data.meta.last_page;
                state.totalItems = res.data.meta.total;
                renderTable(state.users, res.data.meta.from, res.data.meta.to, res.data.meta.total);
            } else {
                // Fallback cerdas: proses filter & pagination di client menggunakan mock data
                updateApiStatus(false, 'Mode Simulasi Frontend (Menunggu API Backend dikoding)');
                processLocalMockUsers();
            }

            state.isLoading = false;
        }

        function processLocalMockUsers() {
            const query = state.searchQuery.toLowerCase().trim();
            const roleFilter = state.selectedRole.toLowerCase().trim();

            let filtered = state.localMockUsers.filter(u => {
                const matchSearch = !query || 
                    u.name.toLowerCase().includes(query) || 
                    u.username.toLowerCase().includes(query) || 
                    u.email.toLowerCase().includes(query) ||
                    (u.npm && u.npm.toLowerCase().includes(query));

                const matchRole = !roleFilter || u.role.toLowerCase() === roleFilter;

                return matchSearch && matchRole;
            });

            state.totalItems = filtered.length;
            state.totalPages = Math.max(1, Math.ceil(filtered.length / state.perPage));

            if (state.currentPage > state.totalPages) {
                state.currentPage = 1;
            }

            const offset = (state.currentPage - 1) * state.perPage;
            const paginated = filtered.slice(offset, offset + state.perPage);

            const from = filtered.length > 0 ? offset + 1 : 0;
            const to = Math.min(offset + state.perPage, filtered.length);

            renderTable(paginated, from, to, filtered.length, offset);
        }

        function renderTable(users, from, to, total, offsetBase = 0) {
            const tbody = document.getElementById('userTableBody');
            const paginationInfo = document.getElementById('paginationInfo');

            if (!users || users.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6">
                            <div class="empty-table-state">
                                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                <div style="font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 4px;">Pengguna Tidak Ditemukan</div>
                                <div style="font-size: 13px;">Tidak ada akun pengguna yang sesuai dengan pencarian atau filter yang dipilih.</div>
                            </div>
                        </td>
                    </tr>
                `;
                paginationInfo.textContent = 'Menampilkan 0 pengguna';
                renderPaginationControls();
                return;
            }

            tbody.innerHTML = users.map((u, idx) => {
                const rowNo = u.row_number || (offsetBase + idx + 1);
                const roleClass = getRoleClass(u.role);
                const statusClass = (u.status || 'Aktif').toLowerCase() === 'aktif' ? 'aktif' : 'nonaktif';
                const statusLabel = u.status || 'Aktif';

                return `
                    <tr>
                        <td class="col-no">${rowNo}</td>
                        <td class="user-name-cell">${escapeHtml(u.name)}</td>
                        <td class="user-username-cell">${escapeHtml(u.username)}</td>
                        <td>
                            <span class="role-badge ${roleClass}">${escapeHtml(u.role)}</span>
                        </td>
                        <td>
                            <span class="status-badge ${statusClass}">
                                <span class="status-pill-dot"></span>
                                ${statusLabel}
                            </span>
                        </td>
                        <td class="col-actions">
                            <div class="action-btn-group">
                                <button type="button" class="btn-action edit" title="Edit Pengguna" onclick="openEditModal(${u.id})">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button type="button" class="btn-action delete" title="Hapus Pengguna" onclick="openDeleteModal(${u.id})">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            paginationInfo.textContent = `Menampilkan ${from} - ${to} dari ${total} pengguna`;
            renderPaginationControls();
        }

        function renderSkeletonLoading() {
            const tbody = document.getElementById('userTableBody');
            let skeletons = '';
            for (let i = 0; i < state.perPage; i++) {
                skeletons += `
                    <tr class="skeleton-row">
                        <td><div class="skeleton-bar" style="width: 20px; margin: 0 auto;"></div></td>
                        <td><div class="skeleton-bar" style="width: 170px;"></div></td>
                        <td><div class="skeleton-bar" style="width: 130px;"></div></td>
                        <td><div class="skeleton-bar" style="width: 90px;"></div></td>
                        <td><div class="skeleton-bar" style="width: 60px;"></div></td>
                        <td><div class="skeleton-bar" style="width: 70px; margin: 0 auto;"></div></td>
                    </tr>
                `;
            }
            tbody.innerHTML = skeletons;
        }

        function renderPaginationControls() {
            const container = document.getElementById('paginationControls');
            if (state.totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';

            // Previous Button
            html += `
                <button type="button" class="page-btn" ${state.currentPage <= 1 ? 'disabled' : ''} onclick="goToPage(${state.currentPage - 1})">
                    ‹
                </button>
            `;

            // Page numbers
            for (let p = 1; p <= state.totalPages; p++) {
                html += `
                    <button type="button" class="page-btn ${p === state.currentPage ? 'active' : ''}" onclick="goToPage(${p})">
                        ${p}
                    </button>
                `;
            }

            // Next Button
            html += `
                <button type="button" class="page-btn" ${state.currentPage >= state.totalPages ? 'disabled' : ''} onclick="goToPage(${state.currentPage + 1})">
                    ›
                </button>
            `;

            container.innerHTML = html;
        }

        function goToPage(page) {
            if (page < 1 || page > state.totalPages || page === state.currentPage) return;
            state.currentPage = page;
            fetchUsers();
        }

        function updateApiStatus(isConnected, message) {
            const badge = document.getElementById('apiStatusBadge');
            const dot = document.getElementById('apiStatusDot');
            const text = document.getElementById('apiStatusText');

            state.apiConnected = isConnected;

            if (isConnected) {
                dot.classList.remove('simulated');
                text.textContent = message;
                badge.style.borderColor = '#bbf7d0';
                badge.style.background = '#f0fdf4';
                badge.style.color = '#166534';
            } else {
                dot.classList.add('simulated');
                text.textContent = message;
                badge.style.borderColor = '#fde68a';
                badge.style.background = '#fffbeb';
                badge.style.color = '#92400e';
            }
        }

        function getRoleClass(role) {
            switch ((role || '').toLowerCase()) {
                case 'admin': return 'admin';
                case 'mahasiswa': return 'mahasiswa';
                case 'dosen pengajar': return 'dosen-pengajar';
                case 'dosen wali': return 'dosen-wali';
                case 'bendahara': return 'bendahara';
                default: return 'admin';
            }
        }

        // ========================================================
        // 4. MODAL INTERACTIONS (TAMBAH, EDIT, HAPUS)
        // ========================================================
        function openAddModal() {
            document.getElementById('addUserForm').reset();
            document.getElementById('addRole').value = 'Mahasiswa';
            document.getElementById('addStatus').value = 'Aktif';
            openModal('addUserModal');
        }

        async function handleCreateUser(e) {
            e.preventDefault();

            const name = document.getElementById('addName').value.trim();
            const username = document.getElementById('addUsername').value.trim();
            const email = document.getElementById('addEmail').value.trim();
            const role = document.getElementById('addRole').value;
            const npm = document.getElementById('addNpm').value.trim();
            const status = document.getElementById('addStatus').value;
            const password = document.getElementById('addPassword').value;
            const passwordConfirm = document.getElementById('addPasswordConfirm').value;

            if (password !== passwordConfirm) {
                showToast('Konfirmasi kata sandi tidak cocok.', true);
                return;
            }

            const payload = {
                name,
                username,
                email,
                role,
                npm,
                status,
                password
            };

            const submitBtn = document.getElementById('btnAddSubmit');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Menyimpan...</span>';

            // Panggil API Backend
            const res = await userApi.createUser(payload);

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Simpan Pengguna</span>';

            if (res.ok) {
                showToast(res.data.message || 'Akun pengguna berhasil ditambahkan!', false);
                closeModal('addUserModal');
                fetchUsers();
            } else {
                // Jika backend offline/belum dibuat endpointnya, simpan di state lokal untuk demo
                if (!state.apiConnected || res.error) {
                    const newId = Date.now();
                    state.localMockUsers.unshift({
                        id: newId,
                        name,
                        username,
                        email,
                        role,
                        npm,
                        status
                    });
                    showToast('Pengguna berhasil ditambahkan ke sistem!', false);
                    closeModal('addUserModal');
                    fetchUsers();
                } else {
                    showToast(res.message || 'Gagal menambahkan akun pengguna.', true);
                }
            }
        }

        function openEditModal(id) {
            // Cari data user dari state aktif atau mock
            let user = state.users.find(u => u.id == id);
            if (!user) {
                user = state.localMockUsers.find(u => u.id == id);
            }

            if (!user) {
                showToast('Data pengguna tidak ditemukan.', true);
                return;
            }

            document.getElementById('editUserId').value = user.id;
            document.getElementById('editName').value = user.name || '';
            document.getElementById('editUsername').value = user.username || '';
            document.getElementById('editEmail').value = user.email || '';
            document.getElementById('editRole').value = user.role || 'Mahasiswa';
            document.getElementById('editNpm').value = user.npm || user.identity_number || '';
            document.getElementById('editStatus').value = user.status || 'Aktif';
            document.getElementById('editPassword').value = '';

            openModal('editUserModal');
        }

        async function handleUpdateUser(e) {
            e.preventDefault();

            const id = document.getElementById('editUserId').value;
            const name = document.getElementById('editName').value.trim();
            const username = document.getElementById('editUsername').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            const role = document.getElementById('editRole').value;
            const npm = document.getElementById('editNpm').value.trim();
            const status = document.getElementById('editStatus').value;
            const password = document.getElementById('editPassword').value;

            const payload = {
                name,
                username,
                email,
                role,
                npm,
                status
            };

            if (password) {
                if (password.length < 6) {
                    showToast('Kata sandi baru minimal 6 karakter.', true);
                    return;
                }
                payload.password = password;
            }

            const submitBtn = document.getElementById('btnEditSubmit');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Menyimpan...</span>';

            const res = await userApi.updateUser(id, payload);

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Simpan Perubahan</span>';

            if (res.ok) {
                showToast(res.data.message || 'Data pengguna berhasil diperbarui!', false);
                closeModal('editUserModal');
                fetchUsers();
            } else {
                // Fallback simpan di mock lokal
                if (!state.apiConnected || res.error) {
                    const localIdx = state.localMockUsers.findIndex(u => u.id == id);
                    if (localIdx !== -1) {
                        state.localMockUsers[localIdx] = {
                            ...state.localMockUsers[localIdx],
                            name,
                            username,
                            email,
                            role,
                            npm,
                            status
                        };
                    }
                    showToast('Data pengguna berhasil diperbarui!', false);
                    closeModal('editUserModal');
                    fetchUsers();
                } else {
                    showToast(res.message || 'Gagal memperbarui data pengguna.', true);
                }
            }
        }

        function openDeleteModal(id) {
            let user = state.users.find(u => u.id == id);
            if (!user) {
                user = state.localMockUsers.find(u => u.id == id);
            }

            if (!user) {
                showToast('Data pengguna tidak ditemukan.', true);
                return;
            }

            document.getElementById('deleteUserId').value = user.id;
            document.getElementById('deleteUserName').textContent = `${user.name} (${user.role})`;

            openModal('deleteUserModal');
        }

        async function confirmDeleteUser() {
            const id = document.getElementById('deleteUserId').value;
            const submitBtn = document.getElementById('btnDeleteSubmit');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Menghapus...</span>';

            const res = await userApi.deleteUser(id);

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Ya, Hapus Akun</span>';

            if (res.ok) {
                showToast(res.data.message || 'Akun pengguna berhasil dihapus.', false);
                closeModal('deleteUserModal');
                fetchUsers();
            } else {
                // Fallback di mock lokal
                if (!state.apiConnected || res.error) {
                    state.localMockUsers = state.localMockUsers.filter(u => u.id != id);
                    showToast('Akun pengguna berhasil dihapus.', false);
                    closeModal('deleteUserModal');
                    fetchUsers();
                } else {
                    showToast(res.message || 'Gagal menghapus akun pengguna.', true);
                }
            }
        }

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }

        function showToast(message, isError = false) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast-item ${isError ? 'error' : 'success'}`;

            const iconSvg = isError 
                ? `<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`
                : `<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`;

            toast.innerHTML = `
                ${iconSvg}
                <span>${escapeHtml(message)}</span>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3500);
        }

        function handleLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari sistem SI-AKAD?')) {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': API_CONFIG.csrfToken
                    }
                }).finally(() => {
                    window.location.href = '/login';
                });
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // ========================================================
        // 5. EVENT LISTENERS & INITIALIZATION
        // ========================================================
        document.addEventListener('DOMContentLoaded', () => {
            // Pencarian debounced realtime
            const searchInput = document.getElementById('searchInput');
            let debounceTimer;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    state.searchQuery = e.target.value;
                    state.currentPage = 1;
                    fetchUsers();
                }, 300);
            });

            // Filter Role
            const roleFilter = document.getElementById('roleFilter');
            roleFilter.addEventListener('change', (e) => {
                state.selectedRole = e.target.value;
                state.currentPage = 1;
                fetchUsers();
            });

            // Close modal with ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                        modal.classList.remove('active');
                    });
                }
            });

            // Close modal by clicking outside
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.remove('active');
                    }
                });
            });

            // Check if user session has admin info
            const storedUser = localStorage.getItem('siakad_user');
            if (storedUser) {
                try {
                    const u = JSON.parse(storedUser);
                    if (u.name) document.getElementById('displayAdminName').textContent = u.name;
                    if (u.npm) document.getElementById('displayAdminNip').textContent = 'NIP: ' + u.npm;
                } catch (e) {}
            } else {
                document.getElementById('displayAdminName').textContent = 'Admin Akademik';
                document.getElementById('displayAdminNip').textContent = '198501012010121001';
            }

            // Initial load
            fetchUsers();
        });
    </script>
</body>
</html>
