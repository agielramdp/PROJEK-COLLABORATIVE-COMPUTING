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
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.08);
            display: grid;
            grid-template-columns: 52% 48%;
            min-height: 640px;
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

        /* Corner metallic pearlescent reflection (matches screenshot bottom-left) */
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

        .card-dosen-wali .role-name,
        .card-dosen-wali .role-desc {
            color: #000000;
        }

        /* Bendahara spans full width across 2 columns */
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
        }

        /* Deep blue gradient overlay top-down to enhance text readability */
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

        /* Architectural Signage Overlay (matching building plaque in screenshot) */
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
        /* INTERACTIVE LOGIN VIEW (SLIDES IN WHEN A ROLE IS SELECTED)   */
        /* ============================================================ */
        .view-container {
            position: relative;
            width: 100%;
            height: 100%;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .role-selection-view {
            width: 100%;
            display: flex;
            flex-direction: column;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .login-form-view {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            display: none;
            flex-direction: column;
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .view-container.show-login .role-selection-view {
            display: none;
            opacity: 0;
            transform: translateX(-30px);
        }

        .view-container.show-login .login-form-view {
            display: flex;
            position: relative;
            opacity: 1;
            transform: translateX(0);
        }

        /* Back to Role Selection Button */
        .back-role-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            width: fit-content;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .back-role-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: translateX(-2px);
        }

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

        .form-input.is-invalid {
            border-color: #ef4444;
            background: #fff5f5;
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
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }

        /* Submit Button */
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

        /* Demo Fill Helper Button */
        .demo-fill-btn {
            width: 100%;
            margin-top: 12px;
            height: 38px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            color: #475569;
            font-size: 12.5px;
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

            .right-panel {
                min-height: 380px;
                padding: 36px 28px;
            }

            .hero-headline {
                font-size: 32px;
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

    <div class="portal-wrapper">
        
        <!-- LEFT PANEL -->
        <div class="left-panel">
            <div class="left-content">
                
                <!-- TOP LOGO & INSTITUTION -->
                <div class="brand-header">
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

                <!-- VIEW CONTAINER (SWITCHES BETWEEN ROLE SELECTION & LOGIN FORM) -->
                <div class="view-container" id="viewContainer">
                    
                    <!-- 1. ROLE SELECTION VIEW (EXACT AS SCREENSHOT) -->
                    <div class="role-selection-view" id="roleSelectionView">
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
                                        <!-- Admin Icon: User at Laptop with Gear -->
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
                                        <!-- Mahasiswa Icon: Graduation Cap / Toga -->
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
                                        <!-- Dosen Pengajar: Lecturer with Presentation Board -->
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
                                        <!-- Dosen Wali: Mentorship / Advisor with student -->
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
                                        <!-- Bendahara: Wallet with Cash / Cards -->
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

                    <!-- 2. FUNCTIONAL LOGIN FORM VIEW -->
                    <div class="login-form-view" id="loginFormView">
                        <button type="button" class="back-role-btn" onclick="backToRoles()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Pilih Role Lain
                        </button>

                        <div class="active-role-badge-row">
                            <div class="active-role-badge" id="activeRoleBadge">
                                <!-- Populated dynamically -->
                                <span id="badgeDot">●</span>
                                <span id="badgeRoleTitle">Admin</span>
                            </div>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Tahun Ajaran 2026/2027</span>
                        </div>

                        <h2 class="form-header-title" id="formHeaderTitle">Masuk ke Akun Anda</h2>
                        <p class="form-header-subtitle" id="formHeaderDesc">Silakan masukkan identitas dan kata sandi Anda</p>

                        <form id="authLoginForm" onsubmit="handleLoginSubmit(event)">
                            <input type="hidden" id="selectedRoleInput" name="role" value="admin">

                            <!-- Identifier input (NIM / NIDN / NIP / ID) -->
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
                                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility()" aria-label="Lihat kata sandi">
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

                            <!-- Helper row: Remember & Forgot -->
                            <div class="form-helper-row">
                                <label class="remember-checkbox-label">
                                    <input type="checkbox" id="rememberMeCheckbox" checked>
                                    <span>Ingat saya</span>
                                </label>
                                <a href="javascript:void(0)" class="forgot-link" onclick="handleForgotPassword()">Lupa kata sandi?</a>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="submit-btn" id="loginSubmitBtn">
                                <span id="submitBtnText">Masuk ke SI-AKAD</span>
                                <svg id="submitBtnArrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>

                            <!-- Demo Auto-Fill helper -->
                            <button type="button" class="demo-fill-btn" onclick="fillDemoCredentials()">
                                <span>⚡ Klik untuk Isi Kredensial Demo Cepat</span>
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>

        <!-- RIGHT PANEL (CAMPUS ARCHITECTURE & BRAND HERO) -->
        <div class="right-panel">
            <div class="right-content">
                <!-- Pill Badge -->
                <div class="hero-badge">SI-AKAD</div>

                <!-- Main Bold Headline -->
                <h2 class="hero-headline">
                    <span class="text-white">Satu Portal,</span>
                    <span class="text-cyan">Semua Kebutuhan</span>
                    <span class="text-white">Akademik</span>
                </h2>

                <!-- Subtitle -->
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
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.5">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span id="toastMessage">Pesan pemberitahuan</span>
    </div>

    <!-- JAVASCRIPT FOR DYNAMIC LOGIC & FUNCTIONALITY -->
    <script>
        // Configuration for each role
        const ROLES_CONFIG = {
            'admin': {
                name: 'Admin',
                badgeTitle: 'Administrator Sistem',
                bg: '#639cf6',
                textColor: '#000000',
                label: 'Username / NIP Administrator',
                placeholder: 'Contoh: admin@siakad.ac.id atau 19850101...',
                demoUser: 'admin.utama@siakad.ac.id',
                demoPass: 'Admin2026!Secure',
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
                placeholder: 'Contoh: 2201010048',
                demoUser: '2201010048',
                demoPass: 'Mhs*Akad2026',
                demoName: 'Agiel Ramadhan Pratama',
                avatarInitials: 'AR',
                avatarBg: '#16a34a'
            },
            'dosen_pengajar': {
                name: 'Dosen pengajar',
                badgeTitle: 'Dosen Pengajar',
                bg: '#b6a6f6',
                textColor: '#000000',
                label: 'NIDN / NIP Dosen Pengajar',
                placeholder: 'Contoh: 0412088501',
                demoUser: '0412088501',
                demoPass: 'Dosen2026#Edu',
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
                placeholder: 'Contoh: 0425097902',
                demoUser: '0425097902',
                demoPass: 'Wali2026*Guidance',
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
                placeholder: 'Contoh: keuangan@siakad.ac.id atau STAF-091',
                demoUser: 'keuangan@siakad.ac.id',
                demoPass: 'Finansi2026@Pass',
                demoName: 'Dewi Lestari, S.E., M.Ak.',
                avatarInitials: 'DL',
                avatarBg: '#d97706'
            }
        };

        let currentRoleKey = 'admin';

        // Select a role from the 5 buttons
        function selectRole(roleKey) {
            const config = ROLES_CONFIG[roleKey];
            if (!config) return;

            currentRoleKey = roleKey;
            const container = document.getElementById('viewContainer');
            const badge = document.getElementById('activeRoleBadge');
            const badgeTitle = document.getElementById('badgeRoleTitle');
            const label = document.getElementById('usernameLabel');
            const input = document.getElementById('usernameInput');
            const passInput = document.getElementById('passwordInput');
            const roleInput = document.getElementById('selectedRoleInput');
            const headerTitle = document.getElementById('formHeaderTitle');
            const headerDesc = document.getElementById('formHeaderDesc');

            // Apply badge styles
            badge.style.backgroundColor = config.bg;
            badge.style.color = config.textColor;
            badgeTitle.textContent = 'Role: ' + config.name;

            // Update inputs & labels
            label.textContent = config.label;
            input.placeholder = config.placeholder;
            input.value = '';
            passInput.value = '';
            roleInput.value = roleKey;
            headerTitle.textContent = 'Masuk sebagai ' + config.name;
            headerDesc.textContent = 'Silakan masukkan ' + config.label + ' dan kata sandi Anda';

            // Transition smoothly to login form
            container.classList.add('show-login');
            input.focus();

            showToast('Role dipilih: ' + config.name);
        }

        // Return back to role selection grid
        function backToRoles() {
            const container = document.getElementById('viewContainer');
            container.classList.remove('show-login');
        }

        // Auto-fill demo credentials for instant testing
        function fillDemoCredentials() {
            const config = ROLES_CONFIG[currentRoleKey];
            if (!config) return;

            const input = document.getElementById('usernameInput');
            const passInput = document.getElementById('passwordInput');

            input.value = config.demoUser;
            passInput.value = config.demoPass;

            showToast('Kredensial demo otomatis terisi untuk ' + config.name);
        }

        // Toggle password show / hide
        function togglePasswordVisibility() {
            const passInput = document.getElementById('passwordInput');
            const eyeOpen = document.getElementById('eyeIconOpen');
            const eyeClosed = document.getElementById('eyeIconClosed');

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

        // Handle login form submission
        function handleLoginSubmit(event) {
            event.preventDefault();
            const config = ROLES_CONFIG[currentRoleKey];
            const username = document.getElementById('usernameInput').value.trim();
            const password = document.getElementById('passwordInput').value.trim();
            const btn = document.getElementById('loginSubmitBtn');
            const btnText = document.getElementById('submitBtnText');
            const btnArrow = document.getElementById('submitBtnArrow');

            if (!username || !password) {
                showToast('Mohon isi semua kolom yang diperlukan!');
                return;
            }

            // Show loading animation
            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Memverifikasi...';
            btnArrow.style.display = 'none';

            // Simulate realistic authentication verification
            setTimeout(() => {
                btn.disabled = false;
                btnText.textContent = 'Masuk ke SI-AKAD';
                btnArrow.style.display = 'block';

                // Display user profile in success modal
                const modal = document.getElementById('successModal');
                const avatar = document.getElementById('modalUserAvatar');
                const name = document.getElementById('modalUserName');
                const sub = document.getElementById('modalUserId');

                avatar.textContent = config.avatarInitials;
                avatar.style.backgroundColor = config.avatarBg;
                name.textContent = config.demoName;
                sub.textContent = 'ID: ' + username + ' • Role: ' + config.badgeTitle;

                modal.classList.add('active');
            }, 800);
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('active');
            backToRoles();
        }

        function openSimulatedDashboard() {
            const config = ROLES_CONFIG[currentRoleKey];
            document.getElementById('successModal').classList.remove('active');
            showToast('Mengalihkan ke Portal ' + config.name + '...');
        }

        function handleForgotPassword() {
            const config = ROLES_CONFIG[currentRoleKey];
            alert(
                'Bantuan Lupa Kata Sandi (' + config.name + '):\n\n' +
                'Silakan hubungi Unit Pelaksana Teknis Sistem Informasi (UPT-SI) Institut Informatika & Komputer di Gedung Rektorat Lt. 2 atau kirimkan permohonan reset password ke helpdesk@siakad.ac.id dengan melampirkan identitas resmi.'
            );
        }

        // Display toast message
        let toastTimeout;
        function showToast(message) {
            const toast = document.getElementById('toastNotification');
            const toastMsg = document.getElementById('toastMessage');
            toastMsg.textContent = message;
            toast.classList.add('active');

            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.remove('active');
            }, 3000);
        }
    </script>
</body>
</html>
