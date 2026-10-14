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
        /* RIGHT PANEL: SECURITY ILLUSTRATION (FOR LUPA PASSWORD VIEW)  */
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
                <div class="brand-header" onclick="navigateView('roles')">
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

                <!-- SUB-VIEW 2: ROLE LOGIN FORM (CONNECTS TO /api/login) -->
                <div class="view-section" id="viewLoginForm" style="margin-top: 24px;">
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

                    <form id="authLoginForm" onsubmit="handleApiLoginSubmit(event)">
                        <input type="hidden" id="selectedRoleInput" name="role" value="admin">

                        <!-- Identifier input -->
                        <div class="form-group">
                            <label class="form-label" for="usernameInput" id="usernameLabel">Nomor Identitas</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input type="text" id="usernameInput" class="form-input" placeholder="Masukkan ID pengguna" required autocomplete="username">
                            </div>
                        </div>

                        <!-- Password input -->
                        <div class="form-group">
                            <label class="form-label" for="passwordInput">Kata Sandi</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <input type="password" id="passwordInput" class="form-input" placeholder="Masukkan kata sandi" required autocomplete="current-password">
                                <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('passwordInput', 'eyeIconOpen', 'eyeIconClosed')" aria-label="Lihat kata sandi">
                                    <svg id="eyeIconOpen" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg id="eyeIconClosed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Helper row -->
                        <div class="form-helper-row">
                            <label class="remember-checkbox-label">
                                <input type="checkbox" id="rememberMeCheckbox" checked>
                                <span>Ingat saya</span>
                            </label>
                            <a href="javascript:void(0)" class="forgot-link" onclick="navigateView('forgot')">Lupa Password?</a>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="submit-btn" id="loginSubmitBtn">
                            <span id="submitBtnText">Masuk ke SI-AKAD</span>
                            <svg id="submitBtnArrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <div class="auth-divider">atau masuk dengan</div>

                        <!-- Google OAuth Button: selalu redirect ke Google resmi -->
                        @if(!empty($googleConfigured))
                        <a href="{{ route('google.redirect') }}" class="btn-google-login" style="text-decoration: none;" id="btnGoogleOAuth">
                            <svg class="google-g-logo" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Masuk dengan Akun Google</span>
                        </a>
                        @else
                        {{-- Google belum dikonfigurasi: tampil tombol disable + petunjuk --}}
                        <button type="button" class="btn-google-login" disabled style="opacity:0.55; cursor:not-allowed;" title="Google OAuth belum dikonfigurasi di server">
                            <svg class="google-g-logo" viewBox="0 0 24 24">
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

                        <!-- Demo Auto-Fill helper -->
                        <button type="button" class="demo-fill-btn" onclick="fillDemoCredentials()">
                            <span>⚡ Klik untuk Isi Kredensial Demo Cepat</span>
                        </button>
                    </form>
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

        <!-- RIGHT PANEL: CAMPUS ARCHITECTURE (DEFAULT FOR ROLES & LOGIN) -->
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

        <!-- RIGHT PANEL: SECURITY ILLUSTRATION (MATCHES SCREENSHOT 2 FOR LUPA PASSWORD) -->
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

            const viewRoles = document.getElementById('viewRoleSelection');
            const viewLogin = document.getElementById('viewLoginForm');
            const viewForgot = document.getElementById('viewForgotPassword');

            // Reset visibility
            [viewRoles, viewLogin, viewForgot].forEach(v => v.classList.remove('active'));

            if (viewName === 'roles') {
                mainLeft.style.display = 'flex';
                mainRight.style.display = 'flex';
                securityRight.style.display = 'none';
                viewRoles.classList.add('active');
            } else if (viewName === 'login') {
                mainLeft.style.display = 'flex';
                mainRight.style.display = 'flex';
                securityRight.style.display = 'none';
                viewLogin.classList.add('active');
            } else if (viewName === 'forgot') {
                mainLeft.style.display = 'flex';
                mainRight.style.display = 'none';
                securityRight.style.display = 'flex';
                viewForgot.classList.add('active');

                // Reset forgot steps to step A
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
            const badge = document.getElementById('activeRoleBadge');
            const badgeTitle = document.getElementById('badgeRoleTitle');
            const label = document.getElementById('usernameLabel');
            const input = document.getElementById('usernameInput');
            const passInput = document.getElementById('passwordInput');
            const roleInput = document.getElementById('selectedRoleInput');
            const headerTitle = document.getElementById('formHeaderTitle');
            const headerDesc = document.getElementById('formHeaderDesc');

            badge.style.backgroundColor = config.bg;
            badge.style.color = config.textColor;
            badgeTitle.textContent = 'Role: ' + config.name;

            label.textContent = config.label;
            input.placeholder = config.placeholder;
            input.value = '';
            passInput.value = '';
            roleInput.value = roleKey;
            headerTitle.textContent = 'Masuk sebagai ' + config.name;
            headerDesc.textContent = 'Silakan masukkan ' + config.label + ' dan kata sandi Anda';

            navigateView('login');
            input.focus();
        }

        // Auto-fill demo credentials
        function fillDemoCredentials() {
            const config = ROLES_CONFIG[currentRoleKey];
            if (!config) return;

            document.getElementById('usernameInput').value = config.demoUser;
            document.getElementById('passwordInput').value = config.demoPass;

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
        // API CALL: LOGIN DENGAN VALIDASI HASH
        // ==========================================
        async function handleApiLoginSubmit(event) {
            event.preventDefault();
            const username = document.getElementById('usernameInput').value.trim();
            const password = document.getElementById('passwordInput').value.trim();
            const role = document.getElementById('selectedRoleInput').value;
            const remember = document.getElementById('rememberMeCheckbox').checked;

            const btn = document.getElementById('loginSubmitBtn');
            const btnText = document.getElementById('submitBtnText');
            const btnArrow = document.getElementById('submitBtnArrow');

            if (!username || !password) {
                showToast('Mohon isi nomor identitas dan kata sandi!', true);
                return;
            }

            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Memvalidasi kredensial...';
            btnArrow.style.display = 'none';

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password,
                        role: role,
                        remember: remember
                    })
                });

                const data = await response.json();

                btn.disabled = false;
                btnText.textContent = 'Masuk ke SI-AKAD';
                btnArrow.style.display = 'block';

                if (response.ok && data.success) {
                    showSuccessModal(data.user);
                } else {
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
