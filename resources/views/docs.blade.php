<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi API Wilayah Indonesia 2026 — OpenAPI 3.0.3</title>
    <meta name="description" content="Dokumentasi resmi REST API Wilayah Administratif Republik Indonesia 2026 (Standar Kemendagri: 38 Provinsi, 514 Kab/Kota, 7.277 Kecamatan, 83.763 Kelurahan/Desa beserta kode pos & koordinat).">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Swagger UI 5 -->
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
    
    <style>
        :root {
            --bg-base: #07090e;
            --bg-surface: #0e1526;
            --bg-card: rgba(15, 23, 42, 0.75);
            --bg-card-hover: rgba(30, 41, 59, 0.85);
            --border: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(56, 189, 248, 0.35);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #0284c7;
            --primary-hover: #0369a1;
            --cyan: #38bdf8;
            --emerald: #10b981;
            --amber: #f59e0b;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-main);
            font-family: var(--font-sans);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Sticky Navbar */
        .navbar {
            background: rgba(10, 15, 26, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
            color: #ffffff;
            box-shadow: 0 0 16px rgba(56, 189, 248, 0.3);
        }

        .brand-title {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: #ffffff;
            display: block;
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .brand-badge {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            font-size: 10.5px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* Navigation Actions */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-nav {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border: 1px solid var(--border);
            padding: 7px 14px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-nav:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            border: 1px solid rgba(56, 189, 248, 0.4);
            padding: 7px 16px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
            transition: all 0.15s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4);
            transform: translateY(-1px);
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 6px);
            background: #0f172a;
            border: 1px solid var(--border);
            border-radius: 8px;
            min-width: 190px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            padding: 6px;
            z-index: 110;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 7px 12px;
            font-size: 12.5px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.1s ease;
        }

        .dropdown-item:hover {
            background: rgba(56, 189, 248, 0.12);
            color: #38bdf8;
        }

        /* Container & Hero Card */
        .container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 24px 20px;
        }

        .hero-card {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.8) 0%, rgba(10, 15, 26, 0.9) 100%);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px 32px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #0284c7 0%, #38bdf8 50%, #10b981 100%);
        }

        .hero-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 18px;
        }

        .hero-title h1 {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .hero-title p {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 6px;
            max-width: 820px;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin: 20px 0;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 16px;
        }

        .stat-num {
            font-size: 20px;
            font-weight: 800;
            font-family: var(--font-mono);
            color: var(--cyan);
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Base URL Bar */
        .base-url-bar {
            background: rgba(3, 7, 18, 0.7);
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 8px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .base-url-left {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--font-mono);
            font-size: 13px;
        }

        .base-url-prefix {
            color: #34d399;
            font-weight: 700;
            font-size: 11px;
            background: rgba(16, 185, 129, 0.12);
            padding: 2px 6px;
            border-radius: 4px;
        }

        .base-url-text {
            color: #f1f5f9;
        }

        .btn-copy-url {
            background: rgba(56, 189, 248, 0.1);
            color: var(--cyan);
            border: 1px solid rgba(56, 189, 248, 0.25);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-copy-url:hover {
            background: rgba(56, 189, 248, 0.2);
            border-color: rgba(56, 189, 248, 0.4);
        }

        /* ========================================================
           SWAGGER UI MODERN DARK THEME OVERRIDES
           ======================================================== */
        #swagger-ui-container {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px 20px 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        .swagger-ui {
            font-family: var(--font-sans) !important;
            color: #cbd5e1 !important;
        }

        /* Hide the default swagger topbar & redundant default info block */
        .swagger-ui .topbar { display: none !important; }
        .swagger-ui .info { display: none !important; }

        /* Scheme & Server Container */
        .swagger-ui .scheme-container {
            background: transparent !important;
            box-shadow: none !important;
            padding: 12px 0 20px 0 !important;
            border-bottom: 1px solid var(--border) !important;
            margin-bottom: 20px !important;
        }

        .swagger-ui .servers-title {
            color: var(--text-muted) !important;
            font-size: 13px !important;
            font-weight: 600 !important;
        }

        .swagger-ui .servers > label select {
            background: #0f172a !important;
            color: #f8fafc !important;
            border: 1px solid rgba(56, 189, 248, 0.3) !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-family: var(--font-sans) !important;
            font-size: 13px !important;
            cursor: pointer !important;
        }

        /* Filter Box */
        .swagger-ui .filter-container {
            padding: 12px 0 16px !important;
        }

        .swagger-ui .filter-container input[type="text"] {
            background: #0f172a !important;
            color: #ffffff !important;
            border: 1px solid var(--border) !important;
            border-radius: 8px !important;
            padding: 10px 16px !important;
            font-size: 13.5px !important;
            font-family: var(--font-sans) !important;
        }

        .swagger-ui .filter-container input[type="text"]:focus {
            border-color: var(--cyan) !important;
            outline: none !important;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.2) !important;
        }

        /* Operation Tags (Section headers) */
        .swagger-ui .opblock-tag {
            color: #ffffff !important;
            font-size: 18px !important;
            font-weight: 700 !important;
            border-bottom: 1px solid var(--border) !important;
            padding: 18px 0 10px 0 !important;
        }

        .swagger-ui .opblock-tag small {
            color: var(--text-muted) !important;
            font-size: 13px !important;
        }

        .swagger-ui .opblock-tag:hover {
            color: var(--cyan) !important;
        }

        /* Operation Blocks (Endpoints) */
        .swagger-ui .opblock {
            background: rgba(15, 23, 42, 0.75) !important;
            border: 1px solid var(--border) !important;
            border-radius: 10px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
            margin: 0 0 14px 0 !important;
            transition: all 0.15s ease !important;
        }

        .swagger-ui .opblock:hover {
            border-color: rgba(56, 189, 248, 0.3) !important;
            background: rgba(20, 30, 52, 0.85) !important;
        }

        .swagger-ui .opblock.opblock-get {
            border-color: rgba(2, 132, 199, 0.3) !important;
            background: rgba(2, 132, 199, 0.04) !important;
        }

        .swagger-ui .opblock.opblock-get .opblock-summary-method {
            background: #0284c7 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 6px !important;
            font-size: 12px !important;
            min-width: 72px !important;
            text-align: center !important;
        }

        .swagger-ui .opblock.opblock-post .opblock-summary-method {
            background: #10b981 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 6px !important;
            font-size: 12px !important;
            min-width: 72px !important;
        }

        .swagger-ui .opblock-summary-path {
            color: #ffffff !important;
            font-family: var(--font-mono) !important;
            font-size: 14px !important;
            font-weight: 600 !important;
        }

        .swagger-ui .opblock-summary-description {
            color: #cbd5e1 !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
        }

        .swagger-ui .opblock .opblock-summary {
            padding: 10px 16px !important;
            border-bottom: 1px solid transparent !important;
        }

        .swagger-ui .opblock.is-open .opblock-summary {
            border-bottom-color: var(--border) !important;
        }

        .swagger-ui .opblock-body {
            background: rgba(10, 15, 26, 0.95) !important;
            padding: 20px !important;
            border-bottom-left-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
        }

        /* Visibility: Text inside operations description wrapper */
        .swagger-ui .opblock-description-wrapper,
        .swagger-ui .opblock-description-wrapper *,
        .swagger-ui .opblock-description,
        .swagger-ui .opblock-description *,
        .swagger-ui .opblock-external-docs-wrapper,
        .swagger-ui .opblock-external-docs-wrapper *,
        .swagger-ui .opblock-title_normal p,
        .swagger-ui .renderedMarkdown,
        .swagger-ui .renderedMarkdown *,
        .swagger-ui .markdown,
        .swagger-ui .markdown * {
            color: #f8fafc !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
        }

        /* Parameters & Section Header */
        .swagger-ui .opblock-section-header {
            background: transparent !important;
            box-shadow: none !important;
            padding: 12px 0 8px 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .swagger-ui .opblock-section-header h4,
        .swagger-ui .tabli button.tablinks {
            color: var(--cyan) !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            letter-spacing: -0.01em !important;
        }

        .swagger-ui .tabli.active button.tablinks {
            color: #38bdf8 !important;
            border-bottom: 2px solid #38bdf8 !important;
        }

        .swagger-ui .parameters-container .parameters-header,
        .swagger-ui .parameters-container th,
        .swagger-ui table thead tr th {
            color: #f1f5f9 !important;
            font-size: 12.5px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.18) !important;
            padding: 10px 8px !important;
        }

        .swagger-ui table tbody tr td {
            color: #f8fafc !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
            padding: 14px 10px !important;
            vertical-align: top !important;
        }

        .swagger-ui .parameter__name {
            color: #ffffff !important;
            font-family: var(--font-mono) !important;
            font-size: 13.5px !important;
            font-weight: 700 !important;
        }

        .swagger-ui .parameter__name.required {
            font-weight: 700 !important;
        }

        .swagger-ui .parameter__name.required span {
            color: #f43f5e !important;
        }

        .swagger-ui .parameter__type {
            color: var(--cyan) !important;
            font-family: var(--font-mono) !important;
            font-size: 11.5px !important;
            font-weight: 600 !important;
        }

        .swagger-ui .parameter__in {
            color: #a1a1aa !important;
            font-family: var(--font-mono) !important;
            font-size: 11.5px !important;
            font-style: italic !important;
            font-weight: 500 !important;
        }

        .swagger-ui .parameter__extension,
        .swagger-ui .parameter__default,
        .swagger-ui .parameters-col_description,
        .swagger-ui .parameters-col_description * {
            color: #e2e8f0 !important;
            font-size: 13.5px !important;
            line-height: 1.5 !important;
        }

        .swagger-ui .parameters-col_description p {
            color: #e2e8f0 !important;
            font-size: 13.5px !important;
        }

        /* Inputs, Selects & Textarea */
        .swagger-ui input[type="text"], 
        .swagger-ui select,
        .swagger-ui textarea {
            background: #0f172a !important;
            border: 1px solid rgba(56, 189, 248, 0.4) !important;
            border-radius: 7px !important;
            color: #ffffff !important;
            font-family: var(--font-sans) !important;
            font-size: 13.5px !important;
            padding: 9px 14px !important;
            transition: all 0.15s ease !important;
        }

        .swagger-ui input[type="text"]::placeholder,
        .swagger-ui textarea::placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
            font-style: normal !important;
        }

        .swagger-ui input[type="text"]:focus,
        .swagger-ui select:focus,
        .swagger-ui textarea:focus {
            border-color: var(--cyan) !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2) !important;
            outline: none !important;
        }

        /* Buttons: Try it out, Cancel, Execute, Clear */
        .swagger-ui .btn.try-out__btn {
            background: rgba(56, 189, 248, 0.15) !important;
            border: 1px solid rgba(56, 189, 248, 0.45) !important;
            color: var(--cyan) !important;
            border-radius: 7px !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            padding: 6px 16px !important;
            transition: all 0.15s ease !important;
        }

        .swagger-ui .btn.try-out__btn:hover {
            background: rgba(56, 189, 248, 0.3) !important;
            border-color: var(--cyan) !important;
            color: #ffffff !important;
        }

        .swagger-ui .btn.cancel {
            background: rgba(244, 63, 94, 0.15) !important;
            border: 1px solid rgba(244, 63, 94, 0.45) !important;
            color: #fb7185 !important;
            border-radius: 7px !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            padding: 6px 16px !important;
            transition: all 0.15s ease !important;
        }

        .swagger-ui .btn.cancel:hover {
            background: rgba(244, 63, 94, 0.3) !important;
            color: #ffffff !important;
        }

        .swagger-ui .btn.execute {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
            border: 1px solid rgba(56, 189, 248, 0.5) !important;
            color: #ffffff !important;
            border-radius: 7px !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            padding: 8px 24px !important;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.4) !important;
        }

        .swagger-ui .btn.execute:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%) !important;
            transform: translateY(-1px) !important;
        }

        .swagger-ui .btn.btn-clear {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid var(--border) !important;
            color: #cbd5e1 !important;
            border-radius: 7px !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            padding: 6px 16px !important;
        }

        .swagger-ui .btn.btn-clear:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }

        /* Responses & Code Blocks */
        .swagger-ui .responses-wrapper .responses-inner h4,
        .swagger-ui .responses-wrapper .responses-inner h5,
        .swagger-ui .responses-inner h4,
        .swagger-ui .responses-inner h5 {
            color: #ffffff !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            margin: 14px 0 10px 0 !important;
        }

        .swagger-ui .responses-table {
            width: 100% !important;
        }

        .swagger-ui .responses-table thead th,
        .swagger-ui .responses-table th {
            color: #f1f5f9 !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.18) !important;
            padding: 10px 8px !important;
        }

        .swagger-ui .response-col_status {
            color: #34d399 !important;
            font-family: var(--font-mono) !important;
            font-size: 14px !important;
            font-weight: 800 !important;
        }

        .swagger-ui .response-col_description,
        .swagger-ui .response-col_description *,
        .swagger-ui .response-col_description__inner div.markdown p,
        .swagger-ui .response-col_description__inner div.renderedMarkdown p {
            color: #f8fafc !important;
            font-size: 13.5px !important;
            line-height: 1.5 !important;
        }

        .swagger-ui .response-control-media-type__title {
            color: #cbd5e1 !important;
            font-size: 12px !important;
            font-weight: 600 !important;
        }

        .swagger-ui .response-control-media-type__accept-message {
            color: #94a3b8 !important;
            font-size: 11px !important;
        }

        .swagger-ui .response-col_links {
            color: #94a3b8 !important;
            font-size: 12px !important;
        }

        .swagger-ui .highlight-code pre,
        .swagger-ui pre.microlight,
        .swagger-ui .curl-command pre,
        .swagger-ui .curl {
            background: #030712 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 8px !important;
            color: #38bdf8 !important;
            font-family: var(--font-mono) !important;
            font-size: 12.5px !important;
            padding: 14px 16px !important;
            line-height: 1.55 !important;
        }

        .swagger-ui .response-headers {
            color: #e2e8f0 !important;
            font-family: var(--font-mono) !important;
            font-size: 12px !important;
            background: #030712 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 8px !important;
            padding: 12px !important;
        }

        .swagger-ui .copy-to-clipboard button {
            background: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 4px !important;
            padding: 4px 8px !important;
        }

        /* Validator badge removal */
        .swagger-ui .validator,
        .swagger-ui a[href*="validator.swagger.io"] {
            display: none !important;
        }

        .swagger-ui .opblock .opblock-summary-path a {
            color: #ffffff !important;
        }

        .swagger-ui select option {
            background: #0f172a !important;
            color: #f8fafc !important;
        }

        /* Schemas & Models Accordion */
        .swagger-ui section.models {
            border: 1px solid var(--border) !important;
            border-radius: 12px !important;
            background: rgba(15, 23, 42, 0.7) !important;
            margin-top: 28px !important;
            padding: 16px !important;
        }

        .swagger-ui section.models h4 {
            color: #ffffff !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .swagger-ui .model-box {
            background: #0b0f19 !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
        }

        .swagger-ui .model-title {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 13.5px !important;
        }

        .swagger-ui .model {
            color: #e2e8f0 !important;
            font-family: var(--font-mono) !important;
            font-size: 12.5px !important;
        }

        .swagger-ui .prop-name {
            color: var(--cyan) !important;
            font-weight: 600 !important;
        }

        .swagger-ui .prop-type {
            color: #c084fc !important;
            font-family: var(--font-mono) !important;
        }

        .swagger-ui .prop-format {
            color: #94a3b8 !important;
        }

        .swagger-ui .property-row td {
            color: #e2e8f0 !important;
        }

        .swagger-ui .tabli button.tablinks {
            color: var(--text-muted) !important;
            font-weight: 600 !important;
            font-size: 12.5px !important;
        }

        .swagger-ui .tabli.active button.tablinks {
            color: var(--cyan) !important;
            border-bottom: 2px solid var(--cyan) !important;
            font-weight: 700 !important;
        }

        .swagger-ui .model-toggle {
            filter: invert(1) !important;
        }

        .swagger-ui .arrow {
            fill: #94a3b8 !important;
        }

        .swagger-ui .download-contents {
            background: rgba(56, 189, 248, 0.15) !important;
            border: 1px solid rgba(56, 189, 248, 0.4) !important;
            color: var(--cyan) !important;
            border-radius: 6px !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            padding: 5px 12px !important;
        }


        /* Responsive Adjustments */
        @media (max-width: 860px) {
            .navbar {
                padding: 12px 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .nav-actions {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 4px;
            }
            .hero-card {
                padding: 20px 16px;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            .base-url-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding: 32px 16px;
            text-align: center;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 13.5px;
        }

        .footer a {
            color: var(--cyan);
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header class="navbar">
        <a href="/api/documentation" class="brand">
            <div class="brand-logo">ID</div>
            <div>
                <span class="brand-title">API Wilayah Indonesia</span>
                <div class="brand-subtitle">
                    <span>Kemendagri Standard</span>
                    <span class="brand-badge">v3.3.0</span>
                    <span>&bull;</span>
                    <span style="color:#cbd5e1;">by Roedy Rustam</span>
                </div>
            </div>
        </a>

        <div class="nav-actions">
            <!-- Active indicator for Docs -->
            <a href="/api/documentation" class="btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                API Documentation
            </a>

            <!-- Live Playground Button -->
            <a href="/demo" class="btn-nav">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                Live Playground
            </a>

            <!-- Dropdown Master JSON -->
            <div class="dropdown">
                <button type="button" class="btn-nav">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Master JSON
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="dropdown-content">
                    <a href="/json/provinsi.json" target="_blank" class="dropdown-item">&bull; 38 Provinsi (JSON)</a>
                    <a href="/json/kabupaten.json" target="_blank" class="dropdown-item">&bull; 514 Kab/Kota (JSON)</a>
                    <a href="/json/kecamatan.json" target="_blank" class="dropdown-item">&bull; 7.277 Kecamatan (JSON)</a>
                    <a href="/json/kelurahan.json" target="_blank" class="dropdown-item">&bull; 83.763 Kelurahan (JSON)</a>
                </div>
            </div>

            <!-- Dropdown OpenAPI Specs -->
            <div class="dropdown">
                <button type="button" class="btn-nav">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
                    OpenAPI Spec
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="dropdown-content">
                    <a href="/docs/openapi.json" target="_blank" class="dropdown-item">&bull; OpenAPI 3.0.3 (JSON)</a>
                    <a href="/docs/openapi.yaml" target="_blank" class="dropdown-item">&bull; OpenAPI 3.0.3 (YAML)</a>
                </div>
            </div>

            <!-- Filament Admin Button -->
            <a href="/admin" class="btn-nav">
                Filament Admin
            </a>

            <!-- GitHub Button -->
            <a href="https://github.com/roedyrustam/API-Wilayah-2024" target="_blank" class="btn-nav" style="background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                GitHub
            </a>
        </div>
    </header>

    <main class="container">
        <!-- Hero Information Card -->
        <section class="hero-card">
            <div class="hero-header">
                <div class="hero-title">
                    <h1>
                        Dokumentasi Resmi REST API 2026
                        <span class="brand-badge" style="background:rgba(2,132,199,0.2); color:#38bdf8; border-color:rgba(56,189,248,0.3); font-size:12px;">OpenAPI 3.0.3</span>
                    </h1>
                    <p>
                        Spesifikasi teknis dan interactive testing console RESTful API Wilayah Administratif Republik Indonesia edisi 2026 standar resmi Kemendagri. Mendukung pencarian hierarkis 4-tingkat, GeoJSON RFC 7946, 5-digit Kode Pos, Single-Trip Cascader, dan Haversine Nearby Radius GIS.
                    </p>
                </div>
            </div>

            <!-- Stats Counters -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-num">38</div>
                    <div class="stat-label">Provinsi (Termasuk DOB 96 PBD)</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">514</div>
                    <div class="stat-label">Kabupaten/Kota (100% GIS Coords)</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">7.277</div>
                    <div class="stat-label">Kecamatan / Distrik</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">83.763</div>
                    <div class="stat-label">Kelurahan/Desa (+ Kode Pos 5-Digit)</div>
                </div>
            </div>

            <!-- Base URL Bar -->
            <div class="base-url-bar">
                <div class="base-url-left">
                    <span class="base-url-prefix">BASE URL v1</span>
                    <span class="base-url-text" id="base-url-display">https://apiwilayah2026.vercel.app/api/v1</span>
                </div>
                <button type="button" class="btn-copy-url" onclick="copyBaseUrl(this)">
                    Salin Base URL
                </button>
            </div>
        </section>

        <!-- Swagger UI Render Target -->
        <section id="swagger-ui-container">
            <div id="swagger-ui"></div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2024&ndash;2026 <strong>Roedy Rustam</strong>. Dibuat sesuai standar resmi Kementerian Dalam Negeri Republik Indonesia.</p>
            <p style="margin-top: 8px;">
                <a href="https://github.com/roedyrustam/API-Wilayah-2024" target="_blank">GitHub Repository</a> &bull;
                <a href="/demo">Interactive Playground</a> &bull;
                <a href="/admin">Filament Admin</a> &bull;
                <span>Lisensi Open-Source MIT</span>
            </p>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js"></script>
    <script>
        function copyBaseUrl(btn) {
            const text = document.getElementById('base-url-display').innerText;
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerText;
                btn.innerText = 'Tersalin!';
                btn.style.color = '#34d399';
                setTimeout(() => {
                    btn.innerText = orig;
                    btn.style.color = '';
                }, 1800);
            });
        }

        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: "/docs/openapi.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                displayRequestDuration: true,
                filter: true,
                tryItOutEnabled: true,
                persistAuthorization: true,
                defaultModelsExpandDepth: 1,
                defaultModelExpandDepth: 1,
                validatorUrl: null
            });
        };
    </script>
</body>
</html>
