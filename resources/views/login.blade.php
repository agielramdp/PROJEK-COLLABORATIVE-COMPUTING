<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SI-AKAD - Sistem Informasi Akademik | Institut Informatika & Komputer</title>
    
    <!-- Google Fonts: Plus Jakarta Sans for crisp, modern typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Google reCAPTCHA v2 Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --color-admin-bg: #639cf6;
            --color-mahasiswa-bg: #7ee06e;
            --color-dosen-pengajar-bg: #b6a6f6;
            --color-dosen-wali-bg: #945e2a;
            --color-bendahara-bg: #f6e3c5;
            --color-navy: #0e2954;
            --color-dark-text: #050b14;
            --color-sub-text: #334155;
            --color-hero-blue: #0b5ed7;
            --color-hero-cyan: #56a5ff;
            --color-primary-blue: #0d6efd;
            --color-btn-blue: #1360cc;
        }

        body {
            font-family: var(--font-family);
            background-color: #0b192e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-dark-text);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Ambient background glow */
        .page-background {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(13, 110, 253, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(30, 60, 110, 0.25) 0%, transparent 50%),
                        #0c1b33;
            z-index: 0;
        }

        /* Google button not-configured notice */
        .google-not-configured-msg {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12.5px;
            color: #92400e;
            margin-top: 10px;
        }

        /* Main Container Card */
        .portal-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1140px;
            margin: 24px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.08);
            display: grid;
            grid-template-columns: 52% 48%;
            min-height: 640px;
            transition: all 0.3s ease;
        }



        /* LEFT COLUMN */
        .left-panel {
            position: relative;
            background: #ffffff;
            padding: 44px 48px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            overflow: hidden;
            z-index: 2;
        }

        /* Corner metallic pearlescent reflection */
        .left-panel::after {
            content: '';
            position: absolute;
            left: -120px;
            bottom: -120px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(160, 180, 215, 0.55) 0%, rgba(200, 215, 240, 0.25) 45%, transparent 75%);
            filter: blur(28px);
            pointer-events: none;
            z-index: 1;
        }

        .left-content {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Brand Header */
        .brand-header {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .brand-logo-svg {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.08));
        }

        .brand-text-col {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--color-navy);
            letter-spacing: -0.5px;
            line-height: 1.15;
        }

        .brand-sub1 {
            font-size: 13px;
            font-weight: 600;
            color: #4b586d;
            line-height: 1.35;
            margin-top: 2px;
        }

        .brand-sub2 {
            font-size: 13px;
            font-weight: 600;
            color: #4b586d;
            line-height: 1.35;
        }

        /* Welcome Section */
        .welcome-section {
            margin-top: 36px;
            margin-bottom: 24px;
        }

        .welcome-title {
            font-size: 28px;
            font-weight: 900;
            color: #000000;
            letter-spacing: -0.6px;
            line-height: 1.15;
            text-transform: uppercase;
        }

        .welcome-desc {
            font-size: 14.5px;
            font-weight: 500;
            color: #1e293b;
            margin-top: 8px;
            line-height: 1.4;
        }

        /* Role Cards Grid */
        .roles-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            width: 100%;
        }

        /* Base Role Card */
        .role-card {
            border: none;
            outline: none;
            border-radius: 18px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: left;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            user-select: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .role-card:hover {
            transform: translateY(-3px) scale(1.015);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
            filter: brightness(1.03);
        }

        .role-card:active {
            transform: translateY(0) scale(0.99);
        }

        .role-card-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .role-icon-box {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .role-icon-box svg {
            width: 100%;
            height: 100%;
            color: #000000;
        }

        .role-info {
            display: flex;
            flex-direction: column;
        }

        .role-name {
            font-size: 17px;
            font-weight: 800;
            color: #000000;
            line-height: 1.2;
            letter-spacing: -0.2px;
        }

        .role-desc {
            font-size: 12.5px;
            font-weight: 500;
            color: #000000;
            line-height: 1.25;
            margin-top: 2px;
            opacity: 0.92;
            white-space: pre-line;
        }

        .role-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-left: 6px;
            transition: transform 0.2s ease;
        }

        .role-card:hover .role-arrow {
            transform: translateX(4px);
        }

        .role-arrow svg {
            width: 22px;
            height: 22px;
            stroke: #000000;
            stroke-width: 2.4;
            fill: none;
        }

        /* Specific Role Colors */
        .card-admin {
            background-color: var(--color-admin-bg);
            box-shadow: 0 6px 16px rgba(99, 156, 246, 0.28);
        }

        .card-mahasiswa {
            background-color: var(--color-mahasiswa-bg);
            box-shadow: 0 6px 16px rgba(126, 224, 110, 0.28);
        }

        .card-dosen-pengajar {
            background-color: var(--color-dosen-pengajar-bg);
            box-shadow: 0 6px 16px rgba(182, 166, 246, 0.28);
        }

        .card-dosen-wali {
            background-color: var(--color-dosen-wali-bg);
            box-shadow: 0 6px 16px rgba(148, 94, 42, 0.28);
        }

        .card-bendahara {
            grid-column: span 2;
            background-color: var(--color-bendahara-bg);
            padding: 16px 22px;
            box-shadow: 0 6px 16px rgba(246, 227, 197, 0.38);
        }

        .card-bendahara .role-icon-box {
            width: 46px;
            height: 46px;
        }

        /* RIGHT COLUMN (HERO & CAMPUS IMAGE) */
        .right-panel {
            position: relative;
            background-color: #0a4ebd;
            background-image: url('/images/campus.jpg');
            background-size: cover;
            background-position: center bottom;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 48px;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, 
                rgba(2, 60, 156, 0.94) 0%, 
                rgba(6, 85, 204, 0.82) 34%, 
                rgba(8, 98, 220, 0.45) 58%, 
                rgba(6, 60, 160, 0.15) 80%,
                rgba(0, 0, 0, 0.25) 100%
            );
            z-index: 1;
            pointer-events: none;
        }

        .right-content {
            position: relative;
            z-index: 2;
            max-width: 440px;
        }

        /* Pill Badge: SI-AKAD */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #0a64e8;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 800;
            letter-spacing: 0.4px;
            padding: 6px 20px;
            border-radius: 9999px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
            margin-bottom: 22px;
        }

        /* Main Hero Headline */
        .hero-headline {
            font-size: 40px;
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: -0.8px;
            margin-bottom: 20px;
        }

        .hero-headline .text-white {
            color: #ffffff;
            display: block;
        }

        .hero-headline .text-cyan {
            color: var(--color-hero-cyan);
            display: block;
        }

        .hero-subtext {
            font-size: 15px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.92);
            line-height: 1.55;
            max-width: 390px;
            letter-spacing: -0.1px;
        }

        /* Architectural Signage Overlay */
        .building-signage-overlay {
            position: absolute;
            right: 32px;
            bottom: 60px;
            background: rgba(14, 46, 92, 0.88);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            z-index: 2;
            transition: transform 0.3s ease;
        }

        .building-signage-overlay:hover {
            transform: translateY(-2px);
        }

        .signage-logo {
            width: 26px;
            height: 26px;
            filter: brightness(0) invert(1);
        }

        .signage-text {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.8px;
            color: #ffffff;
            text-transform: uppercase;
            line-height: 1.2;
        }

        /* ============================================================ */
        /* RIGHT PANEL: SECURITY ILLUSTRATION (SHARED: FORGOT + ADMIN LOGIN) */
        /* ============================================================ */
        .right-panel-security {
            position: relative;
            background: #10438c;
            display: none;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 40px;
        }

        .right-panel-security::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 70% 30%, rgba(25, 90, 190, 0.6) 0%, #0d3b7e 100%);
            z-index: 1;
        }

        .security-illustration-svg {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            height: auto;
            filter: drop-shadow(0 15px 35px rgba(0, 0, 0, 0.3));
        }

        /* ============================================================ */
        /* ADMIN LOGIN REDESIGNED STYLES (MATCHING REFERENCE IMAGE)     */
        /* ============================================================ */

        /* Admin login specific back nav */
        .admin-back-nav {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 20px;
            transition: color 0.2s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .admin-back-nav:hover {
            color: #1e293b;
            transform: translateX(-3px);
        }

        /* Admin Login Title */
        .admin-login-title {
            font-size: 30px;
            font-weight: 900;
            color: #0d1b2a;
            letter-spacing: -0.6px;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .admin-login-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #475569;
            line-height: 1.45;
            margin-bottom: 26px;
        }

        /* Admin form inputs */
        .admin-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .admin-input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
            flex-shrink: 0;
        }

        .admin-form-input {
            width: 100%;
            height: 50px;
            padding: 10px 14px 10px 44px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .admin-form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .admin-form-input:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.12);
            background: #fff;
        }

        .admin-toggle-pass {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .admin-toggle-pass:hover { color: #475569; }

        /* Admin Login Button */
        .admin-login-btn {
            width: 100%;
            height: 50px;
            background: #1e3a8a;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 6px;
            margin-bottom: 12px;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(30, 58, 138, 0.3);
            letter-spacing: 0.1px;
        }

        .admin-login-btn:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
        }

        .admin-login-btn:active {
            transform: translateY(0);
        }

        .admin-login-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Google Login Button for admin */
        .admin-google-btn {
            width: 100%;
            height: 50px;
            background: #ffffff;
            color: #1e293b;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-bottom: 16px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .admin-google-btn:hover {
            border-color: #b0bccc;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }

        /* Lupa password link admin */
        .admin-forgot-link {
            display: block;
            text-align: center;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.15s ease;
            background: none;
            border: none;
            width: 100%;
        }

        .admin-forgot-link:hover {
            color: #1e3a8a;
        }

        /* Right panel for admin login - server/shield illustration */
        .right-panel-admin-login {
            position: relative;
            background: linear-gradient(135deg, #1a3a6b 0%, #0f2550 40%, #0a1a3f 100%);
            display: none;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 40px;
        }

        .right-panel-admin-login.active {
            display: flex;
        }

        .right-panel-admin-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 60% 40%, rgba(30, 80, 180, 0.45) 0%, transparent 65%),
                        radial-gradient(ellipse at 30% 80%, rgba(10, 50, 120, 0.4) 0%, transparent 55%);
            z-index: 1;
        }

        /* Watermark SI letters */
        .admin-right-content {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ============================================================ */
        /* MAHASISWA LOGIN REDESIGNED STYLES (GREEN THEME)              */
        /* ============================================================ */

        /* Mahasiswa back nav */
        .mahasiswa-back-nav {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 20px;
            transition: color 0.2s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .mahasiswa-back-nav:hover {
            color: #1e293b;
            transform: translateX(-3px);
        }

        /* Mahasiswa Login Title */
        .mahasiswa-login-title {
            font-size: 30px;
            font-weight: 900;
            color: #14532d;
            letter-spacing: -0.6px;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .mahasiswa-login-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #475569;
            line-height: 1.45;
            margin-bottom: 26px;
        }

        /* Mahasiswa form inputs */
        .mahasiswa-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .mahasiswa-input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
            flex-shrink: 0;
        }

        .mahasiswa-form-input {
            width: 100%;
            height: 50px;
            padding: 10px 14px 10px 44px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .mahasiswa-form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .mahasiswa-form-input:focus {
            border-color: #166534;
            box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.12);
            background: #fff;
        }

        .mahasiswa-toggle-pass {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .mahasiswa-toggle-pass:hover { color: #475569; }

        /* Mahasiswa Login Button */
        .mahasiswa-login-btn {
            width: 100%;
            height: 50px;
            background: #166534;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 6px;
            margin-bottom: 12px;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(22, 101, 52, 0.3);
            letter-spacing: 0.1px;
        }

        .mahasiswa-login-btn:hover {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22, 101, 52, 0.4);
        }

        .mahasiswa-login-btn:active {
            transform: translateY(0);
        }

        .mahasiswa-login-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Google Login Button for mahasiswa */
        .mahasiswa-google-btn {
            width: 100%;
            height: 50px;
            background: #ffffff;
            color: #1e293b;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-bottom: 16px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .mahasiswa-google-btn:hover {
            border-color: #b0bccc;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }

        /* Lupa password link mahasiswa */
        .mahasiswa-forgot-link {
            display: block;
            text-align: center;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.15s ease;
            background: none;
            border: none;
            width: 100%;
        }

        .mahasiswa-forgot-link:hover {
            color: #166534;
        }

        /* Right panel for mahasiswa login - education network illustration */
        .right-panel-mahasiswa-login {
            position: relative;
            background: linear-gradient(135deg, #1a4731 0%, #14532d 40%, #0f3d22 100%);
            display: none;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 40px;
        }

        .right-panel-mahasiswa-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 55% 35%, rgba(34, 120, 80, 0.5) 0%, transparent 65%),
                        radial-gradient(ellipse at 25% 75%, rgba(15, 70, 40, 0.45) 0%, transparent 55%);
            z-index: 1;
        }

        .mahasiswa-right-content {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ============================================================ */
        /* DOSEN PENGAJAR LOGIN REDESIGNED STYLES (PURPLE THEME)        */
        /* ============================================================ */

        .dosen-back-nav {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 20px;
            transition: color 0.2s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .dosen-back-nav:hover {
            color: #1e293b;
            transform: translateX(-3px);
        }

        .dosen-login-title {
            font-size: 30px;
            font-weight: 900;
            color: #4c1d95;
            letter-spacing: -0.6px;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .dosen-login-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #475569;
            line-height: 1.45;
            margin-bottom: 26px;
        }

        .dosen-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .dosen-input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
            flex-shrink: 0;
        }

        .dosen-form-input {
            width: 100%;
            height: 50px;
            padding: 10px 14px 10px 44px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .dosen-form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .dosen-form-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
            background: #fff;
        }

        .dosen-toggle-pass {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .dosen-toggle-pass:hover { color: #475569; }

        .dosen-login-btn {
            width: 100%;
            height: 50px;
            background: #6d28d9;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 6px;
            margin-bottom: 12px;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(109, 40, 217, 0.3);
            letter-spacing: 0.1px;
        }

        .dosen-login-btn:hover {
            background: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(109, 40, 217, 0.4);
        }

        .dosen-login-btn:active { transform: translateY(0); }

        .dosen-login-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .dosen-google-btn {
            width: 100%;
            height: 50px;
            background: #ffffff;
            color: #1e293b;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-bottom: 16px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .dosen-google-btn:hover {
            border-color: #b0bccc;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }

        .dosen-forgot-link {
            display: block;
            text-align: center;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.15s ease;
            background: none;
            border: none;
            width: 100%;
        }

        .dosen-forgot-link:hover { color: #6d28d9; }

        /* Right panel for dosen pengajar - education/teaching illustration */
        .right-panel-dosen-login {
            position: relative;
            background: linear-gradient(135deg, #4c1d95 0%, #5b21b6 40%, #6d28d9 100%);
            display: none;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 40px;
        }

        .right-panel-dosen-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 60% 35%, rgba(139, 92, 246, 0.5) 0%, transparent 65%),
                        radial-gradient(ellipse at 25% 75%, rgba(76, 29, 149, 0.45) 0%, transparent 55%);
            z-index: 1;
        }

        .dosen-right-content {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ============================================================ */
        /* DOSEN WALI LOGIN REDESIGNED STYLES (BROWN/AMBER THEME)       */
        /* ============================================================ */

        .dosen-wali-back-nav {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 20px;
            transition: color 0.2s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .dosen-wali-back-nav:hover {
            color: #1e293b;
            transform: translateX(-3px);
        }

        .dosen-wali-login-title {
            font-size: 30px;
            font-weight: 900;
            color: #78350f;
            letter-spacing: -0.6px;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .dosen-wali-login-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #475569;
            line-height: 1.45;
            margin-bottom: 26px;
        }

        .dosen-wali-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .dosen-wali-input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
            flex-shrink: 0;
        }

        .dosen-wali-form-input {
            width: 100%;
            height: 50px;
            padding: 10px 14px 10px 44px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .dosen-wali-form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .dosen-wali-form-input:focus {
            border-color: #92400e;
            box-shadow: 0 0 0 3px rgba(146, 64, 14, 0.12);
            background: #fff;
        }

        .dosen-wali-toggle-pass {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .dosen-wali-toggle-pass:hover { color: #475569; }

        .dosen-wali-login-btn {
            width: 100%;
            height: 50px;
            background: #92400e;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 6px;
            margin-bottom: 12px;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(146, 64, 14, 0.3);
            letter-spacing: 0.1px;
        }

        .dosen-wali-login-btn:hover {
            background: #a16207;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(146, 64, 14, 0.4);
        }

        .dosen-wali-login-btn:active { transform: translateY(0); }

        .dosen-wali-login-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .dosen-wali-google-btn {
            width: 100%;
            height: 50px;
            background: #ffffff;
            color: #1e293b;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-bottom: 16px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .dosen-wali-google-btn:hover {
            border-color: #b0bccc;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }

        .dosen-wali-forgot-link {
            display: block;
            text-align: center;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.15s ease;
            background: none;
            border: none;
            width: 100%;
        }

        .dosen-wali-forgot-link:hover { color: #92400e; }

        /* Right panel for dosen wali - academic counseling illustration */
        .right-panel-dosen-wali {
            position: relative;
            background: linear-gradient(135deg, #78350f 0%, #92400e 45%, #a16207 100%);
            display: none;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 40px;
        }

        .right-panel-dosen-wali::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 55% 35%, rgba(180, 100, 30, 0.5) 0%, transparent 65%),
                        radial-gradient(ellipse at 25% 75%, rgba(100, 50, 10, 0.45) 0%, transparent 55%);
            z-index: 1;
        }

        .dosen-wali-right-content {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ============================================================ */
        /* BENDAHARA LOGIN REDESIGNED STYLES (GOLDEN/AMBER THEME)       */
        /* ============================================================ */

        .bendahara-back-nav {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 20px;
            transition: color 0.2s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .bendahara-back-nav:hover {
            color: #1e293b;
            transform: translateX(-3px);
        }

        .bendahara-login-title {
            font-size: 30px;
            font-weight: 900;
            color: #b45309;
            letter-spacing: -0.6px;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .bendahara-login-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #475569;
            line-height: 1.45;
            margin-bottom: 26px;
        }

        .bendahara-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .bendahara-input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
            flex-shrink: 0;
        }

        .bendahara-form-input {
            width: 100%;
            height: 50px;
            padding: 10px 14px 10px 44px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .bendahara-form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .bendahara-form-input:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.12);
            background: #fff;
        }

        .bendahara-toggle-pass {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .bendahara-toggle-pass:hover { color: #475569; }

        .bendahara-login-btn {
            width: 100%;
            height: 50px;
            background: #d97706;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 6px;
            margin-bottom: 12px;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
            letter-spacing: 0.1px;
        }

        .bendahara-login-btn:hover {
            background: #b45309;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(217, 119, 6, 0.45);
        }

        .bendahara-login-btn:active { transform: translateY(0); }

        .bendahara-login-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .bendahara-google-btn {
            width: 100%;
            height: 50px;
            background: #ffffff;
            color: #1e293b;
            border: 1.5px solid #d1d9e6;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-bottom: 16px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .bendahara-google-btn:hover {
            border-color: #b0bccc;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }

        .bendahara-forgot-link {
            display: block;
            text-align: center;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.15s ease;
            background: none;
            border: none;
            width: 100%;
        }

        .bendahara-forgot-link:hover { color: #b45309; }

        /* Right panel for bendahara - finance illustration */
        .right-panel-bendahara {
            position: relative;
            background: linear-gradient(135deg, #c27700 0%, #d97706 45%, #f59e0b 100%);
            display: none;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 40px;
        }

        .right-panel-bendahara::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 60% 30%, rgba(255,200,50,0.35) 0%, transparent 60%),
                        radial-gradient(ellipse at 25% 75%, rgba(180,100,0,0.3) 0%, transparent 55%);
            z-index: 1;
        }

        .bendahara-right-content {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ============================================================ */
        /* VIEW TOGGLING STATE MANAGEMENT                               */
        /* ============================================================ */
        .view-section {
            display: none;
            width: 100%;
            animation: fadeIn 0.3s ease forwards;
        }

        .view-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* BACK BUTTONS */
        .back-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 24px;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .back-nav-link:hover {
            color: #0f172a;
            transform: translateX(-3px);
        }

        /* LOGIN FORM STYLES */
        .active-role-badge-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .active-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12.5px;
            font-weight: 800;
            color: #000000;
        }

        .form-header-title {
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .form-header-subtitle {
            font-size: 13.5px;
            color: #64748b;
            margin-top: 4px;
            margin-bottom: 20px;
        }

        /* Input Controls */
        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #64748b;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 46px;
            padding: 10px 14px 10px 42px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12);
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password-btn:hover {
            color: #0f172a;
        }

        .form-helper-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            margin-top: 4px;
            margin-bottom: 20px;
        }

        .remember-checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            cursor: pointer;
            font-weight: 500;
        }

        .remember-checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #0d6efd;
            border-radius: 4px;
            cursor: pointer;
        }

        .forgot-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }

        /* Primary Button */
        .submit-btn {
            width: 100%;
            height: 48px;
            background: #0d6efd;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 800;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.35);
        }

        .submit-btn:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(13, 110, 253, 0.45);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Divider text "ATAU" */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 16px 0;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* Google Sign-in Button in Login Form */
        .btn-google-login {
            width: 100%;
            height: 44px;
            background: #ffffff;
            color: #1e293b;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-google-login:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Demo Fill Helper Button */
        .demo-fill-btn {
            width: 100%;
            margin-top: 10px;
            height: 36px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .demo-fill-btn:hover {
            background: #eef2ff;
            border-color: #818cf8;
            color: #4338ca;
        }

        /* ============================================================ */
        /* GOOGLE ACCOUNT CHOOSER SCREEN (MATCHES SCREENSHOT 1)         */
        /* ============================================================ */
        .google-account-container {
            display: none;
            width: 100%;
            padding: 36px 44px;
            background: #ffffff;
            border-radius: 16px;
            flex-direction: column;
            position: relative;
        }

        .google-account-container.active {
            display: flex;
        }

        .google-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
            color: #3c4043;
            font-size: 15px;
            font-weight: 600;
        }

        .google-g-logo {
            width: 22px;
            height: 22px;
        }

        .google-body-grid {
            display: grid;
            grid-template-columns: 42% 58%;
            gap: 40px;
            padding-top: 36px;
        }

        .google-left-col {
            display: flex;
            flex-direction: column;
        }

        .google-title {
            font-size: 32px;
            font-weight: 700;
            color: #202124;
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }

        .google-subtitle {
            font-size: 15px;
            color: #5f6368;
            line-height: 1.5;
        }

        .google-subtitle strong {
            color: #1a73e8;
            font-weight: 700;
        }

        .google-accounts-list {
            display: flex;
            flex-direction: column;
        }

        .google-account-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 8px;
            border-bottom: 1px solid #dadce0;
            cursor: pointer;
            transition: background-color 0.15s ease;
            border-radius: 4px;
        }

        .google-account-item:hover {
            background-color: #f8f9fa;
        }

        .google-avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #1a73e8;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .google-account-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .google-user-name {
            font-size: 15px;
            font-weight: 600;
            color: #202124;
            line-height: 1.3;
        }

        .google-user-email {
            font-size: 13.5px;
            color: #5f6368;
            line-height: 1.3;
        }

        .google-account-item.add-account {
            gap: 16px;
        }

        .google-icon-circle-outline {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5f6368;
            flex-shrink: 0;
        }

        .google-disclaimer {
            font-size: 12.5px;
            color: #5f6368;
            line-height: 1.5;
            margin-top: 32px;
        }

        /* ============================================================ */
        /* LUPA PASSWORD SCREEN (MATCHES SCREENSHOT 2)                  */
        /* ============================================================ */
        .forgot-pass-heading {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .forgot-pass-desc {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .forgot-input-label {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
            display: block;
        }

        .forgot-email-input {
            width: 100%;
            height: 46px;
            padding: 10px 14px 10px 42px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            transition: all 0.2s ease;
        }

        .forgot-email-input:focus {
            border-color: #1360cc;
            box-shadow: 0 0 0 3px rgba(19, 96, 204, 0.15);
        }

        .btn-kirim-otp {
            width: 100%;
            height: 46px;
            background: #1360cc;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 700;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.2s ease;
        }

        .btn-kirim-otp:hover {
            background: #0f4ea8;
        }

        .btn-kirim-otp:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        /* OTP Code Verification Inputs */
        .otp-inputs-row {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 16px 0;
        }

        .otp-digit-input {
            width: 44px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            font-weight: 800;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            font-family: inherit;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .otp-digit-input:focus {
            border-color: #1360cc;
            box-shadow: 0 0 0 3px rgba(19, 96, 204, 0.15);
        }

        /* SUCCESS MODAL POPUP */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .modal-backdrop.active {
            display: flex;
            opacity: 1;
        }

        .modal-box {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 460px;
            padding: 32px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            transform: scale(0.92);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal-backdrop.active .modal-box {
            transform: scale(1);
        }

        .modal-success-icon {
            width: 64px;
            height: 64px;
            background: #dcfce7;
            color: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .modal-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .user-preview-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            text-align: left;
        }

        .user-avatar-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            flex-shrink: 0;
        }

        .user-info-text {
            flex-grow: 1;
        }

        .user-name-title {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }

        .user-id-sub {
            font-size: 12.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .modal-btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .modal-primary-btn {
            width: 100%;
            height: 46px;
            background: #0d6efd;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 14.5px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-primary-btn:hover {
            background: #0b5ed7;
        }

        .modal-secondary-btn {
            width: 100%;
            height: 42px;
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13.5px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-secondary-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Toast notification */
        .toast-notification {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 200;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }

        .toast-notification.active {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        .toast-notification.toast-error {
            background: #991b1b;
        }

        /* Lockout / Rate Limit Banner */
        .lockout-banner {
            display: none;
            background: linear-gradient(135deg, #7f1d1d, #991b1b);
            border: 1px solid #dc2626;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 16px;
            color: #fecaca;
            font-size: 13.5px;
            font-weight: 600;
            line-height: 1.5;
            align-items: center;
            gap: 12px;
        }

        .lockout-banner.active {
            display: flex;
        }

        .lockout-banner svg {
            flex-shrink: 0;
            color: #f87171;
        }

        .lockout-timer {
            font-size: 22px;
            font-weight: 900;
            color: #fca5a5;
            letter-spacing: 1px;
        }

        .lockout-banner-text strong {
            color: #ffffff;
            display: block;
            font-size: 14px;
            margin-bottom: 2px;
        }

        /* Attempt counter badge */
        .attempt-warning {
            display: none;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 12px;
            color: #9a3412;
            font-size: 13px;
            font-weight: 600;
            align-items: center;
            gap: 8px;
        }

        .attempt-warning.active {
            display: flex;
        }

        /* reCAPTCHA wrapper */
        .recaptcha-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
            transform: scale(0.95);
            transform-origin: left center;
        }

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 960px) {
            .portal-wrapper {
                grid-template-columns: 1fr;
                max-width: 600px;
                min-height: auto;
                margin: 16px;
            }

            .left-panel {
                padding: 36px 28px;
            }

            .right-panel, .right-panel-security {
                min-height: 380px;
                padding: 36px 28px;
            }

            .hero-headline {
                font-size: 32px;
            }

            .google-body-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
        }

        @media (max-width: 480px) {
            .roles-grid {
                grid-template-columns: 1fr;
            }

            .card-bendahara {
                grid-column: span 1;
            }

            .left-panel {
                padding: 28px 20px;
            }

            .welcome-title {
                font-size: 24px;
            }

            .brand-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="page-background"></div>

    <!-- MAIN CARD CONTAINER -->
    <div class="portal-wrapper" id="portalWrapper">
        
        <!-- ========================================== -->
        <!-- VIEW 1, 2, 4: TWO-COLUMN PANELS (DEFAULT)  -->
        <!-- ========================================== -->

        <!-- LEFT PANEL -->
        <div class="left-panel" id="mainLeftPanel">
            <div class="left-content">
                
                <!-- TOP LOGO & BRAND (SHARED HEADER) -->
                <div class="brand-header" id="sharedBrandHeader" onclick="navigateView('roles')">
                    <!-- Accurate SI-AKAD Emblem SVG -->
                    <svg class="brand-logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Golden crest badge / crown -->
                        <path d="M50 4L63 20H37L50 4Z" fill="#D99B16"/>
                        <path d="M50 14L59 27H41L50 14Z" fill="#F5C036"/>
                        <circle cx="50" cy="22" r="3.5" fill="#D99B16"/>
                        
                        <!-- Outer Shield Arc -->
                        <path d="M12 30C12 30 25 38 50 38C75 38 88 30 88 30C88 30 92 68 50 94C8 68 12 30 12 30Z" fill="#0C2548"/>
                        
                        <!-- Open Book Left Page -->
                        <path d="M22 40C22 40 32 44 47 45V78C32 77 22 72 22 72V40Z" fill="#FFFFFF"/>
                        <path d="M25 43C25 43 33 46 45 47V75C33 74 25 70 25 70V43Z" fill="#143B6F"/>
                        
                        <!-- Open Book Right Page -->
                        <path d="M78 40C78 40 68 44 53 45V78C68 77 78 72 78 72V40Z" fill="#FFFFFF"/>
                        <path d="M75 43C75 43 67 46 55 47V75C67 74 75 70 75 70V43Z" fill="#143B6F"/>
                        
                        <!-- Central Spine & Information Beacon -->
                        <circle cx="50" cy="51" r="3.5" fill="#F5C036"/>
                        <path d="M48 57H52V69H48V57Z" fill="#F5C036"/>
                        <path d="M47 70H53V72H47V70Z" fill="#F5C036"/>
                        
                        <!-- Stylized Page Wings Bottom Accent -->
                        <path d="M30 76C36 78 44 79 50 79C56 79 64 78 70 76C62 82 50 86 50 86C50 86 38 82 30 76Z" fill="#D99B16"/>
                    </svg>

                    <div class="brand-text-col">
                        <h1 class="brand-title">SI-AKAD</h1>
                        <span class="brand-sub1">Sistem Informasi Akademik</span>
                        <span class="brand-sub2">Institut Informatika & Komputer</span>
                    </div>
                </div>

                <!-- SUB-VIEW 1: ROLE SELECTION (MATCHES ORIGINAL SCREENSHOT) -->
                <div class="view-section active" id="viewRoleSelection">
                    <div class="welcome-section">
                        <h2 class="welcome-title">SELAMAT DATANG</h2>
                        <p class="welcome-desc">silahkan pillih role anda untuk melanjutkan ke sistem akademik</p>
                    </div>

                    <!-- 5 ROLE BUTTONS -->
                    <div class="roles-grid">
                        
                        <!-- 1. ADMIN -->
                        <button type="button" class="role-card card-admin" id="btnRoleAdmin" onclick="selectRole('admin')">
                            <div class="role-card-left">
                                <div class="role-icon-box">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2a4 4 0 100 8 4 4 0 000-8zm-5 13c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/>
                                        <path d="M19.14 12.94c.04-.3.06-.61.06-.94s-.02-.64-.07-.94l1.35-1.05c.12-.1.16-.27.08-.41l-1.28-2.22c-.08-.14-.24-.2-.39-.14l-1.59.64c-.33-.26-.7-.47-1.1-.64l-.24-1.69c-.03-.16-.16-.28-.32-.28h-2.56c-.16 0-.3.12-.32.28l-.24 1.69c-.4.17-.77.38-1.1.64l-1.59-.64c-.15-.06-.31 0-.39.14l-1.28 2.22c-.08.14-.04.31.08.41l1.35 1.05c-.05.3-.07.61-.07.94s.02.64.07.94l-1.35 1.05c-.12.1-.16.27-.08.41l1.28 2.22c.08.14.24.2.39.14l1.59-.64c.33.26.7.47 1.1.64l.24 1.69c.03.16.16.28.32.28h2.56c.16 0 .3-.12.32-.28l.24-1.69c.4-.17.77-.38 1.1-.64l1.59.64c.15.06.31 0 .39-.14l1.28-2.22c.08-.14.04-.31-.08-.41l-1.35-1.05zm-3.14.56a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
                                    </svg>
                                </div>
                                <div class="role-info">
                                    <span class="role-name">Admin</span>
                                    <span class="role-desc">mengelola data&#10;sistem</span>
                                </div>
                            </div>
                            <div class="role-arrow">
                                <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                            </div>
                        </button>

                        <!-- 2. MAHASISWA -->
                        <button type="button" class="role-card card-mahasiswa" id="btnRoleMahasiswa" onclick="selectRole('mahasiswa')">
                            <div class="role-card-left">
                                <div class="role-icon-box">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                                    </svg>
                                </div>
                                <div class="role-info">
                                    <span class="role-name">mahasiswa</span>
                                    <span class="role-desc">akses akademik</span>
                                </div>
                            </div>
                            <div class="role-arrow">
                                <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                            </div>
                        </button>

                        <!-- 3. DOSEN PENGAJAR -->
                        <button type="button" class="role-card card-dosen-pengajar" id="btnRoleDosenPengajar" onclick="selectRole('dosen_pengajar')">
                            <div class="role-card-left">
                                <div class="role-icon-box">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="6" cy="6" r="3"/>
                                        <path d="M6 10c-2.2 0-4 1.8-4 4v5h3v-4h2v4h3v-5c0-2.2-1.8-4-4-4z"/>
                                        <path d="M11 4h11v9H11zM13 15h7v2h-7zM16 17v4h1v-4h-1z"/>
                                        <path d="M14 7h5v1.5h-5z"/>
                                    </svg>
                                </div>
                                <div class="role-info">
                                    <span class="role-name">Dosen pengajar</span>
                                    <span class="role-desc">kelola perkuliahan</span>
                                </div>
                            </div>
                            <div class="role-arrow">
                                <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                            </div>
                        </button>

                        <!-- 4. DOSEN WALI -->
                        <button type="button" class="role-card card-dosen-wali" id="btnRoleDosenWali" onclick="selectRole('dosen_wali')">
                            <div class="role-card-left">
                                <div class="role-icon-box">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="8" cy="6" r="3"/>
                                        <path d="M8 10c-2.5 0-5 1.5-5 4v4h10v-4c0-2.5-2.5-4-5-4z"/>
                                        <circle cx="17.5" cy="8.5" r="2.5"/>
                                        <path d="M17.5 12c-1.8 0-3.5 1-3.5 2.5V18h7v-3.5c0-1.5-1.7-2.5-3.5-2.5z"/>
                                        <path d="M11 15l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div class="role-info">
                                    <span class="role-name">Dosen wali</span>
                                    <span class="role-desc">bimbingan&#10;mahasiswa</span>
                                </div>
                            </div>
                            <div class="role-arrow">
                                <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                            </div>
                        </button>

                        <!-- 5. BENDAHARA (FULL WIDTH) -->
                        <button type="button" class="role-card card-bendahara" id="btnRoleBendahara" onclick="selectRole('bendahara')">
                            <div class="role-card-left">
                                <div class="role-icon-box">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 7.28V5c0-1.1-.9-2-2-2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-2.28c.59-.35 1-.98 1-1.72V9c0-.74-.41-1.37-1-1.72zM20 9v6h-7V9h7zM5 19V5h14v2h-6c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h6v2H5z"/>
                                        <circle cx="16" cy="12" r="1.5"/>
                                    </svg>
                                </div>
                                <div class="role-info">
                                    <span class="role-name">Bendahara</span>
                                    <span class="role-desc">kelola keuangan,transaksi,dan laporan keuangan</span>
                                </div>
                            </div>
                            <div class="role-arrow">
                                <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                            </div>
                        </button>

                    </div>
                </div>

                <!-- SUB-VIEW 2: ROLE LOGIN FORM (REDESIGNED FOR ADMIN - MATCHES REFERENCE IMAGE) -->
                <div class="view-section" id="viewLoginForm">

                    <!-- Bagian non-admin: badge + header lama (hanya tampil jika bukan admin) -->
                    <div id="nonAdminLoginSection">
                        <button type="button" class="back-nav-link" onclick="navigateView('roles')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Pilih Role Lain
                        </button>

                        <div class="active-role-badge-row">
                            <div class="active-role-badge" id="activeRoleBadge">
                                <span>●</span>
                                <span id="badgeRoleTitle">Admin</span>
                            </div>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Pangkalan Data Aktif</span>
                        </div>

                        <h2 class="form-header-title" id="formHeaderTitle">Masuk ke Akun Anda</h2>
                        <p class="form-header-subtitle" id="formHeaderDesc">Silakan masukkan identitas dan kata sandi Anda</p>

                        <div class="form-group">
                            <label class="form-label" for="usernameInputNonAdmin" id="usernameLabelNonAdmin">Nomor Identitas</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input type="text" id="usernameInputNonAdmin" class="form-input" placeholder="Masukkan ID pengguna" autocomplete="username">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="passwordInputNonAdmin">Kata Sandi</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <input type="password" id="passwordInputNonAdmin" class="form-input" placeholder="Masukkan kata sandi" autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-helper-row">
                            <label class="remember-checkbox-label">
                                <input type="checkbox" id="rememberMeCheckboxNonAdmin" checked>
                                <span>Ingat saya</span>
                            </label>
                            <a href="javascript:void(0)" class="forgot-link" onclick="navigateView('forgot')">Lupa Password?</a>
                        </div>

                        <!-- Lockout Banner (tampil saat terkunci) -->
                        <div class="lockout-banner" id="lockoutBanner">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <div class="lockout-banner-text">
                                <strong>Akun sementara dikunci</strong>
                                Terlalu banyak percobaan login gagal. Silakan tunggu:
                                <div class="lockout-timer" id="lockoutCountdown">05:00</div>
                            </div>
                        </div>

                        <!-- Attempt Warning (tampil setelah percobaan gagal) -->
                        <div class="attempt-warning" id="attemptWarning">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span id="attemptWarningText">Percobaan ke-1 dari 3. Akun akan dikunci setelah 3 kali gagal.</span>
                        </div>

                        <!-- reCAPTCHA v2 Widget -->
                        <div class="recaptcha-wrapper">
                            <div class="g-recaptcha"
                                 id="recaptchaWidget"
                                 data-sitekey="{{ config('services.recaptcha.site_key') }}"
                                 data-theme="light">
                            </div>
                        </div>

                        <button type="button" class="submit-btn" id="loginSubmitBtnNonAdmin" onclick="submitNonAdminLogin()">
                            <span id="submitBtnTextNonAdmin">Masuk ke SI-AKAD</span>
                            <svg id="submitBtnArrowNonAdmin" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <div class="auth-divider" style="margin-top: 14px;">atau masuk dengan</div>

                        @if(!empty($googleConfigured))
                        <a href="{{ route('google.redirect') }}" class="btn-google-login" style="text-decoration: none; margin-top: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Masuk dengan Akun Google</span>
                        </a>
                        @else
                        <button type="button" class="btn-google-login" disabled style="opacity:0.55; cursor:not-allowed;">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Masuk dengan Akun Google</span>
                        </button>
                        <div class="google-not-configured-msg">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                            <span>Isi <code>GOOGLE_CLIENT_ID</code> &amp; <code>GOOGLE_CLIENT_SECRET</code> di file <code>.env</code> untuk mengaktifkan login Google.</span>
                        </div>
                        @endif

                        <button type="button" class="demo-fill-btn" style="margin-top:10px;" onclick="fillDemoCredentials()">
                            <span>⚡ Klik untuk Isi Kredensial Demo Cepat</span>
                        </button>
                    </div>

                    <!-- ============================================ -->
                    <!-- ADMIN LOGIN SECTION (TAMPILAN BARU SESUAI GAMBAR) -->
                    <!-- ============================================ -->
                    <div id="adminLoginSection" style="display:none;">

                        <!-- Back button -->
                        <button type="button" class="admin-back-nav" onclick="navigateView('roles')">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                            Kembali ke halaman utama
                        </button>

                        <!-- Logo + Brand -->
                        <div class="brand-header" style="margin-bottom: 24px; cursor: default;">
                            <svg class="brand-logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M50 4L63 20H37L50 4Z" fill="#D99B16"/>
                                <path d="M50 14L59 27H41L50 14Z" fill="#F5C036"/>
                                <circle cx="50" cy="22" r="3.5" fill="#D99B16"/>
                                <path d="M12 30C12 30 25 38 50 38C75 38 88 30 88 30C88 30 92 68 50 94C8 68 12 30 12 30Z" fill="#0C2548"/>
                                <path d="M22 40C22 40 32 44 47 45V78C32 77 22 72 22 72V40Z" fill="#FFFFFF"/>
                                <path d="M25 43C25 43 33 46 45 47V75C33 74 25 70 25 70V43Z" fill="#143B6F"/>
                                <path d="M78 40C78 40 68 44 53 45V78C68 77 78 72 78 72V40Z" fill="#FFFFFF"/>
                                <path d="M75 43C75 43 67 46 55 47V75C67 74 75 70 75 70V43Z" fill="#143B6F"/>
                                <circle cx="50" cy="51" r="3.5" fill="#F5C036"/>
                                <path d="M48 57H52V69H48V57Z" fill="#F5C036"/>
                                <path d="M47 70H53V72H47V70Z" fill="#F5C036"/>
                                <path d="M30 76C36 78 44 79 50 79C56 79 64 78 70 76C62 82 50 86 50 86C50 86 38 82 30 76Z" fill="#D99B16"/>
                            </svg>
                            <div class="brand-text-col">
                                <span style="font-size: 22px; font-weight: 800; color: #0C2548; letter-spacing: -0.3px;">SI-AKAD</span>
                                <span style="font-size: 12.5px; font-weight: 600; color: #1e5fae;">Sistem Informasi Akademik</span>
                                <span style="font-size: 12px; font-weight: 500; color: #64748b;">Institut Informatika &amp; Komputer</span>
                            </div>
                        </div>

                        <!-- Title + subtitle -->
                        <h2 class="admin-login-title">Login Admin</h2>
                        <p class="admin-login-subtitle">Masuk dengan akun admin untuk mengelola<br>seluruh sistem akademik</p>

                        <!-- Admin Login Form -->
                        <form id="authLoginForm" onsubmit="handleApiLoginSubmit(event)">
                            <input type="hidden" id="selectedRoleInput" name="role" value="admin">

                            <!-- Username/NIP -->
                            <div class="admin-input-wrapper">
                                <svg class="admin-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input type="text" id="usernameInput" class="admin-form-input" placeholder="Username/NIP" required autocomplete="username">
                            </div>

                            <!-- Password -->
                            <div class="admin-input-wrapper">
                                <svg class="admin-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <input type="password" id="passwordInput" class="admin-form-input" placeholder="Password" required autocomplete="current-password">
                                <button type="button" class="admin-toggle-pass" onclick="togglePasswordVisibility('passwordInput', 'eyeIconOpen', 'eyeIconClosed')" aria-label="Lihat kata sandi">
                                    <svg id="eyeIconOpen" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg id="eyeIconClosed" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="admin-login-btn" id="loginSubmitBtn">
                                <span id="submitBtnText">Login</span>
                                <svg id="submitBtnArrow" width="0" height="0" style="display:none;"></svg>
                            </button>

                            <!-- Google Login -->
                            @if(!empty($googleConfigured))
                            <a href="{{ route('google.redirect') }}" class="admin-google-btn" id="btnGoogleOAuth">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </a>
                            @else
                            <button type="button" class="admin-google-btn" disabled style="opacity:0.55; cursor:not-allowed;">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </button>
                            @endif

                            <!-- Lupa Password -->
                            <button type="button" class="admin-forgot-link" onclick="navigateView('forgot')">Lupa Password?</button>

                            <!-- Demo Fill -->
                            <button type="button" class="demo-fill-btn" style="margin-top:10px;" onclick="fillDemoCredentials()">
                                <span>⚡ Klik untuk Isi Kredensial Demo Cepat</span>
                            </button>
                        </form>
                    </div>

                    <!-- ============================================ -->
                    <!-- MAHASISWA LOGIN SECTION (TAMPILAN BARU SESUAI GAMBAR) -->
                    <!-- ============================================ -->
                    <div id="mahasiswaLoginSection" style="display:none;">

                        <!-- Back button -->
                        <button type="button" class="mahasiswa-back-nav" onclick="navigateView('roles')">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                            Kembali ke halaman utama
                        </button>

                        <!-- Logo + Brand -->
                        <div class="brand-header" style="margin-bottom: 24px; cursor: default;">
                            <svg class="brand-logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M50 4L63 20H37L50 4Z" fill="#D99B16"/>
                                <path d="M50 14L59 27H41L50 14Z" fill="#F5C036"/>
                                <circle cx="50" cy="22" r="3.5" fill="#D99B16"/>
                                <path d="M12 30C12 30 25 38 50 38C75 38 88 30 88 30C88 30 92 68 50 94C8 68 12 30 12 30Z" fill="#0C2548"/>
                                <path d="M22 40C22 40 32 44 47 45V78C32 77 22 72 22 72V40Z" fill="#FFFFFF"/>
                                <path d="M25 43C25 43 33 46 45 47V75C33 74 25 70 25 70V43Z" fill="#143B6F"/>
                                <path d="M78 40C78 40 68 44 53 45V78C68 77 78 72 78 72V40Z" fill="#FFFFFF"/>
                                <path d="M75 43C75 43 67 46 55 47V75C67 74 75 70 75 70V43Z" fill="#143B6F"/>
                                <circle cx="50" cy="51" r="3.5" fill="#F5C036"/>
                                <path d="M48 57H52V69H48V57Z" fill="#F5C036"/>
                                <path d="M47 70H53V72H47V70Z" fill="#F5C036"/>
                                <path d="M30 76C36 78 44 79 50 79C56 79 64 78 70 76C62 82 50 86 50 86C50 86 38 82 30 76Z" fill="#D99B16"/>
                            </svg>
                            <div class="brand-text-col">
                                <span style="font-size: 22px; font-weight: 800; color: #0C2548; letter-spacing: -0.3px;">SI-AKAD</span>
                                <span style="font-size: 12.5px; font-weight: 600; color: #166534;">Sistem Informasi Akademik</span>
                                <span style="font-size: 12px; font-weight: 500; color: #64748b;">Institut Informatika &amp; Komputer</span>
                            </div>
                        </div>

                        <!-- Title + subtitle -->
                        <h2 class="mahasiswa-login-title">Login Mahasiswa</h2>
                        <p class="mahasiswa-login-subtitle">Masuk dengan akun mahasiswa untuk mengakses<br>seluruh layanan akademik</p>

                        <!-- Mahasiswa Login Form -->
                        <form id="authLoginFormMahasiswa" onsubmit="handleMahasiswaLoginSubmit(event)">
                            <input type="hidden" id="selectedRoleInputMahasiswa" name="role" value="mahasiswa">

                            <!-- Username/NPM -->
                            <div class="mahasiswa-input-wrapper">
                                <svg class="mahasiswa-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input type="text" id="usernameInputMahasiswa" class="mahasiswa-form-input" placeholder="Username/NPM" required autocomplete="username">
                            </div>

                            <!-- Password -->
                            <div class="mahasiswa-input-wrapper">
                                <svg class="mahasiswa-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <input type="password" id="passwordInputMahasiswa" class="mahasiswa-form-input" placeholder="Password" required autocomplete="current-password">
                                <button type="button" class="mahasiswa-toggle-pass" onclick="togglePasswordVisibility('passwordInputMahasiswa', 'eyeIconOpenMhs', 'eyeIconClosedMhs')" aria-label="Lihat kata sandi">
                                    <svg id="eyeIconOpenMhs" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg id="eyeIconClosedMhs" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="mahasiswa-login-btn" id="loginSubmitBtnMhs">
                                <span id="submitBtnTextMhs">Login</span>
                            </button>

                            <!-- Google Login -->
                            @if(!empty($googleConfigured))
                            <a href="{{ route('google.redirect') }}" class="mahasiswa-google-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </a>
                            @else
                            <button type="button" class="mahasiswa-google-btn" disabled style="opacity:0.55; cursor:not-allowed;">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </button>
                            @endif

                            <!-- Lupa Password -->
                            <button type="button" class="mahasiswa-forgot-link" onclick="navigateView('forgot')">Lupa Password?</button>

                            <!-- Demo Fill -->
                            <button type="button" class="demo-fill-btn" style="margin-top:10px;" onclick="fillDemoCredentialsMhs()">
                                <span>&#9889; Klik untuk Isi Kredensial Demo Cepat</span>
                            </button>
                        </form>
                    </div>

                    <!-- ============================================ -->
                    <!-- DOSEN PENGAJAR LOGIN SECTION (TAMPILAN BARU) -->
                    <!-- ============================================ -->
                    <div id="dosenPengajarLoginSection" style="display:none;">

                        <!-- Back button -->
                        <button type="button" class="dosen-back-nav" onclick="navigateView('roles')">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                            Kembali ke halaman utama
                        </button>

                        <!-- Logo + Brand -->
                        <div class="brand-header" style="margin-bottom: 24px; cursor: default;">
                            <svg class="brand-logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M50 4L63 20H37L50 4Z" fill="#D99B16"/>
                                <path d="M50 14L59 27H41L50 14Z" fill="#F5C036"/>
                                <circle cx="50" cy="22" r="3.5" fill="#D99B16"/>
                                <path d="M12 30C12 30 25 38 50 38C75 38 88 30 88 30C88 30 92 68 50 94C8 68 12 30 12 30Z" fill="#0C2548"/>
                                <path d="M22 40C22 40 32 44 47 45V78C32 77 22 72 22 72V40Z" fill="#FFFFFF"/>
                                <path d="M25 43C25 43 33 46 45 47V75C33 74 25 70 25 70V43Z" fill="#143B6F"/>
                                <path d="M78 40C78 40 68 44 53 45V78C68 77 78 72 78 72V40Z" fill="#FFFFFF"/>
                                <path d="M75 43C75 43 67 46 55 47V75C67 74 75 70 75 70V43Z" fill="#143B6F"/>
                                <circle cx="50" cy="51" r="3.5" fill="#F5C036"/>
                                <path d="M48 57H52V69H48V57Z" fill="#F5C036"/>
                                <path d="M47 70H53V72H47V70Z" fill="#F5C036"/>
                                <path d="M30 76C36 78 44 79 50 79C56 79 64 78 70 76C62 82 50 86 50 86C50 86 38 82 30 76Z" fill="#D99B16"/>
                            </svg>
                            <div class="brand-text-col">
                                <span style="font-size: 22px; font-weight: 800; color: #0C2548; letter-spacing: -0.3px;">SI-AKAD</span>
                                <span style="font-size: 12.5px; font-weight: 600; color: #6d28d9;">Sistem Informasi Akademik</span>
                                <span style="font-size: 12px; font-weight: 500; color: #64748b;">Institut Informatika &amp; Komputer</span>
                            </div>
                        </div>

                        <!-- Title + subtitle -->
                        <h2 class="dosen-login-title">Login Dosen Pengajar</h2>
                        <p class="dosen-login-subtitle">Masuk dengan akun dosen pengajar untuk<br>mengakses sistem akademik</p>

                        <!-- Dosen Pengajar Login Form -->
                        <form id="authLoginFormDosen" onsubmit="handleDosenLoginSubmit(event)">
                            <input type="hidden" id="selectedRoleInputDosen" name="role" value="dosen_pengajar">

                            <!-- Username/NIDN -->
                            <div class="dosen-input-wrapper">
                                <svg class="dosen-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input type="text" id="usernameInputDosen" class="dosen-form-input" placeholder="Username/NIDN" required autocomplete="username">
                            </div>

                            <!-- Password -->
                            <div class="dosen-input-wrapper">
                                <svg class="dosen-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <input type="password" id="passwordInputDosen" class="dosen-form-input" placeholder="Password" required autocomplete="current-password">
                                <button type="button" class="dosen-toggle-pass" onclick="togglePasswordVisibility('passwordInputDosen', 'eyeIconOpenDosen', 'eyeIconClosedDosen')" aria-label="Lihat kata sandi">
                                    <svg id="eyeIconOpenDosen" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg id="eyeIconClosedDosen" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="dosen-login-btn" id="loginSubmitBtnDosen">
                                <span id="submitBtnTextDosen">Login</span>
                            </button>

                            <!-- Google Login -->
                            @if(!empty($googleConfigured))
                            <a href="{{ route('google.redirect') }}" class="dosen-google-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </a>
                            @else
                            <button type="button" class="dosen-google-btn" disabled style="opacity:0.55; cursor:not-allowed;">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </button>
                            @endif

                            <!-- Lupa Password -->
                            <button type="button" class="dosen-forgot-link" onclick="navigateView('forgot')">Lupa Password?</button>

                            <!-- Demo Fill -->
                            <button type="button" class="demo-fill-btn" style="margin-top:10px;" onclick="fillDemoCredentialsDosen()">
                                <span>&#9889; Klik untuk Isi Kredensial Demo Cepat</span>
                            </button>
                        </form>
                    </div>

                    <!-- ============================================ -->
                    <!-- DOSEN WALI LOGIN SECTION (TAMPILAN BARU)    -->
                    <!-- ============================================ -->
                    <div id="dosenWaliLoginSection" style="display:none;">

                        <!-- Back button -->
                        <button type="button" class="dosen-wali-back-nav" onclick="navigateView('roles')">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                            Kembali ke halaman utama
                        </button>

                        <!-- Logo + Brand -->
                        <div class="brand-header" style="margin-bottom: 24px; cursor: default;">
                            <svg class="brand-logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M50 4L63 20H37L50 4Z" fill="#D99B16"/>
                                <path d="M50 14L59 27H41L50 14Z" fill="#F5C036"/>
                                <circle cx="50" cy="22" r="3.5" fill="#D99B16"/>
                                <path d="M12 30C12 30 25 38 50 38C75 38 88 30 88 30C88 30 92 68 50 94C8 68 12 30 12 30Z" fill="#0C2548"/>
                                <path d="M22 40C22 40 32 44 47 45V78C32 77 22 72 22 72V40Z" fill="#FFFFFF"/>
                                <path d="M25 43C25 43 33 46 45 47V75C33 74 25 70 25 70V43Z" fill="#143B6F"/>
                                <path d="M78 40C78 40 68 44 53 45V78C68 77 78 72 78 72V40Z" fill="#FFFFFF"/>
                                <path d="M75 43C75 43 67 46 55 47V75C67 74 75 70 75 70V43Z" fill="#143B6F"/>
                                <circle cx="50" cy="51" r="3.5" fill="#F5C036"/>
                                <path d="M48 57H52V69H48V57Z" fill="#F5C036"/>
                                <path d="M47 70H53V72H47V70Z" fill="#F5C036"/>
                                <path d="M30 76C36 78 44 79 50 79C56 79 64 78 70 76C62 82 50 86 50 86C50 86 38 82 30 76Z" fill="#D99B16"/>
                            </svg>
                            <div class="brand-text-col">
                                <span style="font-size: 22px; font-weight: 800; color: #0C2548; letter-spacing: -0.3px;">SI-AKAD</span>
                                <span style="font-size: 12.5px; font-weight: 600; color: #92400e;">Sistem Informasi Akademik</span>
                                <span style="font-size: 12px; font-weight: 500; color: #64748b;">Institut Informatika &amp; Komputer</span>
                            </div>
                        </div>

                        <!-- Title + subtitle -->
                        <h2 class="dosen-wali-login-title">Login Dosen Wali</h2>
                        <p class="dosen-wali-login-subtitle">Masuk dengan akun dosen wali untuk mengakses<br>layanan bimbingan akademik dan konseling.</p>

                        <!-- Dosen Wali Login Form -->
                        <form id="authLoginFormDosenWali" onsubmit="handleDosenWaliLoginSubmit(event)">
                            <input type="hidden" id="selectedRoleInputDosenWali" name="role" value="dosen_wali">

                            <!-- Username/NIDN -->
                            <div class="dosen-wali-input-wrapper">
                                <svg class="dosen-wali-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input type="text" id="usernameInputDosenWali" class="dosen-wali-form-input" placeholder="username/Nidn" required autocomplete="username">
                            </div>

                            <!-- Password -->
                            <div class="dosen-wali-input-wrapper">
                                <svg class="dosen-wali-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <input type="password" id="passwordInputDosenWali" class="dosen-wali-form-input" placeholder="Password" required autocomplete="current-password">
                                <button type="button" class="dosen-wali-toggle-pass" onclick="togglePasswordVisibility('passwordInputDosenWali', 'eyeIconOpenDosenWali', 'eyeIconClosedDosenWali')" aria-label="Lihat kata sandi">
                                    <svg id="eyeIconOpenDosenWali" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg id="eyeIconClosedDosenWali" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="dosen-wali-login-btn" id="loginSubmitBtnDosenWali">
                                <span id="submitBtnTextDosenWali">Login</span>
                            </button>

                            <!-- Google Login -->
                            @if(!empty($googleConfigured))
                            <a href="{{ route('google.redirect') }}" class="dosen-wali-google-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </a>
                            @else
                            <button type="button" class="dosen-wali-google-btn" disabled style="opacity:0.55; cursor:not-allowed;">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </button>
                            @endif

                            <!-- Lupa Password -->
                            <button type="button" class="dosen-wali-forgot-link" onclick="navigateView('forgot')">Lupa Password?</button>

                            <!-- Demo Fill -->
                            <button type="button" class="demo-fill-btn" style="margin-top:10px;" onclick="fillDemoCredentialsDosenWali()">
                                <span>&#9889; Klik untuk Isi Kredensial Demo Cepat</span>
                            </button>
                        </form>
                    </div>

                    <!-- ============================================ -->
                    <!-- BENDAHARA LOGIN SECTION (TAMPILAN BARU)     -->
                    <!-- ============================================ -->
                    <div id="bendaharaLoginSection" style="display:none;">

                        <!-- Back button -->
                        <button type="button" class="bendahara-back-nav" onclick="navigateView('roles')">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                            Kembali ke halaman utama
                        </button>

                        <!-- Logo + Brand -->
                        <div class="brand-header" style="margin-bottom: 24px; cursor: default;">
                            <svg class="brand-logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M50 4L63 20H37L50 4Z" fill="#D99B16"/>
                                <path d="M50 14L59 27H41L50 14Z" fill="#F5C036"/>
                                <circle cx="50" cy="22" r="3.5" fill="#D99B16"/>
                                <path d="M12 30C12 30 25 38 50 38C75 38 88 30 88 30C88 30 92 68 50 94C8 68 12 30 12 30Z" fill="#0C2548"/>
                                <path d="M22 40C22 40 32 44 47 45V78C32 77 22 72 22 72V40Z" fill="#FFFFFF"/>
                                <path d="M25 43C25 43 33 46 45 47V75C33 74 25 70 25 70V43Z" fill="#143B6F"/>
                                <path d="M78 40C78 40 68 44 53 45V78C68 77 78 72 78 72V40Z" fill="#FFFFFF"/>
                                <path d="M75 43C75 43 67 46 55 47V75C67 74 75 70 75 70V43Z" fill="#143B6F"/>
                                <circle cx="50" cy="51" r="3.5" fill="#F5C036"/>
                                <path d="M48 57H52V69H48V57Z" fill="#F5C036"/>
                                <path d="M47 70H53V72H47V70Z" fill="#F5C036"/>
                                <path d="M30 76C36 78 44 79 50 79C56 79 64 78 70 76C62 82 50 86 50 86C50 86 38 82 30 76Z" fill="#D99B16"/>
                            </svg>
                            <div class="brand-text-col">
                                <span style="font-size: 22px; font-weight: 800; color: #0C2548; letter-spacing: -0.3px;">SI-AKAD</span>
                                <span style="font-size: 12.5px; font-weight: 600; color: #d97706;">Sistem Informasi Akademik</span>
                                <span style="font-size: 12px; font-weight: 500; color: #64748b;">Institut Informatika &amp; Komputer</span>
                            </div>
                        </div>

                        <!-- Title + subtitle -->
                        <h2 class="bendahara-login-title">Login Bendahara</h2>
                        <p class="bendahara-login-subtitle">Masuk dengan akun bendahara untuk mengelola<br>transaksi keuangan</p>

                        <!-- Bendahara Login Form -->
                        <form id="authLoginFormBendahara" onsubmit="handleBendaharaLoginSubmit(event)">
                            <input type="hidden" id="selectedRoleInputBendahara" name="role" value="bendahara">

                            <!-- Username/NIP -->
                            <div class="bendahara-input-wrapper">
                                <svg class="bendahara-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input type="text" id="usernameInputBendahara" class="bendahara-form-input" placeholder="Username/NIP" required autocomplete="username">
                            </div>

                            <!-- Password -->
                            <div class="bendahara-input-wrapper">
                                <svg class="bendahara-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <input type="password" id="passwordInputBendahara" class="bendahara-form-input" placeholder="Password" required autocomplete="current-password">
                                <button type="button" class="bendahara-toggle-pass" onclick="togglePasswordVisibility('passwordInputBendahara', 'eyeIconOpenBendahara', 'eyeIconClosedBendahara')" aria-label="Lihat kata sandi">
                                    <svg id="eyeIconOpenBendahara" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg id="eyeIconClosedBendahara" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="bendahara-login-btn" id="loginSubmitBtnBendahara">
                                <span id="submitBtnTextBendahara">Login</span>
                            </button>

                            <!-- Google Login -->
                            @if(!empty($googleConfigured))
                            <a href="{{ route('google.redirect') }}" class="bendahara-google-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </a>
                            @else
                            <button type="button" class="bendahara-google-btn" disabled style="opacity:0.55; cursor:not-allowed;">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Login With Google</span>
                            </button>
                            @endif

                            <!-- Lupa Password -->
                            <button type="button" class="bendahara-forgot-link" onclick="navigateView('forgot')">Lupa Password?</button>

                            <!-- Demo Fill -->
                            <button type="button" class="demo-fill-btn" style="margin-top:10px;" onclick="fillDemoCredentialsBendahara()">
                                <span>&#9889; Klik untuk Isi Kredensial Demo Cepat</span>
                            </button>
                        </form>
                    </div>

                </div>

                <!-- SUB-VIEW 3: LUPA PASSWORD VIEW (MATCHES SCREENSHOT 2) -->
                <div class="view-section" id="viewForgotPassword" style="margin-top: 24px;">
                    <!-- Back link matching screenshot 2 -->
                    <button type="button" class="back-nav-link" onclick="navigateView('login')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        Kembali ke form login
                    </button>

                    <h2 class="forgot-pass-heading">Lupa Password</h2>
                    <p class="forgot-pass-desc">
                        Masukan alamat email yang terdaftar pada akun Anda<br>
                        Kami akan mengirimkan kode OTP untuk mereset password
                    </p>

                    <!-- STEP A: INPUT EMAIL -->
                    <div id="forgotStepEmail">
                        <form onsubmit="handleSendOtp(event)">
                            <label class="forgot-input-label" for="forgotEmailInput">email terdaftar</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg>
                                <input type="email" id="forgotEmailInput" class="forgot-email-input" placeholder="nama@gmail.com" required autocomplete="email">
                            </div>

                            <button type="submit" class="btn-kirim-otp" id="btnSendOtp">
                                <span id="btnSendOtpText">kirim kode OTP</span>
                            </button>
                        </form>
                    </div>

                    <!-- STEP B: VERIFY OTP -->
                    <div id="forgotStepOtp" style="display: none;">
                        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 12px; margin-bottom: 16px; font-size: 13px; color: #1e40af;">
                            Kode OTP telah dikirimkan ke <strong id="targetEmailLabel"></strong>.
                            <div id="demoOtpAlert" style="margin-top: 4px; font-weight: 700; color: #0369a1;"></div>
                        </div>

                        <form onsubmit="handleVerifyOtp(event)">
                            <label class="forgot-input-label">Masukkan 6 Digit Kode OTP</label>
                            <div class="otp-inputs-row">
                                <input type="text" maxlength="1" class="otp-digit-input" id="otp_1" oninput="handleOtpInput(this, 'otp_2')" autofocus>
                                <input type="text" maxlength="1" class="otp-digit-input" id="otp_2" oninput="handleOtpInput(this, 'otp_3')">
                                <input type="text" maxlength="1" class="otp-digit-input" id="otp_3" oninput="handleOtpInput(this, 'otp_4')">
                                <input type="text" maxlength="1" class="otp-digit-input" id="otp_4" oninput="handleOtpInput(this, 'otp_5')">
                                <input type="text" maxlength="1" class="otp-digit-input" id="otp_5" oninput="handleOtpInput(this, 'otp_6')">
                                <input type="text" maxlength="1" class="otp-digit-input" id="otp_6" oninput="handleOtpInput(this, null)">
                            </div>

                            <button type="submit" class="btn-kirim-otp" id="btnVerifyOtp">
                                <span id="btnVerifyOtpText">Verifikasi Kode OTP</span>
                            </button>

                            <button type="button" class="demo-fill-btn" style="margin-top: 12px;" onclick="resendOtp()">
                                <span>Kirim Ulang Kode OTP</span>
                            </button>
                        </form>
                    </div>

                    <!-- STEP C: CREATE NEW PASSWORD -->
                    <div id="forgotStepNewPass" style="display: none;">
                        <form onsubmit="handleResetNewPassword(event)">
                            <div class="form-group">
                                <label class="form-label" for="newPasswordInput">Kata Sandi Baru</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <input type="password" id="newPasswordInput" class="form-input" placeholder="Minimal 6 karakter" required minlength="6">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="confirmPasswordInput">Konfirmasi Kata Sandi Baru</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <input type="password" id="confirmPasswordInput" class="form-input" placeholder="Ulangi kata sandi baru" required minlength="6">
                                </div>
                            </div>

                            <button type="submit" class="btn-kirim-otp" id="btnResetPass">
                                <span id="btnResetPassText">Simpan Password Baru</span>
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>

        <!-- RIGHT PANEL: CAMPUS ARCHITECTURE (DEFAULT FOR ROLES) -->
        <div class="right-panel" id="mainRightPanel">
            <div class="right-content">
                <div class="hero-badge">SI-AKAD</div>

                <h2 class="hero-headline">
                    <span class="text-white">Satu Portal,</span>
                    <span class="text-cyan">Semua Kebutuhan</span>
                    <span class="text-white">Akademik</span>
                </h2>

                <p class="hero-subtext">
                    Akses layanan akademik dengan mudah,<br>
                    cepat, dan terintegrasi dalam satu sistem.
                </p>
            </div>

            <!-- Signage Overlay on Campus Building Facade -->
            <div class="building-signage-overlay">
                <svg class="signage-logo" viewBox="0 0 100 100" fill="currentColor">
                    <path d="M50 8L60 22H40L50 8Z"/>
                    <path d="M15 32C15 32 26 38 50 38C74 38 85 32 85 32C85 32 89 65 50 90C11 65 15 32 15 32Z"/>
                    <path d="M25 42C25 42 33 45 47 46V72C33 71 25 67 25 67V42Z" fill="#0E2E5C"/>
                    <path d="M75 42C75 42 67 45 53 46V72C67 71 75 67 75 67V42Z" fill="#0E2E5C"/>
                </svg>
                <div class="signage-text">
                    Institut Informatika & Komputer
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: ADMIN LOGIN - SERVER + SHIELD ILLUSTRATION -->
        <div class="right-panel-admin-login" id="adminRightPanel">
            <div class="admin-right-content">
                <!-- Server + Shield SVG Illustration matching reference image -->
                <svg viewBox="0 0 420 400" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%; max-width:380px; position:relative; z-index:2;">

                    <!-- Watermark "SI" text -->
                    <text x="30" y="200" font-family="'Plus Jakarta Sans', sans-serif" font-size="130" font-weight="900"
                          fill="rgba(255,255,255,0.04)" letter-spacing="-5">SI</text>
                    <text x="50" y="340" font-family="'Plus Jakarta Sans', sans-serif" font-size="130" font-weight="900"
                          fill="rgba(255,255,255,0.04)" letter-spacing="-5">SI</text>

                    <!-- SERVER UNIT 1 (top) -->
                    <rect x="60" y="40" width="200" height="55" rx="8" fill="#1e3a7a" stroke="rgba(150,180,255,0.25)" stroke-width="1.5"/>
                    <circle cx="88" cy="67" r="5.5" fill="#4ade80"/>
                    <circle cx="106" cy="67" r="5.5" fill="#facc15"/>
                    <circle cx="124" cy="67" r="5.5" fill="#f87171" opacity="0.6"/>
                    <rect x="148" y="60" width="80" height="6" rx="3" fill="rgba(180,200,255,0.18)"/>
                    <rect x="148" y="72" width="55" height="5" rx="2.5" fill="rgba(180,200,255,0.12)"/>
                    <!-- Vent lines -->
                    <line x1="240" y1="52" x2="240" y2="82" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>
                    <line x1="245" y1="52" x2="245" y2="82" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>
                    <line x1="250" y1="52" x2="250" y2="82" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>

                    <!-- SERVER UNIT 2 (middle) -->
                    <rect x="60" y="112" width="200" height="55" rx="8" fill="#1e3a7a" stroke="rgba(150,180,255,0.25)" stroke-width="1.5"/>
                    <circle cx="88" cy="139" r="5.5" fill="#4ade80"/>
                    <circle cx="106" cy="139" r="5.5" fill="#facc15"/>
                    <rect x="130" y="132" width="95" height="6" rx="3" fill="rgba(180,200,255,0.18)"/>
                    <rect x="130" y="144" width="65" height="5" rx="2.5" fill="rgba(180,200,255,0.12)"/>
                    <line x1="240" y1="124" x2="240" y2="154" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>
                    <line x1="245" y1="124" x2="245" y2="154" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>
                    <line x1="250" y1="124" x2="250" y2="154" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>

                    <!-- SERVER UNIT 3 (bottom) -->
                    <rect x="60" y="184" width="200" height="55" rx="8" fill="#1e3a7a" stroke="rgba(150,180,255,0.25)" stroke-width="1.5"/>
                    <circle cx="88" cy="211" r="5.5" fill="#4ade80"/>
                    <circle cx="106" cy="211" r="5.5" fill="#f87171" opacity="0.6"/>
                    <rect x="130" y="204" width="75" height="6" rx="3" fill="rgba(180,200,255,0.18)"/>
                    <rect x="130" y="216" width="50" height="5" rx="2.5" fill="rgba(180,200,255,0.12)"/>
                    <line x1="240" y1="196" x2="240" y2="226" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>
                    <line x1="245" y1="196" x2="245" y2="226" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>
                    <line x1="250" y1="196" x2="250" y2="226" stroke="rgba(180,200,255,0.15)" stroke-width="1"/>

                    <!-- Server base / stand -->
                    <rect x="130" y="239" width="60" height="20" rx="4" fill="#1a3370" stroke="rgba(150,180,255,0.2)" stroke-width="1"/>
                    <rect x="100" y="258" width="120" height="10" rx="3" fill="#172d62"/>

                    <!-- Cable from server to ground -->
                    <path d="M160 268 Q160 300 200 310" stroke="rgba(100,150,255,0.3)" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <circle cx="200" cy="312" r="5" fill="rgba(100,150,255,0.35)"/>

                    <!-- SHIELD (right side, overlapping server) -->
                    <path d="M270 80 L370 80 L370 210 Q370 280 320 310 Q270 280 270 210 Z"
                          fill="#1e4fab" stroke="rgba(150,190,255,0.3)" stroke-width="2"/>
                    <!-- Shield inner highlight -->
                    <path d="M285 100 L355 100 L355 205 Q355 260 320 285 Q285 260 285 205 Z"
                          fill="rgba(30,80,180,0.5)" />
                    <!-- Shield center emblem: lock icon -->
                    <rect x="303" y="175" width="34" height="28" rx="5" fill="rgba(255,255,255,0.18)" stroke="rgba(255,255,255,0.35)" stroke-width="1.5"/>
                    <path d="M310 175 L310 165 Q310 152 320 152 Q330 152 330 165 L330 175" stroke="rgba(255,255,255,0.5)" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <circle cx="320" cy="187" r="4" fill="rgba(255,255,255,0.6)"/>
                    <rect x="318" y="190" width="4" height="7" rx="1" fill="rgba(255,255,255,0.6)"/>

                    <!-- Connecting dots (decorative) -->
                    <circle cx="62" cy="290" r="4" fill="rgba(100,160,255,0.4)"/>
                    <circle cx="80" cy="310" r="3" fill="rgba(100,160,255,0.3)"/>
                    <circle cx="50" cy="320" r="2.5" fill="rgba(100,160,255,0.25)"/>
                    <!-- horizontal line from server bottom -->
                    <line x1="60" y1="290" x2="200" y2="290" stroke="rgba(80,130,220,0.2)" stroke-width="2"/>

                </svg>
            </div>
        </div>

        <!-- RIGHT PANEL: MAHASISWA LOGIN - EDUCATION NETWORK ILLUSTRATION -->
        <div class="right-panel-mahasiswa-login" id="mahasiswaRightPanel">
            <div class="mahasiswa-right-content">
                <!-- Education Network SVG Illustration matching reference image -->
                <svg viewBox="0 0 420 420" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%; max-width:380px; position:relative; z-index:2;">

                    <!-- Watermark "SI-AKAD" text -->
                    <text x="210" y="240" font-family="'Plus Jakarta Sans', sans-serif" font-size="54" font-weight="900"
                          fill="rgba(255,255,255,0.05)" text-anchor="middle" letter-spacing="2">SI-AKAD</text>

                    <!-- ===== NETWORK CONNECTION LINES ===== -->
                    <!-- Center to top-left hexagon -->
                    <line x1="210" y1="210" x2="110" y2="115" stroke="rgba(134,239,172,0.35)" stroke-width="1.8"/>
                    <!-- Center to top-right (book circle) -->
                    <line x1="210" y1="210" x2="325" y2="110" stroke="rgba(134,239,172,0.35)" stroke-width="1.8"/>
                    <!-- Center to right (graduation cap) -->
                    <line x1="210" y1="210" x2="345" y2="265" stroke="rgba(134,239,172,0.35)" stroke-width="1.8"/>
                    <!-- Center to bottom-right (globe) -->
                    <line x1="210" y1="210" x2="310" y2="340" stroke="rgba(134,239,172,0.35)" stroke-width="1.8"/>
                    <!-- Center to bottom-left (circle) -->
                    <line x1="210" y1="210" x2="105" y2="305" stroke="rgba(134,239,172,0.35)" stroke-width="1.8"/>
                    <!-- Center to left (small circle) -->
                    <line x1="210" y1="210" x2="70" y2="215" stroke="rgba(134,239,172,0.35)" stroke-width="1.8"/>
                    <!-- Extra branch lines -->
                    <line x1="110" y1="115" x2="60" y2="80" stroke="rgba(134,239,172,0.2)" stroke-width="1.2"/>
                    <line x1="325" y1="110" x2="375" y2="75" stroke="rgba(134,239,172,0.2)" stroke-width="1.2"/>
                    <line x1="345" y1="265" x2="395" y2="250" stroke="rgba(134,239,172,0.2)" stroke-width="1.2"/>

                    <!-- ===== NODE DOTS on lines ===== -->
                    <circle cx="160" cy="162" r="4" fill="rgba(134,239,172,0.5)"/>
                    <circle cx="267" cy="158" r="3.5" fill="rgba(134,239,172,0.5)"/>
                    <circle cx="158" cy="257" r="3.5" fill="rgba(134,239,172,0.5)"/>
                    <circle cx="260" cy="275" r="3" fill="rgba(134,239,172,0.4)"/>

                    <!-- ===== CENTER: LARGE CIRCLE WITH OPEN BOOK ===== -->
                    <circle cx="210" cy="210" r="52" fill="#1a5c38" stroke="rgba(134,239,172,0.4)" stroke-width="2"/>
                    <!-- Open book icon -->
                    <g transform="translate(185, 192)">
                        <!-- Left page -->
                        <path d="M25 8 Q13 8 5 12 L5 38 Q13 34 25 34 Z" fill="rgba(255,255,255,0.85)" stroke="rgba(255,255,255,0.4)" stroke-width="0.8"/>
                        <!-- Right page -->
                        <path d="M25 8 Q37 8 45 12 L45 38 Q37 34 25 34 Z" fill="rgba(255,255,255,0.85)" stroke="rgba(255,255,255,0.4)" stroke-width="0.8"/>
                        <!-- Spine -->
                        <line x1="25" y1="8" x2="25" y2="34" stroke="rgba(134,239,172,0.6)" stroke-width="1.5"/>
                        <!-- Lines on left page -->
                        <line x1="10" y1="18" x2="22" y2="16" stroke="rgba(22,101,52,0.4)" stroke-width="1.2"/>
                        <line x1="10" y1="23" x2="22" y2="21" stroke="rgba(22,101,52,0.4)" stroke-width="1.2"/>
                        <line x1="10" y1="28" x2="22" y2="26" stroke="rgba(22,101,52,0.4)" stroke-width="1.2"/>
                        <!-- Lines on right page -->
                        <line x1="28" y1="16" x2="40" y2="18" stroke="rgba(22,101,52,0.4)" stroke-width="1.2"/>
                        <line x1="28" y1="21" x2="40" y2="23" stroke="rgba(22,101,52,0.4)" stroke-width="1.2"/>
                        <line x1="28" y1="26" x2="40" y2="28" stroke="rgba(22,101,52,0.4)" stroke-width="1.2"/>
                    </g>

                    <!-- ===== TOP-LEFT: HEXAGON ===== -->
                    <polygon points="110,80 132,68 154,80 154,104 132,116 110,104"
                             fill="#1d6b42" stroke="rgba(134,239,172,0.45)" stroke-width="2"/>
                    <!-- Small square inside hexagon -->
                    <rect x="122" y="82" width="20" height="20" rx="3" fill="rgba(255,255,255,0.15)" stroke="rgba(134,239,172,0.4)" stroke-width="1.5"/>
                    <!-- Extra tiny decorative square near hexagon -->
                    <rect x="75" y="58" width="12" height="12" rx="2" fill="rgba(134,239,172,0.2)" stroke="rgba(134,239,172,0.3)" stroke-width="1"/>

                    <!-- ===== TOP-RIGHT: CIRCLE WITH BOOK (smaller) ===== -->
                    <circle cx="325" cy="100" r="38" fill="#1d6b42" stroke="rgba(134,239,172,0.45)" stroke-width="2"/>
                    <!-- Book icon small -->
                    <g transform="translate(307, 85)">
                        <path d="M18 5 Q10 5 4 8 L4 28 Q10 26 18 26 Z" fill="rgba(255,255,255,0.85)"/>
                        <path d="M18 5 Q26 5 32 8 L32 28 Q26 26 18 26 Z" fill="rgba(255,255,255,0.85)"/>
                        <line x1="18" y1="5" x2="18" y2="26" stroke="rgba(134,239,172,0.6)" stroke-width="1.2"/>
                    </g>
                    <!-- Decorative square near top-right -->
                    <rect x="370" y="62" width="14" height="14" rx="2" fill="rgba(134,239,172,0.2)" stroke="rgba(134,239,172,0.3)" stroke-width="1"/>

                    <!-- ===== RIGHT: CIRCLE WITH GRADUATION CAP ===== -->
                    <circle cx="355" cy="268" r="36" fill="#1d6b42" stroke="rgba(134,239,172,0.45)" stroke-width="2"/>
                    <!-- Graduation cap icon -->
                    <g transform="translate(335, 252)">
                        <!-- Board -->
                        <ellipse cx="20" cy="10" rx="20" ry="5" fill="rgba(255,255,255,0.85)"/>
                        <!-- Cap top -->
                        <rect x="12" y="10" width="16" height="12" rx="2" fill="rgba(255,255,255,0.85)"/>
                        <!-- Tassel -->
                        <line x1="36" y1="10" x2="40" y2="20" stroke="rgba(134,239,172,0.8)" stroke-width="1.5"/>
                        <circle cx="40" cy="21" r="2" fill="rgba(134,239,172,0.8)"/>
                    </g>

                    <!-- ===== BOTTOM-RIGHT: CIRCLE WITH GLOBE ===== -->
                    <circle cx="310" cy="345" r="34" fill="#1d6b42" stroke="rgba(134,239,172,0.45)" stroke-width="2"/>
                    <!-- Globe icon -->
                    <g transform="translate(290, 325)">
                        <circle cx="20" cy="20" r="16" stroke="rgba(255,255,255,0.8)" stroke-width="1.8" fill="none"/>
                        <!-- Latitude lines -->
                        <ellipse cx="20" cy="20" rx="8" ry="16" stroke="rgba(255,255,255,0.6)" stroke-width="1.2" fill="none"/>
                        <line x1="4" y1="20" x2="36" y2="20" stroke="rgba(255,255,255,0.6)" stroke-width="1.2"/>
                        <line x1="6" y1="13" x2="34" y2="13" stroke="rgba(255,255,255,0.4)" stroke-width="1"/>
                        <line x1="6" y1="27" x2="34" y2="27" stroke="rgba(255,255,255,0.4)" stroke-width="1"/>
                    </g>

                    <!-- ===== BOTTOM-LEFT: EMPTY CIRCLE (decorative) ===== -->
                    <circle cx="105" cy="310" r="30" fill="none" stroke="rgba(134,239,172,0.4)" stroke-width="2"/>
                    <circle cx="105" cy="310" r="18" fill="rgba(29,107,66,0.5)" stroke="rgba(134,239,172,0.3)" stroke-width="1.5"/>

                    <!-- ===== LEFT: SMALL FILLED CIRCLE ===== -->
                    <circle cx="68" cy="215" r="16" fill="#1d6b42" stroke="rgba(134,239,172,0.45)" stroke-width="2"/>
                    <circle cx="68" cy="215" r="7" fill="rgba(255,255,255,0.2)"/>

                    <!-- ===== SMALL DECORATIVE DOTS ===== -->
                    <circle cx="145" cy="70" r="5" fill="rgba(134,239,172,0.3)"/>
                    <circle cx="390" cy="150" r="5" fill="rgba(134,239,172,0.3)"/>
                    <circle cx="55" cy="160" r="4" fill="rgba(134,239,172,0.25)"/>
                    <circle cx="370" cy="370" r="4" fill="rgba(134,239,172,0.25)"/>

                </svg>
            </div>
        </div>

        <!-- RIGHT PANEL: DOSEN PENGAJAR LOGIN - TEACHING ILLUSTRATION -->
        <div class="right-panel-dosen-login" id="dosenPengajarRightPanel">
            <div class="dosen-right-content">
                <svg viewBox="0 0 440 420" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%; max-width:400px; position:relative; z-index:2;">

                    <!-- Watermark "SI-AKAD" text -->
                    <text x="220" y="245" font-family="'Plus Jakarta Sans', sans-serif" font-size="50" font-weight="900"
                          fill="rgba(255,255,255,0.06)" text-anchor="middle" letter-spacing="2">SI-AKAD</text>

                    <!-- ===== OPEN BOOK (bottom-left) ===== -->
                    <g transform="translate(20, 270)">
                        <!-- Left page -->
                        <path d="M60 10 Q40 5 10 8 L10 80 Q40 77 60 82 Z" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.08)" stroke-linejoin="round"/>
                        <!-- Right page -->
                        <path d="M60 10 Q80 5 110 8 L110 80 Q80 77 60 82 Z" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.08)" stroke-linejoin="round"/>
                        <!-- Spine -->
                        <line x1="60" y1="10" x2="60" y2="82" stroke="#d4af7a" stroke-width="2.5"/>
                        <!-- Lines on pages -->
                        <line x1="18" y1="28" x2="54" y2="26" stroke="#d4af7a" stroke-width="1.5" opacity="0.6"/>
                        <line x1="18" y1="38" x2="54" y2="36" stroke="#d4af7a" stroke-width="1.5" opacity="0.6"/>
                        <line x1="18" y1="48" x2="54" y2="46" stroke="#d4af7a" stroke-width="1.5" opacity="0.6"/>
                        <line x1="18" y1="58" x2="54" y2="56" stroke="#d4af7a" stroke-width="1.5" opacity="0.6"/>
                        <line x1="66" y1="26" x2="102" y2="28" stroke="#d4af7a" stroke-width="1.5" opacity="0.6"/>
                        <line x1="66" y1="36" x2="102" y2="38" stroke="#d4af7a" stroke-width="1.5" opacity="0.6"/>
                        <line x1="66" y1="46" x2="102" y2="48" stroke="#d4af7a" stroke-width="1.5" opacity="0.6"/>
                    </g>

                    <!-- ===== WHITEBOARD WITH CHART (top-left) ===== -->
                    <g transform="translate(30, 30)">
                        <!-- Board frame -->
                        <rect x="0" y="15" width="130" height="90" rx="4" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.06)"/>
                        <!-- Board stand legs -->
                        <line x1="65" y1="105" x2="50" y2="125" stroke="#d4af7a" stroke-width="2"/>
                        <line x1="65" y1="105" x2="80" y2="125" stroke="#d4af7a" stroke-width="2"/>
                        <!-- Top bar -->
                        <rect x="0" y="10" width="130" height="8" rx="2" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.12)"/>
                        <!-- Chart line going up -->
                        <polyline points="15,85 35,70 55,75 75,50 95,55 115,30" stroke="#f5d78e" stroke-width="2.5" fill="none" stroke-linejoin="round" stroke-linecap="round"/>
                        <!-- Chart axes -->
                        <line x1="12" y1="25" x2="12" y2="90" stroke="#d4af7a" stroke-width="1.5" opacity="0.7"/>
                        <line x1="12" y1="90" x2="118" y2="90" stroke="#d4af7a" stroke-width="1.5" opacity="0.7"/>
                        <!-- Arrow on chart line -->
                        <polygon points="113,26 120,32 107,32" fill="#f5d78e" opacity="0.9"/>
                    </g>

                    <!-- ===== LAPTOP / MONITOR (top-right) ===== -->
                    <g transform="translate(265, 20)">
                        <!-- Monitor frame -->
                        <rect x="0" y="0" width="140" height="95" rx="6" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.06)"/>
                        <!-- Screen bezel -->
                        <rect x="8" y="8" width="124" height="74" rx="3" stroke="#d4af7a" stroke-width="1" fill="rgba(212,175,122,0.04)" opacity="0.7"/>
                        <!-- Monitor stand -->
                        <line x1="70" y1="95" x2="70" y2="115" stroke="#d4af7a" stroke-width="2.5"/>
                        <line x1="50" y1="115" x2="90" y2="115" stroke="#d4af7a" stroke-width="2.5" stroke-linecap="round"/>
                        <!-- Math formula A + x^2 on screen -->
                        <text x="25" y="52" font-family="serif" font-size="28" font-weight="bold" fill="#f5d78e" opacity="0.9">A+x</text>
                        <text x="99" y="40" font-family="serif" font-size="16" font-weight="bold" fill="#f5d78e" opacity="0.9">2</text>
                    </g>

                    <!-- ===== DOSEN FIGURE AT PODIUM (center) ===== -->
                    <g transform="translate(165, 140)">
                        <!-- Podium -->
                        <path d="M10 95 L50 60 L90 95 L95 130 L5 130 Z" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.1)" stroke-linejoin="round"/>
                        <!-- Podium top surface -->
                        <rect x="30" y="56" width="40" height="8" rx="2" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.1)"/>
                        <!-- Body / suit -->
                        <path d="M50 5 L50 55" stroke="#d4af7a" stroke-width="1" fill="none" opacity="0.3"/>
                        <!-- Torso -->
                        <path d="M35 30 L35 58 L50 52 L65 58 L65 30 Q58 22 50 20 Q42 22 35 30Z" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.1)"/>
                        <!-- Suit lapels -->
                        <path d="M50 25 L42 38" stroke="#d4af7a" stroke-width="1.5" opacity="0.8"/>
                        <path d="M50 25 L58 38" stroke="#d4af7a" stroke-width="1.5" opacity="0.8"/>
                        <!-- Tie -->
                        <path d="M50 28 L48 45 L50 48 L52 45 Z" stroke="#f5d78e" stroke-width="1" fill="rgba(245,215,142,0.2)"/>
                        <!-- Head -->
                        <circle cx="50" cy="10" r="12" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.1)"/>
                        <!-- Arms -->
                        <path d="M35 35 L18 48" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <path d="M65 35 L82 44" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <!-- Pointer (right hand) -->
                        <line x1="82" y1="44" x2="100" y2="36" stroke="#d4af7a" stroke-width="1.8" stroke-linecap="round"/>
                        <circle cx="100" cy="35" r="2.5" fill="#f5d78e"/>
                    </g>

                    <!-- ===== OPEN BOOK (top-center) ===== -->
                    <g transform="translate(175, 30)">
                        <path d="M40 5 Q25 3 5 6 L5 55 Q25 52 40 56 Z" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.08)"/>
                        <path d="M40 5 Q55 3 75 6 L75 55 Q55 52 40 56 Z" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.08)"/>
                        <line x1="40" y1="5" x2="40" y2="56" stroke="#d4af7a" stroke-width="2"/>
                        <line x1="10" y1="18" x2="36" y2="16" stroke="#d4af7a" stroke-width="1.2" opacity="0.5"/>
                        <line x1="10" y1="26" x2="36" y2="24" stroke="#d4af7a" stroke-width="1.2" opacity="0.5"/>
                        <line x1="10" y1="34" x2="36" y2="32" stroke="#d4af7a" stroke-width="1.2" opacity="0.5"/>
                        <line x1="44" y1="16" x2="70" y2="18" stroke="#d4af7a" stroke-width="1.2" opacity="0.5"/>
                        <line x1="44" y1="24" x2="70" y2="26" stroke="#d4af7a" stroke-width="1.2" opacity="0.5"/>
                        <line x1="44" y1="32" x2="70" y2="34" stroke="#d4af7a" stroke-width="1.2" opacity="0.5"/>
                    </g>

                    <!-- ===== GRADUATION CAP (bottom-right) ===== -->
                    <g transform="translate(310, 290)">
                        <ellipse cx="50" cy="20" rx="50" ry="12" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.1)"/>
                        <rect x="30" y="20" width="40" height="30" rx="3" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.08)"/>
                        <!-- Tassel -->
                        <line x1="95" y1="20" x2="100" y2="48" stroke="#d4af7a" stroke-width="2"/>
                        <path d="M98 48 L100 58 L102 48" stroke="#d4af7a" stroke-width="1.5" fill="none"/>
                        <circle cx="100" cy="60" r="3" fill="#f5d78e"/>
                    </g>

                    <!-- ===== MOLECULE NETWORK (bottom-right corner) ===== -->
                    <g transform="translate(340, 330)">
                        <circle cx="20" cy="20" r="7" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.1)"/>
                        <circle cx="50" cy="10" r="5" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.1)"/>
                        <circle cx="55" cy="40" r="6" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.1)"/>
                        <circle cx="80" cy="25" r="4" stroke="#f5d78e" stroke-width="1.5" fill="rgba(245,215,142,0.15)"/>
                        <line x1="27" y1="20" x2="45" y2="12" stroke="#d4af7a" stroke-width="1.2"/>
                        <line x1="26" y1="24" x2="49" y2="37" stroke="#d4af7a" stroke-width="1.2"/>
                        <line x1="55" y1="15" x2="76" y2="23" stroke="#d4af7a" stroke-width="1.2"/>
                        <line x1="61" y1="38" x2="77" y2="28" stroke="#d4af7a" stroke-width="1.2"/>
                    </g>

                    <!-- Connecting line from whiteboard to dosen -->
                    <line x1="160" y1="100" x2="210" y2="155" stroke="#d4af7a" stroke-width="1" opacity="0.3" stroke-dasharray="4 4"/>
                    <!-- Connecting line from monitor to dosen -->
                    <line x1="280" y1="120" x2="240" y2="155" stroke="#d4af7a" stroke-width="1" opacity="0.3" stroke-dasharray="4 4"/>

                </svg>
            </div>
        </div>

        <!-- RIGHT PANEL: DOSEN WALI LOGIN - ACADEMIC COUNSELING ILLUSTRATION -->
        <div class="right-panel-dosen-wali" id="dosenWaliRightPanel">
            <div class="dosen-wali-right-content">
                <svg viewBox="0 0 440 420" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%; max-width:400px; position:relative; z-index:2;">

                    <!-- Watermark "SI-AKAD" -->
                    <text x="220" y="255" font-family="'Plus Jakarta Sans', sans-serif" font-size="52" font-weight="900"
                          fill="rgba(255,255,255,0.06)" text-anchor="middle" letter-spacing="2">SI-AKAD</text>

                    <!-- ===== LARGE OUTER CIRCLE (counseling context) ===== -->
                    <circle cx="220" cy="210" r="165" stroke="rgba(220,180,120,0.25)" stroke-width="1.5" fill="none" stroke-dasharray="6 5"/>
                    <circle cx="220" cy="210" r="140" stroke="rgba(220,180,120,0.18)" stroke-width="1" fill="none"/>

                    <!-- ===== PERSON 1 (Dosen Wali - left, sitting) ===== -->
                    <g transform="translate(95, 175)">
                        <!-- Chair -->
                        <rect x="5" y="65" width="55" height="5" rx="2" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.08)"/>
                        <line x1="10" y1="70" x2="10" y2="95" stroke="#d4af7a" stroke-width="1.5"/>
                        <line x1="55" y1="70" x2="55" y2="95" stroke="#d4af7a" stroke-width="1.5"/>
                        <rect x="0" y="38" width="8" height="35" rx="3" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.08)"/>
                        <!-- Body -->
                        <path d="M20 38 L20 65 L45 65 L45 38 Q38 28 32 26 Q26 28 20 38Z" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.1)"/>
                        <!-- Lapels -->
                        <path d="M32 30 L26 42" stroke="#d4af7a" stroke-width="1.5" opacity="0.8"/>
                        <path d="M32 30 L38 42" stroke="#d4af7a" stroke-width="1.5" opacity="0.8"/>
                        <!-- Head -->
                        <circle cx="32" cy="14" r="13" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.1)"/>
                        <!-- Arms - extended forward for handshake -->
                        <path d="M45 42 L68 55" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <!-- Legs/seated -->
                        <path d="M22 65 L18 90" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <path d="M43 65 L47 90" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                    </g>

                    <!-- ===== PERSON 2 (Mahasiswa - right, sitting) ===== -->
                    <g transform="translate(270, 175)">
                        <!-- Chair -->
                        <rect x="10" y="65" width="55" height="5" rx="2" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.08)"/>
                        <line x1="15" y1="70" x2="15" y2="95" stroke="#d4af7a" stroke-width="1.5"/>
                        <line x1="60" y1="70" x2="60" y2="95" stroke="#d4af7a" stroke-width="1.5"/>
                        <rect x="62" y="38" width="8" height="35" rx="3" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.08)"/>
                        <!-- Body -->
                        <path d="M20 38 L20 65 L45 65 L45 38 Q38 28 32 26 Q26 28 20 38Z" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.1)"/>
                        <!-- Head -->
                        <circle cx="32" cy="14" r="13" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.1)"/>
                        <!-- Hair (female style indicator) -->
                        <path d="M20 10 Q19 2 32 1 Q45 2 44 10" stroke="#d4af7a" stroke-width="1.5" fill="none"/>
                        <!-- Arms - extended for handshake -->
                        <path d="M20 42 L-4 55" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <!-- Legs/seated -->
                        <path d="M22 65 L18 90" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <path d="M43 65 L47 90" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                    </g>

                    <!-- ===== CENTER HANDSHAKE ===== -->
                    <g transform="translate(195, 225)">
                        <!-- Left hand -->
                        <path d="M0 10 L18 0 L22 5 L8 15Z" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.15)"/>
                        <!-- Right hand -->
                        <path d="M50 10 L32 0 L28 5 L42 15Z" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.15)"/>
                        <!-- Clasped hands -->
                        <ellipse cx="25" cy="10" rx="12" ry="8" stroke="#f5d78e" stroke-width="2" fill="rgba(245,215,142,0.1)"/>
                    </g>

                    <!-- ===== TOP CENTER: OPEN BOOK IN SPEECH BUBBLE ===== -->
                    <g transform="translate(168, 30)">
                        <!-- Speech bubble -->
                        <rect x="0" y="0" width="104" height="72" rx="12" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.08)"/>
                        <!-- Bubble tail -->
                        <path d="M45 72 L38 90 L60 72Z" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.08)"/>
                        <!-- Open book inside -->
                        <path d="M32 20 Q24 18 16 21 L16 52 Q24 49 32 52 Z" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.08)"/>
                        <path d="M32 20 Q40 18 52 21 L52 52 Q40 49 32 52 Z" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.08)"/>
                        <line x1="32" y1="20" x2="32" y2="52" stroke="#d4af7a" stroke-width="1.5"/>
                        <!-- Book lines -->
                        <line x1="18" y1="29" x2="30" y2="27" stroke="#d4af7a" stroke-width="1" opacity="0.6"/>
                        <line x1="18" y1="35" x2="30" y2="33" stroke="#d4af7a" stroke-width="1" opacity="0.6"/>
                        <line x1="18" y1="41" x2="30" y2="39" stroke="#d4af7a" stroke-width="1" opacity="0.6"/>
                        <line x1="34" y1="27" x2="50" y2="29" stroke="#d4af7a" stroke-width="1" opacity="0.6"/>
                        <line x1="34" y1="33" x2="50" y2="35" stroke="#d4af7a" stroke-width="1" opacity="0.6"/>
                        <line x1="34" y1="39" x2="50" y2="41" stroke="#d4af7a" stroke-width="1" opacity="0.6"/>
                        <!-- Chat dots -->
                        <circle cx="72" cy="15" r="3" fill="#f5d78e" opacity="0.7"/>
                        <circle cx="82" cy="15" r="3" fill="#f5d78e" opacity="0.7"/>
                        <circle cx="92" cy="15" r="3" fill="#f5d78e" opacity="0.7"/>
                    </g>

                    <!-- ===== TOP LEFT: KEY ===== -->
                    <g transform="translate(55, 55)">
                        <!-- Key head (circle) -->
                        <circle cx="20" cy="20" r="18" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.08)"/>
                        <circle cx="20" cy="20" r="10" stroke="#d4af7a" stroke-width="1.5" fill="none"/>
                        <!-- Key shaft -->
                        <line x1="34" y1="32" x2="62" y2="60" stroke="#d4af7a" stroke-width="2.5" stroke-linecap="round"/>
                        <!-- Key teeth -->
                        <line x1="50" y1="54" x2="44" y2="60" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <line x1="58" y1="62" x2="52" y2="68" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                    </g>

                    <!-- ===== TOP RIGHT: HANDSHAKE ===== -->
                    <g transform="translate(320, 60)">
                        <!-- Left arm -->
                        <path d="M0 20 L35 5" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <!-- Right arm -->
                        <path d="M75 20 L40 5" stroke="#d4af7a" stroke-width="2" stroke-linecap="round"/>
                        <!-- Clasped hands center -->
                        <ellipse cx="37" cy="5" rx="14" ry="9" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.12)"/>
                        <!-- Wrist lines -->
                        <line x1="26" y1="10" x2="22" y2="20" stroke="#d4af7a" stroke-width="1.5"/>
                        <line x1="48" y1="10" x2="52" y2="20" stroke="#d4af7a" stroke-width="1.5"/>
                    </g>

                    <!-- ===== BOTTOM CENTER: COMPASS ===== -->
                    <g transform="translate(185, 335)">
                        <!-- Compass outer ring -->
                        <circle cx="35" cy="35" r="32" stroke="#d4af7a" stroke-width="2" fill="rgba(212,175,122,0.08)"/>
                        <circle cx="35" cy="35" r="24" stroke="#d4af7a" stroke-width="1" fill="none" opacity="0.5"/>
                        <!-- Compass needle N -->
                        <path d="M35 12 L30 35 L35 30 L40 35 Z" stroke="#f5d78e" stroke-width="1.5" fill="rgba(245,215,142,0.3)"/>
                        <!-- Compass needle S -->
                        <path d="M35 58 L30 35 L35 40 L40 35 Z" stroke="#d4af7a" stroke-width="1.5" fill="rgba(212,175,122,0.2)"/>
                        <!-- Center dot -->
                        <circle cx="35" cy="35" r="3" fill="#f5d78e"/>
                        <!-- Cardinal points ticks -->
                        <line x1="35" y1="5" x2="35" y2="10" stroke="#d4af7a" stroke-width="1.5"/>
                        <line x1="35" y1="60" x2="35" y2="65" stroke="#d4af7a" stroke-width="1.5"/>
                        <line x1="5" y1="35" x2="10" y2="35" stroke="#d4af7a" stroke-width="1.5"/>
                        <line x1="60" y1="35" x2="65" y2="35" stroke="#d4af7a" stroke-width="1.5"/>
                    </g>

                    <!-- Decorative dots -->
                    <circle cx="155" cy="145" r="4" fill="rgba(212,175,122,0.4)"/>
                    <circle cx="285" cy="145" r="4" fill="rgba(212,175,122,0.4)"/>
                    <circle cx="100" cy="310" r="4" fill="rgba(212,175,122,0.3)"/>
                    <circle cx="340" cy="310" r="4" fill="rgba(212,175,122,0.3)"/>

                </svg>
            </div>
        </div>

        <!-- RIGHT PANEL: BENDAHARA LOGIN - FINANCE ILLUSTRATION -->
        <div class="right-panel-bendahara" id="bendaharaRightPanel">
            <div class="bendahara-right-content">
                <svg viewBox="0 0 440 420" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%; max-width:400px; position:relative; z-index:2;">

                    <!-- Watermark "SI-AKAD" -->
                    <text x="220" y="255" font-family="'Plus Jakarta Sans', sans-serif" font-size="50" font-weight="900"
                          fill="rgba(100,50,0,0.12)" text-anchor="middle" letter-spacing="2">SI-AKAD</text>

                    <!-- Circuit/connection lines background -->
                    <line x1="155" y1="80" x2="200" y2="80" stroke="rgba(100,50,0,0.3)" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <line x1="240" y1="80" x2="290" y2="80" stroke="rgba(100,50,0,0.3)" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <line x1="290" y1="80" x2="290" y2="130" stroke="rgba(100,50,0,0.3)" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <line x1="155" y1="80" x2="155" y2="290" stroke="rgba(100,50,0,0.3)" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <line x1="155" y1="290" x2="200" y2="290" stroke="rgba(100,50,0,0.3)" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <line x1="245" y1="290" x2="290" y2="290" stroke="rgba(100,50,0,0.3)" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <line x1="290" y1="290" x2="290" y2="220" stroke="rgba(100,50,0,0.3)" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <!-- Circuit dots -->
                    <circle cx="155" cy="185" r="5" fill="rgba(100,50,0,0.25)"/>
                    <circle cx="290" cy="185" r="5" fill="rgba(100,50,0,0.25)"/>
                    <circle cx="220" cy="80" r="5" fill="rgba(100,50,0,0.25)"/>
                    <circle cx="220" cy="290" r="5" fill="rgba(100,50,0,0.25)"/>

                    <!-- ===== CALCULATOR (top-left) ===== -->
                    <g transform="translate(60, 40)">
                        <!-- Body -->
                        <rect x="0" y="0" width="100" height="130" rx="8" stroke="rgba(80,40,0,0.8)" stroke-width="2.5" fill="rgba(255,255,255,0.08)"/>
                        <!-- Display -->
                        <rect x="8" y="8" width="84" height="28" rx="4" stroke="rgba(80,40,0,0.6)" stroke-width="1.5" fill="rgba(255,255,255,0.12)"/>
                        <!-- Display line -->
                        <line x1="60" y1="22" x2="86" y2="22" stroke="rgba(80,40,0,0.6)" stroke-width="1.5"/>
                        <!-- Button grid 3x4 -->
                        <!-- Row 1 -->
                        <rect x="10" y="44" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <rect x="39" y="44" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <rect x="68" y="44" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <!-- Row 2 -->
                        <rect x="10" y="66" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <rect x="39" y="66" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <rect x="68" y="66" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <!-- Row 3 -->
                        <rect x="10" y="88" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <rect x="39" y="88" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <rect x="68" y="88" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <!-- Row 4 -->
                        <rect x="10" y="110" width="22" height="16" rx="3" stroke="rgba(80,40,0,0.5)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                        <rect x="39" y="110" width="51" height="16" rx="3" stroke="rgba(80,40,0,0.65)" stroke-width="2" fill="rgba(255,255,255,0.12)"/>
                    </g>

                    <!-- ===== SHIELD WITH CHECKMARK (top-right) ===== -->
                    <g transform="translate(270, 35)">
                        <!-- Shield body -->
                        <path d="M55 5 L100 20 L100 60 C100 88 55 105 55 105 C55 105 10 88 10 60 L10 20 Z"
                              stroke="rgba(80,40,0,0.8)" stroke-width="2.5" fill="rgba(255,255,255,0.08)" stroke-linejoin="round"/>
                        <!-- Checkmark -->
                        <polyline points="30,58 47,75 82,38" stroke="rgba(80,40,0,0.9)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </g>

                    <!-- ===== PLUS SIGNS (connectors) ===== -->
                    <g transform="translate(205, 158)">
                        <line x1="12" y1="0" x2="12" y2="24" stroke="rgba(80,40,0,0.6)" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="0" y1="12" x2="24" y2="12" stroke="rgba(80,40,0,0.6)" stroke-width="2.5" stroke-linecap="round"/>
                    </g>
                    <g transform="translate(205, 255)">
                        <line x1="12" y1="0" x2="12" y2="24" stroke="rgba(80,40,0,0.6)" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="0" y1="12" x2="24" y2="12" stroke="rgba(80,40,0,0.6)" stroke-width="2.5" stroke-linecap="round"/>
                    </g>

                    <!-- ===== COIN STACK WITH $ (bottom-left) ===== -->
                    <g transform="translate(65, 220)">
                        <!-- Stack coins -->
                        <ellipse cx="50" cy="120" rx="45" ry="12" stroke="rgba(80,40,0,0.7)" stroke-width="2" fill="rgba(255,255,255,0.08)"/>
                        <rect x="5" y="100" width="90" height="20" stroke="rgba(80,40,0,0.7)" stroke-width="2" fill="rgba(255,255,255,0.08)"/>
                        <ellipse cx="50" cy="100" rx="45" ry="12" stroke="rgba(80,40,0,0.7)" stroke-width="2" fill="rgba(255,255,255,0.1)"/>
                        <rect x="5" y="80" width="90" height="20" stroke="rgba(80,40,0,0.7)" stroke-width="2" fill="rgba(255,255,255,0.08)"/>
                        <ellipse cx="50" cy="80" rx="45" ry="12" stroke="rgba(80,40,0,0.7)" stroke-width="2" fill="rgba(255,255,255,0.1)"/>
                        <rect x="5" y="60" width="90" height="20" stroke="rgba(80,40,0,0.7)" stroke-width="2" fill="rgba(255,255,255,0.08)"/>
                        <ellipse cx="50" cy="60" rx="45" ry="12" stroke="rgba(80,40,0,0.8)" stroke-width="2.5" fill="rgba(255,255,255,0.12)"/>
                        <!-- Dollar sign -->
                        <text x="35" y="68" font-family="serif" font-size="22" font-weight="bold" fill="rgba(80,40,0,0.8)">$</text>
                    </g>

                    <!-- ===== Rp COIN (bottom-center) ===== -->
                    <g transform="translate(175, 290)">
                        <circle cx="45" cy="45" r="42" stroke="rgba(80,40,0,0.8)" stroke-width="2.5" fill="rgba(255,255,255,0.1)"/>
                        <circle cx="45" cy="45" r="34" stroke="rgba(80,40,0,0.4)" stroke-width="1" fill="none"/>
                        <text x="19" y="54" font-family="'Plus Jakarta Sans', sans-serif" font-size="20" font-weight="800" fill="rgba(80,40,0,0.85)">Rp</text>
                    </g>

                    <!-- ===== OPEN BOOK WITH PENCIL (bottom-right) ===== -->
                    <g transform="translate(278, 218)">
                        <!-- Book body -->
                        <rect x="0" y="0" width="110" height="140" rx="6" stroke="rgba(80,40,0,0.7)" stroke-width="2" fill="rgba(255,255,255,0.08)"/>
                        <!-- Spine -->
                        <line x1="55" y1="0" x2="55" y2="140" stroke="rgba(80,40,0,0.5)" stroke-width="1.5"/>
                        <!-- Left page lines -->
                        <line x1="8" y1="20" x2="48" y2="20" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="8" y1="30" x2="48" y2="30" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="8" y1="40" x2="48" y2="40" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="8" y1="50" x2="48" y2="50" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="8" y1="60" x2="48" y2="60" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="8" y1="70" x2="48" y2="70" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="8" y1="80" x2="48" y2="80" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <!-- Right page lines -->
                        <line x1="62" y1="20" x2="102" y2="20" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="62" y1="30" x2="102" y2="30" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="62" y1="40" x2="102" y2="40" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="62" y1="50" x2="102" y2="50" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <line x1="62" y1="60" x2="102" y2="60" stroke="rgba(80,40,0,0.45)" stroke-width="1.2"/>
                        <!-- Pencil on top of book -->
                        <g transform="translate(60, -35) rotate(35)">
                            <rect x="0" y="0" width="12" height="70" rx="2" stroke="rgba(80,40,0,0.75)" stroke-width="1.5" fill="rgba(255,255,255,0.1)"/>
                            <polygon points="0,70 12,70 6,85" stroke="rgba(80,40,0,0.75)" stroke-width="1.5" fill="rgba(255,255,255,0.08)"/>
                            <rect x="0" y="0" width="12" height="10" rx="2" stroke="rgba(80,40,0,0.5)" stroke-width="1" fill="rgba(255,255,255,0.15)"/>
                        </g>
                    </g>

                </svg>
            </div>
        </div>

        <div class="right-panel-security" id="securityRightPanel">
            <!-- Custom Vector Illustration Matching Screenshot 2 -->
            <svg class="security-illustration-svg" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Large Watermark Text -->
                <text x="250" y="310" font-family="'Plus Jakarta Sans', sans-serif" font-size="78" font-weight="900" fill="rgba(255, 255, 255, 0.05)" text-anchor="middle" letter-spacing="4">SI-AKAD</text>

                <!-- Circular Circuit / Connection Lines -->
                <path d="M250 80C350 80 430 160 430 260C430 360 350 440 250 440C150 440 70 360 70 260" stroke="#d5c8a3" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="4 6" opacity="0.6"/>
                <path d="M250 50V90 M380 180H440 M120 180H60 M250 430V470" stroke="#d5c8a3" stroke-width="2" opacity="0.5"/>

                <!-- 1. TOP: RELOAD / REFRESH ARROWS -->
                <g transform="translate(90, 190)">
                    <path d="M45 10A35 35 0 1 1 10 45" stroke="#d5c8a3" stroke-width="4.5" stroke-linecap="round"/>
                    <polygon points="52,2 54,20 36,18" fill="#d5c8a3"/>
                </g>

                <!-- 2. CENTER-TOP: PADLOCK WITH KEY -->
                <g transform="translate(190, 140)">
                    <!-- Padlock Shackle -->
                    <path d="M35 50V30C35 16 46 5 60 5C74 5 85 16 85 30V50" stroke="#d5c8a3" stroke-width="7" stroke-linecap="round"/>
                    <!-- Padlock Body -->
                    <rect x="20" y="50" width="80" height="70" rx="14" stroke="#d5c8a3" stroke-width="7" fill="#10438c"/>
                    <!-- Keyhole -->
                    <circle cx="60" cy="80" r="7" fill="#d5c8a3"/>
                    <path d="M57 84L54 100H66L63 84Z" fill="#d5c8a3"/>

                    <!-- Attached Key -->
                    <path d="M85 80H125M110 80V92M120 80V92" stroke="#d5c8a3" stroke-width="5" stroke-linecap="round"/>
                </g>

                <!-- 3. TOP RIGHT: A+ BADGE CIRCLE -->
                <g transform="translate(370, 215)">
                    <circle cx="36" cy="36" r="36" stroke="#d5c8a3" stroke-width="4.5" fill="#10438c"/>
                    <text x="36" y="44" font-family="'Plus Jakarta Sans', sans-serif" font-size="24" font-weight="800" fill="#d5c8a3" text-anchor="middle">A+<tspan font-size="16" dy="-8">🎓</tspan></text>
                </g>

                <!-- 4. BOTTOM LEFT: ENVELOPE WITH LINK CHAIN -->
                <g transform="translate(85, 340)">
                    <rect x="0" y="0" width="100" height="72" rx="6" stroke="#d5c8a3" stroke-width="4.5" fill="#10438c"/>
                    <path d="M0 0L50 40L100 0" stroke="#d5c8a3" stroke-width="4.5"/>
                    <!-- Chain Link Icon inside envelope -->
                    <circle cx="42" cy="42" r="9" stroke="#d5c8a3" stroke-width="3" fill="none"/>
                    <circle cx="58" cy="42" r="9" stroke="#d5c8a3" stroke-width="3" fill="none"/>
                    <line x1="42" y1="42" x2="58" y2="42" stroke="#d5c8a3" stroke-width="3"/>
                </g>

                <!-- 5. BOTTOM CENTER: SHIELD WITH CHECKMARK -->
                <g transform="translate(205, 395)">
                    <path d="M45 0L85 15V45C85 70 45 90 45 90C45 90 5 70 5 45V15L45 0Z" stroke="#d5c8a3" stroke-width="4.5" fill="#10438c"/>
                    <path d="M28 42L40 54L62 30" stroke="#d5c8a3" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                </g>

                <!-- 6. BOTTOM RIGHT: OPEN DOOR WITH LIGHT CASTING -->
                <g transform="translate(375, 345)">
                    <!-- Door Frame -->
                    <rect x="0" y="0" width="85" height="140" stroke="#d5c8a3" stroke-width="4.5" fill="none"/>
                    <!-- Light Ray -->
                    <polygon points="0,0 70,10 70,130 0,140" fill="#fff9db"/>
                    <!-- Door Panel -->
                    <rect x="70" y="10" width="8" height="120" fill="#d5c8a3"/>
                    <circle cx="60" cy="72" r="3" fill="#10438c"/>
                </g>
            </svg>
        </div>

    </div>

    <!-- SUCCESS LOGGED IN MODAL -->
    <div class="modal-backdrop" id="successModal">
        <div class="modal-box">
            <div class="modal-success-icon">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h3 class="modal-title">Autentikasi Berhasil!</h3>
            <p class="modal-subtitle">Selamat datang di Sistem Informasi Akademik Institut Informatika & Komputer.</p>

            <div class="user-preview-card">
                <div class="user-avatar-circle" id="modalUserAvatar">AD</div>
                <div class="user-info-text">
                    <div class="user-name-title" id="modalUserName">Administrator Utama</div>
                    <div class="user-id-sub" id="modalUserId">ID: admin@siakad.ac.id • Role: Admin</div>
                </div>
            </div>

            <div class="modal-btn-group">
                <button type="button" class="modal-primary-btn" onclick="openSimulatedDashboard()">
                    Masuk ke Dashboard Akademik →
                </button>
                <button type="button" class="modal-secondary-btn" onclick="closeSuccessModal()">
                    Ganti Akun / Keluar
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div class="toast-notification" id="toastNotification">
        <svg id="toastIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.5">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span id="toastMessage">Pesan pemberitahuan</span>
    </div>

    <!-- JAVASCRIPT FOR FULL API INTEGRATION & VIEW LOGIC -->
    <script>
        const ROLES_CONFIG = {
            'admin': {
                name: 'Admin',
                badgeTitle: 'Administrator Sistem',
                bg: '#639cf6',
                textColor: '#000000',
                label: 'Username / NIP Administrator',
                placeholder: 'Contoh: admin@siakad.ac.id atau 198501012010121001',
                demoUser: 'admin@siakad.ac.id',
                demoPass: 'admin123',
                demoName: 'Drs. H. Hendra Wijaya, M.Kom',
                avatarInitials: 'HW',
                avatarBg: '#2563eb'
            },
            'mahasiswa': {
                name: 'mahasiswa',
                badgeTitle: 'Mahasiswa Aktif',
                bg: '#7ee06e',
                textColor: '#000000',
                label: 'NIM (Nomor Induk Mahasiswa)',
                placeholder: 'Contoh: 2201010048 atau mahasiswa123@gmail.com',
                demoUser: 'mahasiswa123@gmail.com',
                demoPass: 'password123',
                demoName: 'Mahasiswa',
                avatarInitials: 'M',
                avatarBg: '#16a34a'
            },
            'dosen_pengajar': {
                name: 'Dosen pengajar',
                badgeTitle: 'Dosen Pengajar',
                bg: '#b6a6f6',
                textColor: '#000000',
                label: 'NIDN / NIP Dosen Pengajar',
                placeholder: 'Contoh: 0412088501 atau dosen@siakad.ac.id',
                demoUser: 'dosen@siakad.ac.id',
                demoPass: 'dosen123',
                demoName: 'Dr. Ir. Budi Santoso, M.T.',
                avatarInitials: 'BS',
                avatarBg: '#7c3aed'
            },
            'dosen_wali': {
                name: 'Dosen wali',
                badgeTitle: 'Dosen Wali / Pembimbing',
                bg: '#945e2a',
                textColor: '#000000',
                label: 'NIDN / NIP Dosen Wali',
                placeholder: 'Contoh: 0425097902 atau wali@siakad.ac.id',
                demoUser: 'wali@siakad.ac.id',
                demoPass: 'wali123',
                demoName: 'Siti Rahmawati, S.Kom., M.Cs.',
                avatarInitials: 'SR',
                avatarBg: '#945e2a'
            },
            'bendahara': {
                name: 'Bendahara',
                badgeTitle: 'Bagian Keuangan & Transaksi',
                bg: '#f6e3c5',
                textColor: '#000000',
                label: 'ID Staf / Email Keuangan',
                placeholder: 'Contoh: STAF-091 atau bendahara@siakad.ac.id',
                demoUser: 'bendahara@siakad.ac.id',
                demoPass: 'bendahara123',
                demoName: 'Dewi Lestari, S.E., M.Ak.',
                avatarInitials: 'DL',
                avatarBg: '#d97706'
            }
        };

        let currentRoleKey = 'admin';
        let forgotEmailState = '';
        let verifiedOtpState = '';

        // Navigate between views: 'roles', 'login', 'forgot'
        function navigateView(viewName) {
            const mainLeft = document.getElementById('mainLeftPanel');
            const mainRight = document.getElementById('mainRightPanel');
            const securityRight = document.getElementById('securityRightPanel');
            const adminRight = document.getElementById('adminRightPanel');
            const mahasiswaRight = document.getElementById('mahasiswaRightPanel');
            const dosenRight = document.getElementById('dosenPengajarRightPanel');
            const dosenWaliRight = document.getElementById('dosenWaliRightPanel');
            const bendaharaRight = document.getElementById('bendaharaRightPanel');
            const sharedBrand = document.getElementById('sharedBrandHeader');

            const viewRoles = document.getElementById('viewRoleSelection');
            const viewLogin = document.getElementById('viewLoginForm');
            const viewForgot = document.getElementById('viewForgotPassword');

            // Reset visibility
            [viewRoles, viewLogin, viewForgot].forEach(v => v.classList.remove('active'));

            if (viewName === 'roles') {
                mainLeft.style.display = 'flex';
                mainRight.style.display = 'flex';
                securityRight.style.display = 'none';
                adminRight.style.display = 'none';
                mahasiswaRight.style.display = 'none';
                dosenRight.style.display = 'none';
                dosenWaliRight.style.display = 'none';
                bendaharaRight.style.display = 'none';
                sharedBrand.style.display = 'flex';
                viewRoles.classList.add('active');
            } else if (viewName === 'login') {
                mainLeft.style.display = 'flex';
                securityRight.style.display = 'none';

                if (currentRoleKey === 'admin') {
                    mainRight.style.display = 'none';
                    adminRight.style.display = 'flex';
                    mahasiswaRight.style.display = 'none';
                    dosenRight.style.display = 'none';
                    dosenWaliRight.style.display = 'none';
                    bendaharaRight.style.display = 'none';
                    document.getElementById('adminLoginSection').style.display = 'block';
                    document.getElementById('nonAdminLoginSection').style.display = 'none';
                    document.getElementById('mahasiswaLoginSection').style.display = 'none';
                    document.getElementById('dosenPengajarLoginSection').style.display = 'none';
                    document.getElementById('dosenWaliLoginSection').style.display = 'none';
                    document.getElementById('bendaharaLoginSection').style.display = 'none';
                    sharedBrand.style.display = 'none';
                } else if (currentRoleKey === 'mahasiswa') {
                    mainRight.style.display = 'none';
                    adminRight.style.display = 'none';
                    mahasiswaRight.style.display = 'flex';
                    dosenRight.style.display = 'none';
                    dosenWaliRight.style.display = 'none';
                    bendaharaRight.style.display = 'none';
                    document.getElementById('adminLoginSection').style.display = 'none';
                    document.getElementById('nonAdminLoginSection').style.display = 'none';
                    document.getElementById('mahasiswaLoginSection').style.display = 'block';
                    document.getElementById('dosenPengajarLoginSection').style.display = 'none';
                    document.getElementById('dosenWaliLoginSection').style.display = 'none';
                    document.getElementById('bendaharaLoginSection').style.display = 'none';
                    sharedBrand.style.display = 'none';
                } else if (currentRoleKey === 'dosen_pengajar') {
                    mainRight.style.display = 'none';
                    adminRight.style.display = 'none';
                    mahasiswaRight.style.display = 'none';
                    dosenRight.style.display = 'flex';
                    dosenWaliRight.style.display = 'none';
                    bendaharaRight.style.display = 'none';
                    document.getElementById('adminLoginSection').style.display = 'none';
                    document.getElementById('nonAdminLoginSection').style.display = 'none';
                    document.getElementById('mahasiswaLoginSection').style.display = 'none';
                    document.getElementById('dosenPengajarLoginSection').style.display = 'block';
                    document.getElementById('dosenWaliLoginSection').style.display = 'none';
                    document.getElementById('bendaharaLoginSection').style.display = 'none';
                    sharedBrand.style.display = 'none';
                } else if (currentRoleKey === 'dosen_wali') {
                    mainRight.style.display = 'none';
                    adminRight.style.display = 'none';
                    mahasiswaRight.style.display = 'none';
                    dosenRight.style.display = 'none';
                    dosenWaliRight.style.display = 'flex';
                    bendaharaRight.style.display = 'none';
                    document.getElementById('adminLoginSection').style.display = 'none';
                    document.getElementById('nonAdminLoginSection').style.display = 'none';
                    document.getElementById('mahasiswaLoginSection').style.display = 'none';
                    document.getElementById('dosenPengajarLoginSection').style.display = 'none';
                    document.getElementById('dosenWaliLoginSection').style.display = 'block';
                    document.getElementById('bendaharaLoginSection').style.display = 'none';
                    sharedBrand.style.display = 'none';
                } else if (currentRoleKey === 'bendahara') {
                    mainRight.style.display = 'none';
                    adminRight.style.display = 'none';
                    mahasiswaRight.style.display = 'none';
                    dosenRight.style.display = 'none';
                    dosenWaliRight.style.display = 'none';
                    bendaharaRight.style.display = 'flex';
                    document.getElementById('adminLoginSection').style.display = 'none';
                    document.getElementById('nonAdminLoginSection').style.display = 'none';
                    document.getElementById('mahasiswaLoginSection').style.display = 'none';
                    document.getElementById('dosenPengajarLoginSection').style.display = 'none';
                    document.getElementById('dosenWaliLoginSection').style.display = 'none';
                    document.getElementById('bendaharaLoginSection').style.display = 'block';
                    sharedBrand.style.display = 'none';
                } else {
                    mainRight.style.display = 'flex';
                    adminRight.style.display = 'none';
                    mahasiswaRight.style.display = 'none';
                    dosenRight.style.display = 'none';
                    dosenWaliRight.style.display = 'none';
                    bendaharaRight.style.display = 'none';
                    document.getElementById('adminLoginSection').style.display = 'none';
                    document.getElementById('mahasiswaLoginSection').style.display = 'none';
                    document.getElementById('dosenPengajarLoginSection').style.display = 'none';
                    document.getElementById('dosenWaliLoginSection').style.display = 'none';
                    document.getElementById('bendaharaLoginSection').style.display = 'none';
                    document.getElementById('nonAdminLoginSection').style.display = 'block';
                    sharedBrand.style.display = 'flex';
                }

                viewLogin.classList.add('active');
            } else if (viewName === 'forgot') {
                mainLeft.style.display = 'flex';
                mainRight.style.display = 'none';
                securityRight.style.display = 'flex';
                adminRight.style.display = 'none';
                mahasiswaRight.style.display = 'none';
                dosenRight.style.display = 'none';
                dosenWaliRight.style.display = 'none';
                sharedBrand.style.display = 'flex';
                viewForgot.classList.add('active');

                document.getElementById('forgotStepEmail').style.display = 'block';
                document.getElementById('forgotStepOtp').style.display = 'none';
                document.getElementById('forgotStepNewPass').style.display = 'none';
            }
        }

        // Role Card Click
        function selectRole(roleKey) {
            const config = ROLES_CONFIG[roleKey];
            if (!config) return;

            currentRoleKey = roleKey;

            if (roleKey === 'admin') {
                const roleInput = document.getElementById('selectedRoleInput');
                const input = document.getElementById('usernameInput');
                const passInput = document.getElementById('passwordInput');
                if (roleInput) roleInput.value = 'admin';
                if (input) input.value = '';
                if (passInput) passInput.value = '';
                navigateView('login');
                if (input) input.focus();
            } else if (roleKey === 'mahasiswa') {
                const input = document.getElementById('usernameInputMahasiswa');
                const passInput = document.getElementById('passwordInputMahasiswa');
                const roleInput = document.getElementById('selectedRoleInputMahasiswa');
                if (roleInput) roleInput.value = 'mahasiswa';
                if (input) input.value = '';
                if (passInput) passInput.value = '';
                navigateView('login');
                if (input) input.focus();
            } else if (roleKey === 'dosen_pengajar') {
                const input = document.getElementById('usernameInputDosen');
                const passInput = document.getElementById('passwordInputDosen');
                const roleInput = document.getElementById('selectedRoleInputDosen');
                if (roleInput) roleInput.value = 'dosen_pengajar';
                if (input) input.value = '';
                if (passInput) passInput.value = '';
                navigateView('login');
                if (input) input.focus();
            } else if (roleKey === 'dosen_wali') {
                const input = document.getElementById('usernameInputDosenWali');
                const passInput = document.getElementById('passwordInputDosenWali');
                const roleInput = document.getElementById('selectedRoleInputDosenWali');
                if (roleInput) roleInput.value = 'dosen_wali';
                if (input) input.value = '';
                if (passInput) passInput.value = '';
                navigateView('login');
                if (input) input.focus();
            } else if (roleKey === 'bendahara') {
                const input = document.getElementById('usernameInputBendahara');
                const passInput = document.getElementById('passwordInputBendahara');
                const roleInput = document.getElementById('selectedRoleInputBendahara');
                if (roleInput) roleInput.value = 'bendahara';
                if (input) input.value = '';
                if (passInput) passInput.value = '';
                navigateView('login');
                if (input) input.focus();
            } else {
                const badge = document.getElementById('activeRoleBadge');
                const badgeTitle = document.getElementById('badgeRoleTitle');
                const label = document.getElementById('usernameLabelNonAdmin');
                const input = document.getElementById('usernameInputNonAdmin');
                const passInput = document.getElementById('passwordInputNonAdmin');
                const headerTitle = document.getElementById('formHeaderTitle');
                const headerDesc = document.getElementById('formHeaderDesc');

                badge.style.backgroundColor = config.bg;
                badge.style.color = config.textColor;
                badgeTitle.textContent = 'Role: ' + config.name;

                label.textContent = config.label;
                input.placeholder = config.placeholder;
                input.value = '';
                passInput.value = '';
                headerTitle.textContent = 'Masuk sebagai ' + config.name;
                headerDesc.textContent = 'Silakan masukkan ' + config.label + ' dan kata sandi Anda';

                navigateView('login');
                input.focus();
            }
        }

        // Non-admin login submit
        function submitNonAdminLogin() {
            const username = document.getElementById('usernameInputNonAdmin').value.trim();
            const password = document.getElementById('passwordInputNonAdmin').value.trim();
            const remember = document.getElementById('rememberMeCheckboxNonAdmin').checked;

            if (!username || !password) {
                showToast('Mohon isi nomor identitas dan kata sandi!', true);
                return;
            }

            // Inject values to main hidden inputs for shared API handler
            document.getElementById('usernameInput').value = username;
            document.getElementById('passwordInput').value = password;

            const fakeEvent = { preventDefault: () => {} };
            handleApiLoginSubmit(fakeEvent);
        }

        // Auto-fill demo credentials
        function fillDemoCredentials() {
            const config = ROLES_CONFIG[currentRoleKey];
            if (!config) return;

            if (currentRoleKey === 'admin') {
                document.getElementById('usernameInput').value = config.demoUser;
                document.getElementById('passwordInput').value = config.demoPass;
            } else {
                document.getElementById('usernameInputNonAdmin').value = config.demoUser;
                document.getElementById('passwordInputNonAdmin').value = config.demoPass;
            }

            showToast('Kredensial demo diisi: ' + config.demoUser, false);
        }

        // Toggle Password Show/Hide
        function togglePasswordVisibility(inputId, openIconId, closedIconId) {
            const passInput = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openIconId);
            const eyeClosed = document.getElementById(closedIconId);

            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                passInput.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        }

        // ==========================================
        // RATE LIMITER: LOCKOUT COUNTDOWN TIMER
        // ==========================================
        let lockoutInterval = null;

        function startLockoutCountdown(seconds) {
            const banner = document.getElementById('lockoutBanner');
            const countdown = document.getElementById('lockoutCountdown');
            const btn = document.getElementById('loginSubmitBtn');
            const attemptWarning = document.getElementById('attemptWarning');

            banner.classList.add('active');
            attemptWarning.classList.remove('active');
            btn.disabled = true;

            let remaining = seconds;

            function updateDisplay() {
                const m = Math.floor(remaining / 60).toString().padStart(2, '0');
                const s = (remaining % 60).toString().padStart(2, '0');
                countdown.textContent = m + ':' + s;
            }

            updateDisplay();

            if (lockoutInterval) clearInterval(lockoutInterval);
            lockoutInterval = setInterval(() => {
                remaining--;
                updateDisplay();
                if (remaining <= 0) {
                    clearInterval(lockoutInterval);
                    banner.classList.remove('active');
                    btn.disabled = false;
                    // Reset reCAPTCHA
                    if (typeof grecaptcha !== 'undefined') {
                        grecaptcha.reset();
                    }
                    showToast('Kunci akun telah dibuka. Silakan coba login kembali.', false);
                }
            }, 1000);
        }

        function updateAttemptWarning(attempts, maxAttempts) {
            const warning = document.getElementById('attemptWarning');
            const text = document.getElementById('attemptWarningText');
            if (attempts > 0 && attempts < maxAttempts) {
                warning.classList.add('active');
                const remaining = maxAttempts - attempts;
                text.textContent = 'Percobaan ke-' + attempts + ' dari ' + maxAttempts + '. ' +
                    (remaining === 1 ? 'Satu kali lagi gagal, akun akan dikunci!' : remaining + ' percobaan tersisa sebelum akun dikunci.');
            } else {
                warning.classList.remove('active');
            }
        }

        // ==========================================
        // API CALL: LOGIN DENGAN VALIDASI HASH
        // ==========================================
        async function handleApiLoginSubmit(event) {
            event.preventDefault();
            const username = document.getElementById('usernameInput').value.trim();
            const password = document.getElementById('passwordInput').value.trim();
            const role = document.getElementById('selectedRoleInput').value;
            const rememberElem = document.getElementById('rememberMeCheckbox') || document.getElementById('rememberMeCheckboxNonAdmin');
            const remember = rememberElem ? rememberElem.checked : false;

            const btn = document.getElementById('loginSubmitBtn');
            const btnText = document.getElementById('submitBtnText');
            const btnArrow = document.getElementById('submitBtnArrow');

            if (!username || !password) {
                showToast('Mohon isi nomor identitas dan kata sandi!', true);
                return;
            }

            // Ambil token reCAPTCHA
            let recaptchaToken = '';
            if (typeof grecaptcha !== 'undefined') {
                recaptchaToken = grecaptcha.getResponse();
                if (!recaptchaToken) {
                    showToast('Mohon centang verifikasi reCAPTCHA terlebih dahulu!', true);
                    return;
                }
            }

            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Memvalidasi kredensial...';
            btnArrow.style.display = 'none';

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password,
                        role: role,
                        remember: remember,
                        'g-recaptcha-response': recaptchaToken
                    })
                });

                const data = await response.json();

                btn.disabled = false;
                btnText.textContent = 'Masuk ke SI-AKAD';
                btnArrow.style.display = 'block';

                // Reset reCAPTCHA setelah setiap attempt
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }

                if (response.ok && data.success) {
                    // Login berhasil: reset semua warning
                    document.getElementById('attemptWarning').classList.remove('active');
                    document.getElementById('lockoutBanner').classList.remove('active');
                    showSuccessModal(data.user);
                } else if (response.status === 429) {
                    // Rate limit / lockout aktif
                    const retryAfter = data.retry_after || 300;
                    startLockoutCountdown(retryAfter);
                    showToast(data.message || 'Terlalu banyak percobaan. Akun dikunci sementara.', true);
                } else {
                    // Login gagal biasa - tampilkan counter percobaan
                    if (data.attempts !== undefined) {
                        updateAttemptWarning(data.attempts, data.max_attempts || 3);
                    }
                    showToast(data.message || 'Login gagal. Periksa kembali data Anda.', true);
                }
            } catch (err) {
                btn.disabled = false;
                btnText.textContent = 'Masuk ke SI-AKAD';
                btnArrow.style.display = 'block';
                showToast('Terjadi gangguan jaringan ke server API.', true);
            }
        }

        // Google OAuth: login dilakukan via redirect server-side (/auth/google/redirect)
        // Fungsi ini hanya digunakan jika perlu fallback manual (tidak dipakai dalam flow normal)
        // ==========================================
        // API CALL: GOOGLE AUTHENTICATION (Fallback)
        // ==========================================
        async function loginWithGoogleAccount(email, name) {
            showToast('Menghubungkan ke Akun Google: ' + email + '...');

            try {
                const response = await fetch('/api/auth/google', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        name: name
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showSuccessModal(data.user);
                } else {
                    showToast(data.message || 'Gagal login dengan akun Google.', true);
                }
            } catch (err) {
                showToast('Gagal menghubungi endpoint Google auth.', true);
            }
        }

        // ==========================================
        // API CALL: FORGOT PASSWORD & OTP
        // ==========================================
        async function handleSendOtp(event) {
            event.preventDefault();
            const email = document.getElementById('forgotEmailInput').value.trim();
            const btn = document.getElementById('btnSendOtp');
            const btnText = document.getElementById('btnSendOtpText');

            if (!email) {
                showToast('Mohon masukkan alamat email yang terdaftar!', true);
                return;
            }

            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Mengirimkan OTP...';

            try {
                const response = await fetch('/api/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                btn.disabled = false;
                btnText.textContent = 'kirim kode OTP';

                if (response.ok && data.success) {
                    forgotEmailState = email;
                    document.getElementById('targetEmailLabel').textContent = email;
                    
                    // Display OTP demo for direct interactive testing
                    if (data.otp_demo) {
                        document.getElementById('demoOtpAlert').innerHTML = '⚡ Kode OTP Anda: <span style="font-size:16px; letter-spacing:2px; color:#1e40af;">' + data.otp_demo + '</span>';
                        // Auto-fill OTP inputs for smooth developer/user convenience
                        const digits = data.otp_demo.split('');
                        for (let i = 1; i <= 6; i++) {
                            const input = document.getElementById('otp_' + i);
                            if (input && digits[i - 1]) input.value = digits[i - 1];
                        }
                    }

                    document.getElementById('forgotStepEmail').style.display = 'none';
                    document.getElementById('forgotStepOtp').style.display = 'block';

                    showToast(data.message, false);
                } else {
                    showToast(data.message || 'Email tidak ditemukan.', true);
                }
            } catch (err) {
                btn.disabled = false;
                btnText.textContent = 'kirim kode OTP';
                showToast('Gagal mengirimkan permintaan OTP.', true);
            }
        }

        // OTP inputs auto-advance
        function handleOtpInput(current, nextId) {
            if (current.value.length === 1 && nextId) {
                const next = document.getElementById(nextId);
                if (next) next.focus();
            }
        }

        function getFullOtpValue() {
            let otp = '';
            for (let i = 1; i <= 6; i++) {
                otp += document.getElementById('otp_' + i).value.trim();
            }
            return otp;
        }

        // Verify OTP API
        async function handleVerifyOtp(event) {
            event.preventDefault();
            const otp = getFullOtpValue();
            const btn = document.getElementById('btnVerifyOtp');
            const btnText = document.getElementById('btnVerifyOtpText');

            if (otp.length !== 6) {
                showToast('Mohon masukkan lengkap 6 digit kode OTP!', true);
                return;
            }

            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Memverifikasi...';

            try {
                const response = await fetch('/api/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: forgotEmailState,
                        otp: otp
                    })
                });

                const data = await response.json();

                btn.disabled = false;
                btnText.textContent = 'Verifikasi Kode OTP';

                if (response.ok && data.success) {
                    verifiedOtpState = otp;
                    document.getElementById('forgotStepOtp').style.display = 'none';
                    document.getElementById('forgotStepNewPass').style.display = 'block';
                    showToast(data.message, false);
                } else {
                    showToast(data.message || 'Kode OTP salah atau kedaluwarsa.', true);
                }
            } catch (err) {
                btn.disabled = false;
                btnText.textContent = 'Verifikasi Kode OTP';
                showToast('Gagal memverifikasi OTP.', true);
            }
        }

        // Resend OTP
        function resendOtp() {
            document.getElementById('forgotStepOtp').style.display = 'none';
            document.getElementById('forgotStepEmail').style.display = 'block';
            showToast('Silakan kirim ulang kode OTP');
        }

        // Reset New Password API
        async function handleResetNewPassword(event) {
            event.preventDefault();
            const pass = document.getElementById('newPasswordInput').value;
            const confirmPass = document.getElementById('confirmPasswordInput').value;
            const btn = document.getElementById('btnResetPass');
            const btnText = document.getElementById('btnResetPassText');

            if (pass !== confirmPass) {
                showToast('Konfirmasi kata sandi tidak cocok!', true);
                return;
            }

            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Menyimpan...';

            try {
                const response = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: forgotEmailState,
                        otp: verifiedOtpState,
                        password: pass,
                        password_confirmation: confirmPass
                    })
                });

                const data = await response.json();

                btn.disabled = false;
                btnText.textContent = 'Simpan Password Baru';

                if (response.ok && data.success) {
                    showToast(data.message, false);
                    setTimeout(() => {
                        navigateView('login');
                        document.getElementById('usernameInput').value = forgotEmailState;
                        document.getElementById('passwordInput').value = pass;
                    }, 1200);
                } else {
                    showToast(data.message || 'Gagal mereset kata sandi.', true);
                }
            } catch (err) {
                btn.disabled = false;
                btnText.textContent = 'Simpan Password Baru';
                showToast('Gagal memperbarui kata sandi.', true);
            }
        }

        // Show Authentication Success Modal
        function showSuccessModal(user) {
            const modal = document.getElementById('successModal');
            const avatar = document.getElementById('modalUserAvatar');
            const name = document.getElementById('modalUserName');
            const sub = document.getElementById('modalUserId');

            avatar.textContent = user.avatar || user.name.substring(0, 2).toUpperCase();
            name.textContent = user.name;
            sub.textContent = 'Email: ' + user.email + ' • Role: ' + (user.role.toUpperCase());

            modal.classList.add('active');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('active');
            navigateView('roles');
        }

        function openSimulatedDashboard() {
            document.getElementById('successModal').classList.remove('active');
            showToast('Mengalihkan ke Portal Dashboard Akademik...', false);
        }

        // Toast Notification helper
        let toastTimeout;
        function showToast(message, isError = false) {
            const toast = document.getElementById('toastNotification');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');

            toastMsg.textContent = message;

            if (isError) {
                toast.classList.add('toast-error');
                toastIcon.style.stroke = '#fca5a5';
            } else {
                toast.classList.remove('toast-error');
                toastIcon.style.stroke = '#4ade80';
            }

            toast.classList.add('active');
            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.remove('active');
            }, 3500);
        }

        // Handle Google OAuth callback feedback on load
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');

            if (error === 'google_not_registered') {
                const email = decodeURIComponent(urlParams.get('email') || '');
                showToast('Email Google ' + (email ? '(' + email + ')' : '') + ' tidak terdaftar di SI-AKAD. Silakan login dengan username/password.', true);
            } else if (error === 'google_not_configured') {
                showToast('Google OAuth belum dikonfigurasi oleh administrator sistem.', true);
            } else if (error === 'google_auth_failed' || error === 'google_token_failed' || error === 'google_user_failed') {
                showToast('Login Google dibatalkan atau terjadi kesalahan pada otentikasi.', true);
            } else if (error === 'google_email_missing') {
                showToast('Gagal mendapatkan alamat email dari akun Google.', true);
            }
        });
    </script>
</body>
</html>
